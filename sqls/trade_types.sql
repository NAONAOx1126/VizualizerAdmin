CREATE TABLE IF NOT EXISTS `admin_trade_types` (
  `trade_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '取引種別ID',
  `trade_type_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '取引種別名',
  `create_time` datetime NOT NULL COMMENT 'データ登録日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`trade_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='取引種別テーブル';
