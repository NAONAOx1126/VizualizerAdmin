<?php

/**
 * Copyright (C) 2012 Vizualizer All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @author    Naohisa Minagawa <info@vizualizer.jp>
 * @copyright Copyright (c) 2010, Vizualizer
 * @license http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 * @since PHP 5.3
 * @version   1.0.0
 */

/**
 * 取引のモデルです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Model_Trade extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     *
     * @param $values モデルに初期設定する値
     */
    public function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("admin");
        parent::__construct($loader->loadTable("Trades"), $values);
    }

    /**
     * 主キーでデータを取得する。
     *
     * @param $trade_id 取引ID
     */
    public function findByPrimaryKey($trade_id)
    {
        $this->findBy(array("trade_id" => $trade_id));
    }

    /**
     * 関連取引IDでデータを取得する。
     *
     * @param $trade_id 取引ID
     */
    public function findAllByRelated($trade_id)
    {
        return $this->findAllBy(array("related_trade_id" => $trade_id));
    }

    /**
     * 指定日に引き継ぎ対象となるでデータを取得する。
     *
     * @param $today 引き継ぎ取得の指定日
     */
    public function findAllByContinue($today)
    {
        // 指定日から指定日の所属する月の朔日を取得
        $month = date("Y-m-01", strtotime($today));

        // 該当の取引を取得
        $select = new Vizualizer_Query_Select($this->access);
        $select->addColumn($this->access->_W);
        $select->addWhere($this->access->continue_interval." > 0");
        $select->addWhere($this->access->billing_date." LIKE CONCAT(SUBSTRING(DATE_SUB(?, INTERVAL ".$this->access->continue_interval." MONTH), 1, 8), '%')", array($month));
        $select->addOrder($this->access->related_trade_id);
        $select->setLimit($this->limit, $this->offset);
        $sqlResult = $select->fetch($this->limit, $this->offset);
        $thisClass = get_class($this);
        $result = new Vizualizer_Plugin_ModelIterator($thisClass, $sqlResult);
        return $result;
    }

    /**
     * この取引の取引明細を取得する。
     *
     * @return 取引明細
     */
    public function details()
    {
        $loader = new Vizualizer_Plugin("admin");
        $tradeDetail = $loader->loadModel("TradeDetail");
        $tradeDetails = $tradeDetail->findAllByTrade($this->trade_id);
        return $tradeDetails;
    }

    /**
     * この取引の作業者を取得する。
     *
     * @return 作業者
     */
    public function worker()
    {
        $loader = new Vizualizer_Plugin("admin");
        $companyOperator = $loader->loadModel("CompanyOperator");
        $companyOperator->findByPrimaryKey($this->worker_operator_id);
        return $companyOperator;
    }

    /**
     * この取引の顧客を取得する。
     *
     * @return 顧客
     */
    public function customer()
    {
        $loader = new Vizualizer_Plugin("admin");
        $companyOperator = $loader->loadModel("CompanyOperator");
        $companyOperator->findByPrimaryKey($this->customer_operator_id);
        return $companyOperator;
    }

    /**
     * この取引の取引種別を取得する。
     *
     * @return 取引種別
     */
    public function tradeType()
    {
        $loader = new Vizualizer_Plugin("admin");
        $tradeType = $loader->loadModel("TradeType");
        $tradeType->findByPrimaryKey($this->trade_type_id);
        return $tradeType;
    }

    /**
     * この取引の取引ステータスを取得する。
     *
     * @return 取引ステータス
     */
    public function tradeStatus()
    {
        $loader = new Vizualizer_Plugin("admin");
        $tradeStatus = $loader->loadModel("TradeStatus");
        $tradeStatus->findByPrimaryKey($this->trade_status_id);
        return $tradeStatus;
    }

    /**
     * 明細から合計金額を計算する
     */
    public function calculate(){
        $subtotal = 0;
        $tax = 0;
        foreach($this->details() as $detail){
            $subtotal += $detail->price * $detail->quantity;
            $intax = floor($detail->price * $detail->tax_rate / 100) * $detail->quantity;
            switch($detail->tax_type){
                case 2:
                    // 内税の場合は小計から減算
                    $subtotal -= $intax;
                case 1:
                    // 内税／外税の場合は税額を加算
                    $tax += $intax;
                    break;
            }
        }
        $this->subtotal = $subtotal;
        $this->tax = $tax;
        $this->total = $this->subtotal - $this->discount + $this->tax;
        $this->save();
    }

    /**
     * 取引が分割可能な場合、取引を分割する。
     */
    public function split(){
        $loader = new Vizualizer_Plugin("admin");
        $tradeSplit = $loader->loadModel("TradeSplit");
        $tradeSplit->findByWorkerCustomerType($this->worker_operator_id, $this->customer_operator_id, $this->trade_type_id);
        if($tradeSplit->trade_split_id > 0){
            $splitted = $this->findAllByRelated($this->trade_id);

            if(!$splitted->valid()){
                // 分割したデータが無い場合は、新規作成のため取引の情報を連想配列にする。
                $arrTrade = $this->toArray();
                unset($arrTrade["trade_id"]);
                $arrTrade["related_trade_id"] = $this->trade_id;
                $arrTradeDetails = array();
                foreach($this->details() as $detail){
                    $arrTradeDetail = $detail->toArray();
                    unset($arrTradeDetail["trade_detail_id"]);
                    $arrTradeDetail["related_trade_detail_id"] = $detail->trade_detail_id;
                    $arrTradeDetails[] = $arrTradeDetail;
                }

                // フロントから顧客への取引データを作成
                $trade = $loader->loadModel("Trade", $arrTrade);
                $trade->worker_operator_id = $tradeSplit->contact_operator_id;
                $trade->save();
                foreach($arrTradeDetails as $arrTradeDetail){
                    $detail = $loader->loadModel("TradeDetail", $arrTradeDetail);

                    $detail->trade_id = $trade->trade_id;
                    $detail->save();
                }

                // 作業者からフロントへの取引データを作成（金額の計算はあとで実施）
                $trade = $loader->loadModel("Trade", $arrTrade);
                $trade->customer_operator_id = $tradeSplit->contact_operator_id;
                $trade->save();
                foreach($arrTradeDetails as $arrTradeDetail){
                    $detail = $loader->loadModel("TradeDetail", $arrTradeDetail);
                    $detail->trade_id = $trade->trade_id;
                    $detail->save();
                }
                $splitted = $this->findAllByRelated($baseTradeId);
            }

            // 日付／ステータス／金額を再設定
            foreach($splitted as $split){
                // 日付とステータスと金額を再設定
                $split->price_rate = "100";
                $split->subtotal = $this->subtotal;
                $split->discount = $this->discount;
                $split->tax = $this->tax;
                $split->total = $this->total;
                $split->start_date = $this->start_date;
                $split->planed_date = $this->planed_date;
                $split->ordered_date = $this->ordered_date;
                $split->delivered_date = $this->delivered_date;
                $split->billing_date = $this->billing_date;
                $split->payment_date = $this->payment_date;
                $split->complete_date = $this->complete_date;
                $split->trade_status_id = $this->trade_status_id;
                $split->description = $this->description;
                foreach($split->details() as $splitDetail){
                    $detail = $loader->loadModel("TradeDetail");
                    $detail->findByPrimaryKey($splitDetail->related_trade_detail_id);
                    if($detail->trade_detail_id > 0){
                        $splitDetail->trade_detail_name = $detail->trade_detail_name;
                        $splitDetail->price = $detail->price;
                        $splitDetail->quantity = $detail->quantity;
                        $splitDetail->unit = $detail->unit;
                        $splitDetail->tax_type = $detail->tax_type;
                        $splitDetail->tax_rate = $detail->tax_rate;
                        $splitDetail->save();
                    }else{
                        $splitDetail->delete();
                    }
                }
                foreach($this->details() as $detail){
                    $splitDetail = $loader->loadModel("TradeDetail");
                    $splitDetail->findByRelated($split->trade_id, $detail->trade_detail_id);
                    if(!($splitDetail->trade_detail_id > 0)){
                        $arrTradeDetail = $detail->toArray();
                        unset($arrTradeDetail["trade_detial_id"]);
                        $arrTradeDetail["trade_id"] = $split->trade_id;
                        $arrTradeDetail["related_trade_detail_id"] = $detail->trade_detail_id;
                        $splitDetail = $loader->loadModel("TradeDetail");
                        foreach($arrTradeDetail as $name => $value){
                            $splitDetail->$name = $value;
                        }
                        $splitDetail->save();
                    }
                }

                if($split->worker_operator_id == $tradeSplit->worker_operator_id && $split->customer_operator_id == $tradeSplit->contact_operator_id){
                    // 作業者からフロントの取引の場合は金額を計算
                    $split->price_rate = 100 - $tradeSplit->contact_mergin_rate;
                    $split->subtotal = floor($this->subtotal * $split->price_rate / 100);
                    $split->discount = floor($this->discount * $split->price_rate / 100);
                    $split->tax = floor($this->tax * $split->price_rate / 100);
                    $split->total = floor($this->total * $split->price_rate / 100);
                    foreach($split->details() as $splitDetail){
                        $detail = $loader->loadModel("TradeDetail");
                        $detail->findByPrimaryKey($splitDetail->related_trade_detail_id);
                        if($detail->trade_detail_id > 0){
                            $splitDetail->price = floor($detail->price * $split->price_rate / 100);
                            $splitDetail->save();
                        }
                    }
                }
                $split->save();
            }
        }
    }
}
