CREATE TABLE IF NOT EXISTS `admin_trade_splits` (
  `trade_split_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '取引分割ID',
  `worker_operator_id` int(11) NOT NULL COMMENT '作業オペレータID',
  `contact_operator_id` int(11) NOT NULL COMMENT 'フロントオペレータID',
  `customer_operator_id` int(11) NOT NULL COMMENT '顧客オペレータID',
  `contact_mergin_rate` int(11) NOT NULL COMMENT 'フロントマージン比率（%）',
  `create_time` datetime NOT NULL COMMENT 'データ登録日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`trade_split_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='取引分割テーブル';
