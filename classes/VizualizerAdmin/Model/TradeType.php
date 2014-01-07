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
 * 取引種別のモデルです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Model_TradeType extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     *
     * @param $values モデルに初期設定する値
     */
    public function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("admin");
        parent::__construct($loader->loadTable("TradeTypes"), $values);
    }

    /**
     * 主キーでデータを取得する。
     *
     * @param $trade_type_id 取引種別ID
     */
    public function findByPrimaryKey($trade_type_id)
    {
        $this->findBy(array("trade_type_id" => $trade_type_id));
    }

    /**
     * この取引種別の取引分割設定を取得する。
     *
     * @return 取引分割設定
     */
    public function tradeSplits()
    {
        $loader = new Vizualizer_Plugin("admin");
        $tradeSplit = $loader->loadModel("TradeSplit");
        $tradeSplits = $tradeSplit->findAllByTradeType($this->trade_type_id);
        return $tradeSplits;
    }
}
