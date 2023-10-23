<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_convo = "localhost";
$database_convo = "convo";
$username_convo = "root";
$password_convo = "";
$convo = mysql_pconnect($hostname_convo, $username_convo, $password_convo) or trigger_error(mysql_error(),E_USER_ERROR); 
?>