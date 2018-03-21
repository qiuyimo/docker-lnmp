<?php
/**
 * Created by PhpStorm.
 * User: qiuyu
 * Date: 2018/3/21
 * Time: 下午12:02
 */

$dbh = new PDO("mysql:host=mysql;dbname=test", 'root', 'root');
if ($dbh) {
    echo 'Connect MySQL access';
} else {
    echo 'Connect MySQL error';
}

echo "<hr/>";

$dbh = new PDO("pgsql:host=postgresql;port=5432;dbname=postgres", 'postgres', '');
// $dbh = new PDO("pgsql:host=192.168.10.2;port=5432;dbname=postgres", 'postgres', 'postgres');
if ($dbh) {
    echo 'Connect PostgreSQL access';
} else {
    echo 'Connect PostgreSQL error';
}

echo "<hr/>";

echo phpinfo();
