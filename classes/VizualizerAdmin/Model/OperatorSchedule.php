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
 * 管理画面ユーザーのスケジュールのモデルです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Model_OperatorSchedule extends Vizualizer_Plugin_Model
{

    /**
     * コンストラクタ
     *
     * @param $values モデルに初期設定する値
     */
    public function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("admin");
        parent::__construct($loader->loadTable("OperatorSchedules"), $values);
    }

    /**
     * 主キーでデータを取得する。
     *
     * @param $schedule_id スケジュールID
     */
    public function findByPrimaryKey($schedule_id)
    {
        $this->findBy(array("schedule_id" => $schedule_id));
    }

    /**
     * 開始日でデータを取得する。
     *
     * @param string $start_day
     */
    public function findAllByStartDay($start_day)
    {
        $result = $this->findAllBy(array("like:start_time" => date("Y-m-d", strtotime($start_day)) . " %"));
    }

    /**
     * 組織に所属するオペレータのリストを取得する。
     *
     * @return オペレータのリスト
     */
    public function operator()
    {
        $loader = new Vizualizer_Plugin("admin");
        $companyOperator = $loader->loadModel("CompanyOperator");
        $companyOperator->findByPrimaryKey($this->operator_id);
        return $companyOperator;
    }

    public function createSchedule($operator_id, $start_time, $end_time, $location = "", $schedule_id = null)
    {
        // 時間パラメータを調整
        $start_time = date("Y-m-d H:i:s", strtotime($start_time));
        $end_time = date("Y-m-d H:i:s", strtotime($end_time));

        // 指定した時間帯の予定状況を確認
        $condition = array("operator_id" => $operator_id, "ge:start_time" => $end_time, "le:end_time" => $start_time);
        if ($schedule_id > 0) {
            $condition["ne:schedule_id"] = $schedule_id;
        }
        $duplicated = $this->findAllBy($condition);
        if ($duplicated->valid()) {
            // 時間のかぶる予定があった場合はエラー
            throw new Vizualizer_Exception_Invalid("operator_id", "指定の時間帯には既に予定が設定されています。");
        }

        // 予定の登録
        if ($schedule_id > 0) {
            $this->findByPrimaryKey($schedule_id);
        }
        $this->operator_id = $operator_id;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
        $this->location = $location;
        $this->save();
    }
}
