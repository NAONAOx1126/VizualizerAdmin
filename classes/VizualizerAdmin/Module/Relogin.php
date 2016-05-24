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
 * 管理画面の再ログイン処理を実行する。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Module_Relogin extends Vizualizer_Plugin_Module
{

    function execute($params)
    {
        $loader = new Vizualizer_Plugin("Admin");
        if (Vizualizer_Session::get(VizualizerAdmin::SESSION_KEY) !== null) {
            $data = Vizualizer_Session::get(VizualizerAdmin::SESSION_KEY);

            // 管理者モデルを取得する。
            $companyOperator = $loader->loadModel("CompanyOperator");

            // 渡されたログインIDでレコードを取得する。
            $companyOperator->findByPrimaryKey($data["operator_id"]);

            // ログインIDに該当するアカウントが無い場合
            Vizualizer_Logger::writeDebug("Try Relogin AS :\r\n" . var_export($companyOperator->toArray(), true));
            if ($companyOperator->operator_id > 0) {
                // 再ログインに成功した場合には管理者情報をセッションに格納する。
                $companyOperator->administrator_flg = $companyOperator->role()->administrator_flg;
                Vizualizer_Session::set(VizualizerAdmin::SESSION_KEY, $companyOperator->toArray());

                // 再ログインした情報を属性に置き直し
                $attr = Vizualizer::attr();
                $attr[VizualizerAdmin::KEY] = $companyOperator;
            }
        }
    }
}
