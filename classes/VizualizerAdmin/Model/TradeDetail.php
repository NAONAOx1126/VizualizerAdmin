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
 * 取引明細のモデルです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Model_TradeDetail extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     *
     * @param $values モデルに初期設定する値
     */
    public function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("admin");
        parent::__construct($loader->loadTable("TradeDetails"), $values);
    }

    /**
     * 主キーでデータを取得する。
     *
     * @param $trade_detail_id 取引明細ID
     */
    public function findByPrimaryKey($trade_detail_id)
    {
        $this->findBy(array("trade_detail_id" => $trade_detail_id));
    }

    /**
     * 関連取引明細と現取引でデータを取得する。
     *
     * @param $trade_id 取引ID
     * @param $trade_detail_id 関連取引明細ID
     */
    public function findByRelated($trade_id, $trade_detail_id)
    {
        $this->findBy(array("trade_id" => $trade_id, "related_trade_detail_id" => $trade_detail_id));
    }

    /**
     * 取引IDでデータを取得する。
     *
     * @param $trade_id 取引ID
     */
    public function findAllByTrade($trade_id){
        return $this->findAllBy(array("trade_id" => $trade_id));
    }

    /**
     * この取引明細の取引を取得する。
     *
     * @return 取引
     */
    public function trade()
    {
        $loader = new Vizualizer_Plugin("admin");
        $trade = $loader->loadModel("Trade");
        $trade->findByPrimaryKey($this->trade_id);
        return $trade;
    }
}
