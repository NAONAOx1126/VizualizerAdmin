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
 * オペレータのスケジュールを保存する。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Module_Schedule_Save extends Vizualizer_Plugin_Module
{

    function execute($params)
    {
        $post = Vizualizer::request();
        if ($post["add"] || $post["save"]) {
            $loader = new Vizualizer_Plugin("Admin");
            $model = $loader->loadModel("OperatorSchedule");

            // トランザクションの開始
            $connection = Vizualizer_Database_Factory::begin("admin");

            try {
                if ($post["schedule_id"] > 0) {
                    $model->createSchedule($post["operator_id"], $post["start_time"], $post["end_time"], $post["location"], $post["schedule_id"]);
                } else {
                    $model->createSchedule($post["operator_id"], $post["start_time"], $post["end_time"], $post["location"]);
                }

                // エラーが無かった場合、処理をコミットする。
                Vizualizer_Database_Factory::commit($connection);

                $this->removeInput("add");
                $this->removeInput("save");
                $this->reload();
            } catch (Exception $e) {
                Vizualizer_Database_Factory::rollback($connection);
                throw $e;
            }
        }
    }
}
