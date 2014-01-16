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
 * admin_trade_detailsテーブルの定義クラスです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Table_TradeDetails extends Vizualizer_Plugin_Table
{

    /**
     * コンストラクタです。
     */
    public function __construct()
    {
        parent::__construct("admin_trade_details", "admin");
    }

    /**
     * テーブルを作成するためのスタティックメソッドです。。
     */
    public static function install()
    {
        $connection = Vizualizer_Database_Factory::begin("admin");
        try {
            // 依存テーブルをインストール
            VizualizerAdmin_Table_Companys::install();
            VizualizerAdmin_Table_Roles::install();

            // テーブルのインストール
            $connection->query(file_get_contents(dirname(__FILE__) . "/../../../sqls/trade_details.sql"));
            Vizualizer_Database_Factory::commit($connection);
        } catch (Exception $e) {
            Vizualizer_Database_Factory::rollback($connection);
        }
    }
}