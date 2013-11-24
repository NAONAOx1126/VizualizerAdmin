CREATE TABLE IF NOT EXISTS `admin_operator_schedules` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'スケジュールID',
  `operator_id` int(11) NOT NULL COMMENT 'オペレータID',
  `start_time` datetime NOT NULL COMMENT '開始時刻',
  `end_time` datetime NOT NULL COMMENT '終了時刻',
  `location` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '配置場所',
  `create_time` datetime NOT NULL COMMENT 'データ作成日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`schedule_id`),
  UNIQUE KEY `operator_id` (`operator_id`,`start_time`),
  KEY `start_time` (`start_time`,`end_time`)
  CONSTRAINT `admin_operator_schedules_ibfk_1` FOREIGN KEY (`operator_id`) REFERENCES `admin_company_operators` (`operator_id`) ON DELETE CASCADE ON UPDATE CASCADE,
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='オペレータスケジュールテーブル' AUTO_INCREMENT=1 ;
