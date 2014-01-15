CREATE TABLE IF NOT EXISTS `admin_trade_details` (
  `trade_detail_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '案件明細ID',
  `trade_id` int(11) NOT NULL COMMENT '案件ID',
  `trade_detail_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '案件明細名',
  `price` int(11) NOT NULL COMMENT '単価',
  `quantity` int(11) NOT NULL COMMENT '数量',
  `unit` int(11) NOT NULL COMMENT '単位',
  `tax_type` int(11) NOT NULL COMMENT '消費税区分（1：外税、2：内税、3：非課税）',
  `tax_rate` int(11) NOT NULL COMMENT '消費税率',
  `create_time` datetime NOT NULL COMMENT 'データ登録日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`trade_detail_id`),
  KEY `trade_id` (`trade_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='案件明細テーブル';

ALTER TABLE `admin_trade_details`
  ADD CONSTRAINT `admin_trade_details_ibfk_1` FOREIGN KEY (`trade_id`) REFERENCES `admin_trades` (`trade_id`) ON DELETE CASCADE ON UPDATE CASCADE;
