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
 * オペレータのリストを取得する。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Module_Operator_List extends Vizualizer_Plugin_Module_List
{

    function execute($params)
    {
        $this->executeImpl($params, "Admin", "CompanyOperator", $params->get("result", "operators"));
        if ($params->get("with_company", "0") == "1") {
            $attr = Vizualizer::attr();
            $post = Vizualizer::request();

            // セレクトモードの時は通常の検索条件を適用しない
            if ($params->check("mode", "normal") == "select") {
                $savedPost = $post->export();
                $selectSearch = array();
                if($params->check("selectSearchKeys") && array_key_exists("search", $savedPost) && is_array($savedPost["search"])){
                    $selectKeys = explode(",", $params->get("selectSearchKeys"));
                    foreach($selectKeys as $key){
                        if(array_key_exists($key, $savedPost["search"])){
                            $selectSearch[$key] = $savedPost["search"][$key];
                        }
                    }
                }
                Vizualizer::request()->set("search", $selectSearch);
            }
            if (!$params->check("search") || isset($post[$params->get("search")])) {
                // サイトデータを取得する。
                $loader = new Vizualizer_Plugin("Admin");
                $model = $loader->loadModel("Company");

                // カテゴリが選択された場合、カテゴリの商品IDのリストを使う
                $conditions = $this->condition;
                if (is_array($post["search"])) {
                    foreach ($post["search"] as $key => $value) {
                        if (!$this->isEmpty($value)) {
                            if ($params->get("mode", "list") != "select" || !$params->check("select") || $key != substr($params->get("select"), 0, strpos($params->get("select"), "|"))) {
                                $conditions[$key] = $value;
                            }
                        }
                    }
                }

                $models = $model->findAllBy($conditions, $sortOrder, $sortReverse, $forceOperator);
                $list = array();
                if ($params->get("mode", "list") == "list") {
                    foreach($attr[$result] as $item){
                        $list[] = $item;
                    }
                    foreach($models as $item){
                        $list[] = $item;
                    }
                    $attr[$result] = $list;
                } elseif ($params->get("mode", "list") == "select") {
                        $list = $attr[$result];
                        foreach ($models as $model) {
                            $list["*" . $model->$select_key] = $model->$select_value;
                        }
                        $attr[$result] = $list;
                    }
                }
                if ($params->get("mode", "list") == "select") {
                    Vizualizer::request()->import($savedPost);
                }

        }
    }
}
