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
 * オペレータ営業日のモデルです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Model_CompanyOperatorActivity extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     *
     * @param $values モデルに初期設定する値
     */
    public function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("admin");
        parent::__construct($loader->loadTable("StoreOpens"), $values);
    }

    /**
     * 主キーでデータを取得する。
     *
     * @param $store_open_id 店舗営業日ID
     */
    public function findByPrimaryKey($activity_id)
    {
        $this->findBy(array("activity_id" => $activity_id));
    }

    /**
     * オペレータIDでデータを取得する。
     *
     * @param $operator_id オペレータID
     */
    public function findAllByOperatorId($operator_id)
    {
        return $this->findAllBy(array("operator_id" => $operator_id));
    }

    /**
     * 曜日でデータを取得する。
     */
    public function findByWeekday($operator_id, $week_index, $weekday)
    {
        $this->findBy(array("operator_id" => $operator_id, "week_index" => $week_index, "weekday" => $weekday));
    }

    /**
     * 日付でデータを取得する。
     */
    public function findByDate($operator_id, $open_date)
    {
        $this->findBy(array("operator_id" => $operator_id, "open_date" => $open_date));
    }

    /**
     * 営業日のオペレータを取得する。
     *
     * @return オペレータ
     */
    public function operator()
    {
        $loader = new Vizualizer_Plugin("admin");
        $operator = $loader->loadModel("CompanyOperator");
        $operator->findByPrimaryKey($this->operator_id);
        return $operator;
    }
}
