CREATE TABLE IF NOT EXISTS `admin_trade_statuses` (
  `trade_status_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '取引ステータスID',
  `trade_status_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '取引ステータス名',
  `planed_flg` tinyint(1) NOT NULL COMMENT '見積書発行可能フラグ',
  `ordered_flg` tinyint(1) NOT NULL COMMENT '注文書発行可能フラグ',
  `delivered_flg` tinyint(1) NOT NULL COMMENT '納品書発行可能フラグ',
  `billed_flg` tinyint(1) NOT NULL COMMENT '請求書発行可能フラグ',
  `complete_flg` tinyint(1) NOT NULL COMMENT '完了フラグ',
  `create_time` datetime NOT NULL COMMENT 'データ登録日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`trade_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='取引ステータステーブル';
