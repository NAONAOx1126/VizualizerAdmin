CREATE TABLE IF NOT EXISTS `admin_trade_splits` (
  `trade_split_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '取引分割ID',
  `worker_operator_id` int(11) NOT NULL COMMENT '作業オペレータID',
  `contact_operator_id` int(11) NOT NULL COMMENT 'フロントオペレータID',
  `customer_operator_id` int(11) NOT NULL COMMENT '顧客オペレータID',
  `trade_type_id` int(11) NOT NULL COMMENT '取引種別ID',
  `contact_mergin_rate` int(11) NOT NULL COMMENT 'フロントマージン比率（%）',
  `create_time` datetime NOT NULL COMMENT 'データ登録日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`trade_split_id`),
  KEY `worker_operator_id` (`worker_operator_id`),
  KEY `contact_operator_id` (`contact_operator_id`),
  KEY `customer_operator_id` (`customer_operator_id`),
  KEY `trade_type_id` (`trade_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='取引分割テーブル';

ALTER TABLE `admin_trade_splits`
  ADD CONSTRAINT `admin_trade_splits_ibfk_4` FOREIGN KEY (`trade_type_id`) REFERENCES `admin_trade_types` (`trade_type_id`),
  ADD CONSTRAINT `admin_trade_splits_ibfk_1` FOREIGN KEY (`worker_operator_id`) REFERENCES `admin_company_operators` (`operator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_trade_splits_ibfk_2` FOREIGN KEY (`contact_operator_id`) REFERENCES `admin_company_operators` (`operator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_trade_splits_ibfk_3` FOREIGN KEY (`customer_operator_id`) REFERENCES `admin_company_operators` (`operator_id`) ON DELETE CASCADE ON UPDATE CASCADE;
