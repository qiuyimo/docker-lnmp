#!/usr/bin/env php
<?php

/**
 * Google Sites API 入口
 * User: qiuyu
 * Date: 2018/4/24
 * Time: 上午9:49
 */
declare(ticks = 1);

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Symfony\Component\Finder\Finder;

define("_COMMAND_", __DIR__ . '/command');
define("_CONFIG_", __DIR__ . '/config');
define("_APP_", __DIR__ . '/app');

// symfony\console 实例化.
$application = new Application();

// 遍历添加命令, 命令位于 `./command/*`
$finder = new Finder();
$finder->files()->in(_COMMAND_);
foreach ($finder as $file) {
    $className = 'Command\\' . rtrim($file->getRelativePathname(), '.php');
    $application->add(new $className());
}

// 运行命令
try {
    $application->run();
} catch (Exception $e) {
    dd('运行错误');
}
