<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;

use Symfony\Component\Finder\Finder;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

/**
 * Docker
 */
class Docker extends Command
{
    private $hostsFile = '/etc/hosts';

    /**
     * Sites constructor.
     * @param $msg
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * config.
     */
    protected function configure()
    {
        // 设置命令名称.
        $this->setName('docker');

        // 设置描述.
        $this->setDescription('set docker-lnmp');

        // 设置帮助说明.
        $this->setHelp('set docker-lnmp');

        // 配置一个选项.
        $this->addOption('php', null, InputOption::VALUE_REQUIRED, 'php version');
        $this->addOption('mysql', null, InputOption::VALUE_REQUIRED, 'mysql version');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * 获取 PHP 版本.
         */
        $phpVersion = $input->getOption('php');
        if (! $phpVersion) {
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'Please select PHP version (defaults 7.2.3)',
                ['PHP 5.6', 'PHP 7.1', 'PHP 7.2'],
                2
            );
            $question->setErrorMessage('PHP version %s is invalid.');
            $phpVersion = $helper->ask($input, $output, $question);
            $output->writeln('<info>You have just selected: </info><comment>' . $phpVersion . '</comment>');
        }

        /**
         * 获取 MySQL 版本.
         */
        $mysqlVersion = $input->getOption('mysql');
        if (! $mysqlVersion) {
            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion(
                'Please select MySQL version (defaults 5.7)',
                ['MySQL 5.6', 'MySQL 5.7'],
                1
            );
            $question->setErrorMessage('MySQL version %s is invalid.');
            $mysqlVersion = $helper->ask($input, $output, $question);
            $output->writeln('<info>You have just selected: </info><comment>' . $mysqlVersion . '</comment>');
        }

        /**
         * 定义端口.
         */
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            'Use the default port? (y/n)',
            true
        );
        $isUseDefaultPort = (bool)$helper->ask($input, $output, $question);
        $output->writeln('<info>You have just selected: </info><comment>' . ($isUseDefaultPort ? 'Yes' : 'No') . '</comment>');
        if ($isUseDefaultPort) {
            // 使用默认的端口.
            $defaultPorts = [
                'MySQL' => 3306,
                'Redis' => 9632,
                'POSTGRESQL' => 5432,
                'PHPMyADMIN' => 8080,
                'Nginx' => 80,
            ];
            foreach ($defaultPorts as $name => $port) {
                $output->writeln('<info>' . $name . ' port: </info><comment>' . $port . '</comment>');
            }
        } else {
            // todo. 自定义端口.
        }

        /**
         * 自动生成 Nginx 的 server 配置文件.
         */
        // 清空配置.
        $fs = new Filesystem();
        $fs->remove(_CONFIG_ . '/nginx/conf.d');
        // 生成配置. 判断是否需要在hosts中添加配置.
        $fs->mkdir(_CONFIG_ . '/nginx/conf.d', 0700);
        $allProjects = $this->getProjectName(_APP_);
        $demo = file_get_contents(_CONFIG_ . '/nginx/demo.conf');
        $hosts = file_get_contents($this->hostsFile);
        $flag = false;
        foreach ($allProjects as $key => $val) {
            $nginxConf = str_replace(['{$serverName}', '{$port}', '{$root}'], [$val, 80, '/app/' . $val], $demo);
            file_put_contents(_CONFIG_ . '/nginx/conf.d/' . $val, $nginxConf);
            if (strpos($hosts, $val) === false) {
                if (!$flag) {
                    $output->writeln('<comment>请修改 hosts 文件, 加入以下内容:</comment>');
                    $flag = true;
                }
                // 加入到hosts中. todo. 如何自动修改?
                $output->writeln('<comment>127.0.0.1 ' . $val . '</comment>');
            }
        }
        $output->writeln('<info>create Nginx server config access.</info>');
    }

    /**
     * 获取目录下的文件名称. (不包含子目录)
     *
     * @param string $dir
     * @return array
     */
    private function getProjectName($dir)
    {
        $handler = opendir($dir);
        while (($filename = readdir($handler)) !== false) {
            if ($filename != "." && $filename != "..") {
                $files[] = $filename ;
            }
        }
        closedir($handler);
        return $files;
    }
}
