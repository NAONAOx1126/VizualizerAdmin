CREATE TABLE IF NOT EXISTS `admin_company_operator_activities` (
  `activity_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '店舗営業ID',
  `operator_id` int(11) NOT NULL COMMENT '店舗ID',
  `week_index` int(11) DEFAULT NULL COMMENT '月の何週目か、1週目なら1など',
  `weekday` int(11) DEFAULT NULL COMMENT '曜日、日曜:0、月曜:1など',
  `open_date` date DEFAULT NULL COMMENT '指定日、年は考慮しない',
  `open_time` time NOT NULL COMMENT '指定された営業日の開店時間',
  `close_time` time NOT NULL COMMENT '指定された営業日の閉店時間',
  `create_time` datetime NOT NULL COMMENT 'データ作成日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`activity_id`),
  KEY `week_index` (`week_index`,`weekday`,`open_date`),
  KEY `operator_id` (`operator_id`),
  CONSTRAINT `admin_company_operator_activities_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `admin_company_operators` (`operator_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='オペレータ営業日テーブル'