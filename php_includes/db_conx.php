<?php

require_once 'php_includes/credentials.php';
$db_conx = new mysqli($db_hostname, $db_username, $db_password, $db_database);
if ($db_conx->connect_error) die($db_conx->connect_error);

?>