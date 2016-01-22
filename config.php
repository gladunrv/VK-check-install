<?
define('APP_ID', '1111111');
define('APP_HASH', 'HASHHASHHASHHASHHASH');
DEFINE ('DB_NAME', "db_name");
DEFINE ('DB_LOGIN', "db_user");
DEFINE ('DB_PASS', "db_pass");
$mysql = mysql_connect('127.0.0.1', DB_LOGIN, DB_PASS);
if (!$mysql) die('SQL_ERROR');
mysql_select_db (DB_NAME, $mysql);
?>
