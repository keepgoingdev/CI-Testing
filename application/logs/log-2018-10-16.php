<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2018-10-16 07:43:45 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DES' at line 6 - Invalid query: SELECT `tbl_course_event`.`id`, `tbl_course_event`.`course_code`, `tbl_course_event`.`customized`, `tbl_course_event`.`canceled`, `tbl_course_event`.`course_date`, `tbl_course_event`.`location`, `tbl_course_event`.`city`, `tbl_course_event`.`maximum_participants`, `tbl_course_event`.`mails_sent`, `tbl_course_event`.`certdip_sent`, `tbl_course`.`course_name`, GROUP_CONCAT(tbl_teacher.user_id) teachers_ids, GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name, (' '), tbl_teacher.last_name) SEPARATOR ', ') AS teachers
FROM `tbl_course_event`
LEFT JOIN `tbl_course` ON `tbl_course`.`id` = `tbl_course_event`.`course_id`
LEFT JOIN `tbl_course_event_teachers` ON `tbl_course_event_teachers`.`course_event_id` = `tbl_course_event`.`id`
LEFT JOIN `tbl_teacher` ON `tbl_teacher`.`id` = `tbl_course_event_teachers`.`teacher_id`
WHERE CONCAT((tbl_teacher.course_code), (tbl_teacher.location), (tbl_teacher.city), (tbl_teacher.course_date), (tbl_teacher.course_name), (tbl_teacher.first_name),(tbl_teacher.last_name)) LIKE '%sports%')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DESC
ERROR - 2018-10-16 07:46:19 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DES' at line 6 - Invalid query: SELECT `tbl_course_event`.`id`, `tbl_course_event`.`course_code`, `tbl_course_event`.`customized`, `tbl_course_event`.`canceled`, `tbl_course_event`.`course_date`, `tbl_course_event`.`location`, `tbl_course_event`.`city`, `tbl_course_event`.`maximum_participants`, `tbl_course_event`.`mails_sent`, `tbl_course_event`.`certdip_sent`, `tbl_course`.`course_name`, GROUP_CONCAT(tbl_teacher.user_id) teachers_ids, GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name, (' '), tbl_teacher.last_name) SEPARATOR ', ') AS teachers
FROM `tbl_course_event`
LEFT JOIN `tbl_course` ON `tbl_course`.`id` = `tbl_course_event`.`course_id`
LEFT JOIN `tbl_course_event_teachers` ON `tbl_course_event_teachers`.`course_event_id` = `tbl_course_event`.`id`
LEFT JOIN `tbl_teacher` ON `tbl_teacher`.`id` = `tbl_course_event_teachers`.`teacher_id`
WHERE CONCAT((tbl_course_event.course_code), (tbl_course_event.location), (tbl_course_event.city), (tbl_course_event.course_date), (tbl_course_event.course_name), (tbl_teacher.first_name),(tbl_teacher.last_name)) LIKE '%sport%')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DESC
ERROR - 2018-10-16 07:47:35 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DES' at line 6 - Invalid query: SELECT `tbl_course_event`.`id`, `tbl_course_event`.`course_code`, `tbl_course_event`.`customized`, `tbl_course_event`.`canceled`, `tbl_course_event`.`course_date`, `tbl_course_event`.`location`, `tbl_course_event`.`city`, `tbl_course_event`.`maximum_participants`, `tbl_course_event`.`mails_sent`, `tbl_course_event`.`certdip_sent`, `tbl_course`.`course_name`, GROUP_CONCAT(tbl_teacher.user_id) teachers_ids, GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name, (' '), tbl_teacher.last_name) SEPARATOR ', ') AS teachers
FROM `tbl_course_event`
LEFT JOIN `tbl_course` ON `tbl_course`.`id` = `tbl_course_event`.`course_id`
LEFT JOIN `tbl_course_event_teachers` ON `tbl_course_event_teachers`.`course_event_id` = `tbl_course_event`.`id`
LEFT JOIN `tbl_teacher` ON `tbl_teacher`.`id` = `tbl_course_event_teachers`.`teacher_id`
WHERE CONCAT((tbl_course_event.course_code), (tbl_course_event.location), (tbl_course_event.city), (tbl_course_event.course_date), (tbl_course.course_name), (tbl_teacher.first_name),(tbl_teacher.last_name)) LIKE '%sport%')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DESC
ERROR - 2018-10-16 07:47:43 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near ')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DES' at line 6 - Invalid query: SELECT `tbl_course_event`.`id`, `tbl_course_event`.`course_code`, `tbl_course_event`.`customized`, `tbl_course_event`.`canceled`, `tbl_course_event`.`course_date`, `tbl_course_event`.`location`, `tbl_course_event`.`city`, `tbl_course_event`.`maximum_participants`, `tbl_course_event`.`mails_sent`, `tbl_course_event`.`certdip_sent`, `tbl_course`.`course_name`, GROUP_CONCAT(tbl_teacher.user_id) teachers_ids, GROUP_CONCAT(DISTINCT CONCAT(tbl_teacher.first_name, (' '), tbl_teacher.last_name) SEPARATOR ', ') AS teachers
FROM `tbl_course_event`
LEFT JOIN `tbl_course` ON `tbl_course`.`id` = `tbl_course_event`.`course_id`
LEFT JOIN `tbl_course_event_teachers` ON `tbl_course_event_teachers`.`course_event_id` = `tbl_course_event`.`id`
LEFT JOIN `tbl_teacher` ON `tbl_teacher`.`id` = `tbl_course_event_teachers`.`teacher_id`
WHERE CONCAT((tbl_course_event.course_code), (tbl_course_event.location), (tbl_course_event.city), (tbl_course_event.course_date), (tbl_course.course_name), (tbl_teacher.first_name),(tbl_teacher.last_name)) LIKE '%s%')
GROUP BY `tbl_course_event`.`id`
ORDER BY `tbl_course_event`.`course_date` DESC
ERROR - 2018-10-16 13:47:58 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/edit_customer.php 222
ERROR - 2018-10-16 13:47:58 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/edit_customer.php 222
ERROR - 2018-10-16 13:47:58 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/edit_customer.php 239
ERROR - 2018-10-16 13:47:58 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/edit_customer.php 239
ERROR - 2018-10-16 19:12:20 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:12:20 --> Unable to connect to the database
ERROR - 2018-10-16 19:12:20 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:12:23 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:12:23 --> Unable to connect to the database
ERROR - 2018-10-16 19:12:23 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:14:10 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:14:10 --> Unable to connect to the database
ERROR - 2018-10-16 19:14:10 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:14:12 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:14:12 --> Unable to connect to the database
ERROR - 2018-10-16 19:14:12 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:14:12 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:14:12 --> Unable to connect to the database
ERROR - 2018-10-16 19:14:12 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:14:13 --> Unable to connect to the database
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:14:13 --> Unable to connect to the database
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:14:13 --> Unable to connect to the database
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_deltagare'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:14:13 --> Unable to connect to the database
ERROR - 2018-10-16 19:14:13 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:15:08 --> Severity: Warning --> mysqli::real_connect(): (HY000/1045): Access denied for user 'yocguyxk_labbmiljosuu'@'cpsrv38.misshosting.com' (using password: YES) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/database/drivers/mysqli/mysqli_driver.php 202
ERROR - 2018-10-16 19:15:08 --> Unable to connect to the database
ERROR - 2018-10-16 19:15:08 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Common.php 573
ERROR - 2018-10-16 19:22:08 --> 404 Page Not Found: Assets/global
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 71
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 72
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 4
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 5
ERROR - 2018-10-16 19:50:38 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/course_event.php 340
ERROR - 2018-10-16 19:50:46 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:50:46 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:50:46 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:50:46 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:50:46 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:50:46 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Input.php 410
ERROR - 2018-10-16 19:50:46 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Input.php 410
ERROR - 2018-10-16 19:50:46 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/libraries/Session/Session_driver.php 133
ERROR - 2018-10-16 19:50:46 --> Severity: Warning --> session_regenerate_id(): Cannot regenerate session id - headers already sent /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/libraries/Session/Session.php 644
ERROR - 2018-10-16 19:50:46 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 71
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 72
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 4
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 5
ERROR - 2018-10-16 19:52:03 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/course_event.php 340
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 71
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 72
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 4
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 5
ERROR - 2018-10-16 19:52:06 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/course_event.php 340
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 71
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 72
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 4
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 5
ERROR - 2018-10-16 19:52:08 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/course_event.php 340
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 26
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 71
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Course_event.php 72
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 4
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/includes/nav_bar.php 5
ERROR - 2018-10-16 19:52:09 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/course_event.php 340
ERROR - 2018-10-16 19:52:13 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:13 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:13 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:13 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:13 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:13 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:16 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:16 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:16 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:16 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:16 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:16 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:17 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:18 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:18 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:18 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:18 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:18 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:18 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:19 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:19 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:19 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:19 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:19 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:19 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:24 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:24 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:24 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:24 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:24 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:24 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:25 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:25 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:25 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:25 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:25 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:25 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:52:26 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:26 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:26 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:26 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:26 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/controllers/Login.php 14
ERROR - 2018-10-16 19:52:26 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/core/Exceptions.php:272) /home/yocguyxk/public_html/redheadgroup.se/deltagare/system/helpers/url_helper.php 564
ERROR - 2018-10-16 19:55:37 --> 404 Page Not Found: Assets/global
ERROR - 2018-10-16 19:57:58 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/edit_customer.php 239
ERROR - 2018-10-16 19:57:58 --> Severity: Notice --> Trying to get property of non-object /home/yocguyxk/public_html/redheadgroup.se/deltagare/application/views/content/edit_customer.php 239
