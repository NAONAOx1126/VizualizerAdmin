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
 * オペレータにメールを送信する。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Module_Mail extends Vizualizer_Plugin_Module
{

    function execute($params)
    {
        if($params->check("title") && $params->check("template")){
            $post = Vizualizer::request();
            if($params->check("operator_id")){
                if($params->get("opeartor_id") == "login"){
                    $attr = Vizualizer::attr();
                    $operatorId = $attr[VizualizerAdmin::KEY]->operator_id;
                }else{
                    $operatorId = $params->get("opeartor_id");
                }
            }else{
                $operatorId = $post["operator_id"];
            }
            $title = $params->get("title");
            $templateName = $params->get("template");

            $loader = new Vizualizer_Plugin("Admin");
            $operator = $loader->loadModel("CompanyOperator");
            $operator->findByPrimaryKey($operatorId);

            $attr = Vizualizer::attr();
            $template = $attr["template"];
            $body = $template->fetch($templateName.".txt");

            $mail = new Vizualizer_Sendmail();
            $mail->setFrom($params->get("from"));
            $mail->setTo($operator->email);
            $mail->setSubject($title);
            $mail->addBody($body);
            $mail->send();
        }
    }
}
