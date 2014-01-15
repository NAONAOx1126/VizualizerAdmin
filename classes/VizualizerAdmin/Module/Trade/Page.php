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
 * 取引のリストをページング付きで取得する。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Module_Trade_Page extends Vizualizer_Plugin_Module_Page
{

    function execute($params)
    {
        $loader = new Vizualizer_Plugin("Admin");
        $trade = $loader->loadModel("Trade");
        $trades = $trade->findAllBy(array("ge:related_trade_id" => "1"));
        $relatedIds = array();
        foreach($trades as $trade){
            $relatedIds[$trade->related_trade_id] = $trade->related_trade_id;
        }
        if(!empty($relatedIds)){
            $this->addCondition("nin:trade_id", array_values($relatedIds));
        }
        $this->executeImpl($params, "Admin", "Trade", $params->get("result", "trades"));
    }
}
