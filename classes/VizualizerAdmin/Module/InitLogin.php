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
 * 管理画面のログイン画面用の初期化処理を実行する。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Module_InitLogin extends Vizualizer_Plugin_Module
{

    function execute($params)
    {
        // ベースURLを取得
        if (substr(VIZUALIZER_SUBDIR, -1) == "/") {
            $baseUrl = substr(VIZUALIZER_SUBDIR, 0, -1);
        }else{
            $baseUrl = VIZUALIZER_SUBDIR;
        }

        // 現在表示しているページのHTMLを取得
        $currentPage = str_replace("?".$_SERVER["QUERY_STRING"], "", $_SERVER["REQUEST_URI"]);
        if(!empty($baseUrl) && strpos($currentPage, $baseUrl) === 0){
            $currentPage = substr($currentPage, strlen($baseUrl));
        }

        // 呼び出されたページを取得
        $attr = Vizualizer::attr();
        if($attr["templateName"] != $currentPage){
            $attr["loginUrl"] = $_SERVER["REQUEST_URI"];
        }else{
            $attr["loginUrl"] = $baseUrl.$params->get("default", "/index.html");
        }
    }
}
