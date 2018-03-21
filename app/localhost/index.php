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

echo phpinfo();
