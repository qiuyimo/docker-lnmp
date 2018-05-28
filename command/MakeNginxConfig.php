<?php

namespace Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * MakeNginxConfig
 */
class MakeNginxConfig extends Command
{
    /**
     * MakeNginxConfig constructor.
     * @param $msg
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * config.
     *
     * 有三种参数变体可用：
     * InputArgument::REQUIRED  参数必填。如果不提供，则命令不运行.
     * InputArgument::OPTIONAL  参数可选，因此可以忽略.
     * InputArgument::IS_ARRAY  参数可以包含任意多个值。因此，它必须使用在参数列表中的最后一个.
     *
     * 有四种选项的变体可用：
     * InputOption::VALUE_IS_ARRAY  此选项可接收多个值 (如 --dir=/foo --dir=/bar);
     * InputOption::VALUE_NONE      此选项不接受输入的值 (如 --yell);
     * InputOption::VALUE_REQUIRED  此选项的值必填 (如 --iterations=5), 但选项本身仍然是可选的;
     * InputOption::VALUE_OPTIONAL  此选项的值可有可无 (e.g. --yell 或 --yell=loud)。
     */
    protected function configure()
    {
        // 设置命令名称.
        $this->setName('makeNginxConfig');

        // 设置描述.
        $this->setDescription('makeNginxConfig');

        // 设置帮助说明.
        $this->setHelp('makeNginxConfig');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        do {
            $question = new Question('Please enter the domain');
            $domain = $helper->ask($input, $output, $question);
        } while ($domain);
        $output->writeln('<info>You have just inputed: </info><comment>' . $domain . '</comment>');

    }
}
