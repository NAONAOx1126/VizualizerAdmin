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
 * 管理画面のログイン処理を実行する。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Module_Login extends Vizualizer_Plugin_Module
{

    function execute($params)
    {
        $loader = new Vizualizer_Plugin("Admin");
        if (Vizualizer_Session::get(VizualizerAdmin::SESSION_KEY) === null) {
            $post = Vizualizer::request();
            if (isset($post["login"])) {
                // 管理者モデルを取得する。
                $companyOperator = $loader->loadModel("CompanyOperator");

                if(empty($post["login_id"])){
                    Vizualizer_Logger::writeDebug("ログインIDが入力されていません。");
                    throw new Vizualizer_Exception_Invalid("login", "ログインIDが入力されていません。");
                }

                // 渡されたログインIDでレコードを取得する。
                $companyOperator->findByLoginId($post["login_id"]);

                // ログインIDに該当するアカウントが無い場合
                Vizualizer_Logger::writeDebug("Try Login AS :\r\n" . var_export($companyOperator->toArray(), true));
                if (!($companyOperator->operator_id > 0)) {
                    Vizualizer_Logger::writeDebug("ログインIDに該当するアカウントがありません。");
                    throw new Vizualizer_Exception_Invalid("login", "ログイン情報が正しくありません。");
                }

                // 保存されたパスワードと一致するか調べる。
                if ($companyOperator->password != $this->encryptPassword($companyOperator->login_id, $post["password"])) {
                    Vizualizer_Logger::writeDebug("パスワードが一致しません");
                    throw new Vizualizer_Exception_Invalid("login", "ログイン情報が正しくありません。");
                }

                // アカウントが有効期限内か調べる。
                if (!empty($companyOperator->start_time) && time() < strtotime($companyOperator->start_time)) {
                    Vizualizer_Logger::writeDebug("アカウントが利用開始されていません。");
                    throw new Vizualizer_Exception_Invalid("login", "アカウントが利用開始されていません。");
                }

                // アカウントが有効期限内か調べる。
                if (!empty($companyOperator->end_time) && time() > strtotime($companyOperator->end_time)) {
                    Vizualizer_Logger::writeDebug("アカウントが有効期限切れです。");
                    throw new Vizualizer_Exception_Invalid("login", "アカウントが有効期限切れです。");
                }

                // ログインに成功した場合には管理者情報をセッションに格納する。
                $companyOperator->administrator_flg = $companyOperator->role()->administrator_flg;
                Vizualizer_Session::set(VizualizerAdmin::SESSION_KEY, $companyOperator->toArray());

                // 権限に自動遷移先が割り当てられている場合はリダイレクト
                if (!empty($companyOperator->role()->default_page)) {
                    $this->redirectInside($companyOperator->role()->default_page);
                } else {
                    $this->reload();
                }
            }
        }
        // 管理者モデルを復元する。
        $companyOperator = $loader->loadModel("CompanyOperator", Vizualizer_Session::get(VizualizerAdmin::SESSION_KEY));
        if ($companyOperator->operator_id > 0) {
            $attr = Vizualizer::attr();
            $attr[VizualizerAdmin::KEY] = $companyOperator;
        } else {
            Vizualizer_Logger::writeDebug("認証されていません。");
            throw new Vizualizer_Exception_Invalid("", "");
        }
    }
}
