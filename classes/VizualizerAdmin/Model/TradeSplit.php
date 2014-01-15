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
 * 取引分割設定のモデルです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Model_TradeSplit extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     *
     * @param $values モデルに初期設定する値
     */
    public function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("admin");
        parent::__construct($loader->loadTable("TradeSplits"), $values);
    }

    /**
     * 主キーでデータを取得する。
     *
     * @param $trade_split_id 取引分割ID
     */
    public function findByPrimaryKey($trade_split_id)
    {
        $this->findBy(array("trade_split_id" => $trade_split_id));
    }

    /**
     * 作業者IDと顧客IDでデータを取得する。
     *
     * @param $worker_operator_id 作業者ID
     * @param $customer_operator_id 顧客ID
     * @param $trade_type_id 取引種別ID
     */
    public function findByWorkerCustomerType($worker_operator_id, $customer_operator_id, $trade_type_id){
        $this->findBy(array("worker_operator_id" => $worker_operator_id, "customer_operator_id" => $customer_operator_id, "trade_type_id" => $trade_type_id));
    }

    /**
     * 取引IDでデータを取得する。
     *
     * @param $trade_id 取引ID
     */
    public function findAllByTrade($trade_id)
    {
        return $this->findAllBy(array("trade_id" => $trade_id));
    }

    /**
     * 作業者IDでデータを取得する。
     *
     * @param $worker_operator_id 作業者ID
     */
    public function findAllByWorker($worker_operator_id)
    {
        return $this->findAllBy(array("worker_operator_id" => $worker_operator_id));
    }


    /**
     * 窓口IDでデータを取得する。
     *
     * @param $contact_operator_id コード
     */
    public function findAllByContact($contact_operator_id)
    {
        return $this->findAllBy(array("contact_operator_id" => $contact_operator_id));
    }

    /**
     * 顧客IDでデータを取得する。
     *
     * @param $customer_operator_id 役割コード
     */
    public function findAllByCustomer($customer_operator_id)
    {
        return $this->findAllBy(array("customer_operator_id" => $customer_operator_id));
    }

    /**
     * 取引種別IDでデータを取得する。
     *
     * @param $trade_type_id 取引種別コード
     */
    public function findAllByTradeType($trade_type_id)
    {
        return $this->findAllBy(array("trade_type_id" => $trade_type_id));
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
     * この取引の窓口を取得する。
     *
     * @return 窓口
     */
    public function contact()
    {
        $loader = new Vizualizer_Plugin("admin");
        $companyOperator = $loader->loadModel("CompanyOperator");
        $companyOperator->findByPrimaryKey($this->contact_operator_id);
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
     * @return 顧客
     */
    public function tradeType()
    {
        $loader = new Vizualizer_Plugin("admin");
        $tradeType = $loader->loadModel("TradeType");
        $tradeType->findByPrimaryKey($this->trade_type_id);
        return $tradeType;
    }
}
