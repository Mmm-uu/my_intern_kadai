<?php defined('COREPATH') or exit('No direct script access allowed'); ?>

WARNING - 2025-08-20 10:43:30 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 10:43:30 --> Error - syntax error, unexpected '}', expecting end of file in /var/www/html/my_fuel_project/fuel/app/classes/controller/dashboard.php on line 40
WARNING - 2025-08-20 10:43:53 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 10:43:53 --> Error - The requested view could not be found: actions/index.php in /var/www/html/my_fuel_project/fuel/core/classes/view.php on line 440
WARNING - 2025-08-20 10:44:49 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 10:44:49 --> Notice - Undefined variable: actions_list in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 13
WARNING - 2025-08-20 10:46:10 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 10:46:32 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 10:46:32 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 10:47:37 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 10:47:45 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 10:48:16 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 10:48:16 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 11:25:26 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 11:25:26 --> Error - Call to undefined method Fuel\Core\DB::exper() in /var/www/html/my_fuel_project/fuel/app/classes/model/actions.php on line 23
WARNING - 2025-08-20 11:26:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 11:26:12 --> 1064 - You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near ') OR DATE(actions.created_at) = DATA_SUB(CURDATE(), INTERVAL actions.frequency D' at line 1 [ SELECT `actions*` FROM `actions` LEFT JOIN `records` ON (`actions`.`id` = `records`.`action_id`) WHERE `actions`.`deleted` = 0 AND (DATE(records.date) = DATA_SUB(CURDATE(), INTERVAL actions.frequency DAY) OR DATE(actions.created_at) = DATA_SUB(CURDATE(), INTERVAL actions.frequency DAY)) ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 11:28:57 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 11:28:57 --> 1054 - Unknown column 'actions*' in 'field list' [ SELECT `actions*` FROM `actions` LEFT JOIN `records` ON (`actions`.`id` = `records`.`action_id`) WHERE `actions`.`deleted` = 0 AND (DATE(records.date) = DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY) OR DATE(actions.created_at) = DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)) ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 11:29:16 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 11:29:16 --> 1054 - Unknown column 'actions*' in 'field list' [ SELECT `actions*` FROM `actions` LEFT JOIN `records` ON (`actions`.`id` = `records`.`action_id`) WHERE `actions`.`deleted` = 0 AND (DATE(records.date) = DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY) OR DATE(actions.created_at) = DATE_SUB(CURDATE(), INTERVAL actions.frequency DAY)) ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 11:31:36 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 11:32:34 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 11:32:38 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 11:32:57 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 11:51:18 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 11:51:18 --> Notice - Undefined variable: action in /var/www/html/my_fuel_project/fuel/app/views/today.php on line 5
WARNING - 2025-08-20 11:53:28 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 11:53:28 --> Notice - Undefined index: action_id in /var/www/html/my_fuel_project/fuel/app/views/today.php on line 5
WARNING - 2025-08-20 11:54:27 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 11:56:03 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 11:57:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 12:03:16 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 12:17:14 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 12:17:16 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 12:17:16 --> 1054 - Unknown column 'status' in 'field list' [ INSERT INTO `records` (`action_id`, `date`, `status`) VALUES ('2', 1755659836, 1) ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 12:17:41 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 12:17:41 --> 1054 - Unknown column 'status' in 'field list' [ INSERT INTO `records` (`action_id`, `date`, `status`) VALUES ('2', 1755659861, 1) ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 12:17:44 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 12:17:46 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 12:17:46 --> 1054 - Unknown column 'status' in 'field list' [ INSERT INTO `records` (`action_id`, `date`, `status`) VALUES ('1', 1755659866, 1) ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 12:20:13 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 12:20:40 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 12:22:20 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 12:23:23 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:25:22 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:25:23 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:25:32 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:25:49 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:25:52 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 13:25:52 --> 1292 - Incorrect datetime value: '1755663952' for column 'date' at row 1 [ INSERT INTO `records` (`action_id`, `date`, `status`) VALUES ('1', 1755663952, 1) ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 13:31:05 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:31:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:31:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:31:15 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:31:15 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:35:18 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:35:21 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:35:21 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 13:42:31 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 13:42:31 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 13:54:56 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 13:54:56 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:14:01 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:14:01 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:14:03 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:14:03 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:15:42 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:15:42 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:15:43 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:15:43 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:15:50 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:15:50 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:15:54 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:15:54 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:16:16 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:16:16 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:16:18 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:16:18 --> 1146 - Table 'my_app_db.actions' doesn't exist [ SELECT * FROM `actions` WHERE `deleted` = 0 ] in /var/www/html/my_fuel_project/fuel/core/classes/database/mysqli/connection.php on line 292
WARNING - 2025-08-20 15:22:43 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 15:50:39 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:50:39 --> Notice - Undefined variable: action in /var/www/html/my_fuel_project/fuel/app/views/index.php on line 5
WARNING - 2025-08-20 15:50:42 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:50:42 --> Notice - Undefined variable: action in /var/www/html/my_fuel_project/fuel/app/views/index.php on line 5
WARNING - 2025-08-20 15:51:18 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:51:18 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 15:54:20 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 15:54:20 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:02:10 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 16:02:11 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:02:11 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 16:02:11 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:02:13 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 16:02:13 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:02:14 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 16:02:14 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:02:17 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 16:02:17 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:02:19 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 16:02:19 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:02:25 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
ERROR - 2025-08-20 16:02:25 --> Fatal Error - Method Fuel\Core\View::__toString() must not throw an exception, caught ParseError: syntax error, unexpected 'else' (T_ELSE), expecting end of file in /var/www/html/my_fuel_project/fuel/app/views/dashboard.php on line 0
WARNING - 2025-08-20 16:03:38 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:38 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:39 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:40 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:40 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:41 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:41 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:42 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:43 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:43 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:43 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:50 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:05:50 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:06:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:06:12 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:44:13 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:55:44 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 16:55:44 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 17:51:02 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 17:51:15 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 17:51:15 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:16:36 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:53:32 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:05 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:22 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:23 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:24 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:24 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:24 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:25 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:25 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:25 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:58:25 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:59:06 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:59:07 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:59:07 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 18:59:07 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:01:16 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:02:54 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:02:54 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:03:03 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:03:10 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:07:01 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:07:01 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:07:13 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:07:13 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:12:57 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
WARNING - 2025-08-20 19:12:57 --> Fuel\Core\Fuel::init - The configured locale en_US is not installed on your system.
