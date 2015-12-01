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
 * 管理画面メールテンプレートのモデルです。
 *
 * @package VizualizerAdmin
 * @author Naohisa Minagawa <info@vizualizer.jp>
 */
class VizualizerAdmin_Model_MailTemplate extends Vizualizer_Plugin_Model
{
    /**
     * 組織のキャッシュ用変数
     */
    private $company;

    /**
     * コンストラクタ
     */
    public function __construct($values = array())
    {
        $loader = new Vizualizer_Plugin("admin");
        parent::__construct($loader->loadTable("MailTemplates"), $values);
    }

    /**
     * 主キーでオペレータを検索する。
     */
    public function findByPrimaryKey($mail_template_id)
    {
        $this->findBy(array("mail_template_id" => $mail_template_id));
    }

    /**
     * オペレータのログインIDでデータを検索する。
     */
    public function findByTemplateCode($company_id, $template_code)
    {
        $this->findBy(array("company_id" => $company_id, "template_code" => $template_code));
        // 該当のテンプレートがない場合はcompany_id = 0で検索する。
        if (!($this->mail_template_id > 0)) {
            $this->findBy(array("company_id" => "0", "template_code" => $template_code));
        }
    }

    /**
     * 組織のIDでオペレータのデータを検索する。
     */
    public function findAllByCompanyId($company_id)
    {
        return $this->findAllBy(array("company_id" => $company_id));
    }

    /**
     * オペレータの所属する組織のデータを取得する。
     */
    public function company()
    {
        if(!$this->company){
            $loader = new Vizualizer_Plugin("admin");
            $this->company = $loader->loadModel("Company");
            $this->company->findByPrimaryKey($this->company_id);
        }
        return $this->company;
    }
}
