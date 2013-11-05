CREATE TABLE IF NOT EXISTS `admin_roles` (
  `role_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ロールID',
  `role_code` varchar(15) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ロールの識別用コード',
  `role_name` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT 'ロール名',
  `create_time` datetime NOT NULL COMMENT 'データ作成日時',
  `update_time` datetime NOT NULL COMMENT 'データ最終更新日時',
  PRIMARY KEY (`role_id`),
  UNIQUE KEY `role_code` (`role_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci