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
 * Demo
 */
class Demo extends Command
{
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
        $this->setName('docker');

        // 设置描述.
        $this->setDescription('set docker-lnmp');

        // 设置帮助说明.
        $this->setHelp('set docker-lnmp');

        // 配置一个参数.
        // $this->addArgument('apiName', InputArgument::REQUIRED, 'The API name.');

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
        // 获取参数和选项.
        $apiName = $input->getArgument('apiName');

        /**
         * 使用颜色样式
         */
        // green text / 绿字
        $output->writeln('<info>foo</info>');
        // yellow text / 黄字
        $output->writeln('<comment>foo</comment>');
        // black text on a cyan background / 青色背景上的黑字
        $output->writeln('<question>foo</question>');
        // white text on a red background / 红背景上的白字
        $output->writeln('<error>foo</error>');

        /**
         * 使用 OutputFormatterStyle 类，也可以建立你自己的样式:
         *
         * 可用的前景和背景颜色是: black, red, green, yellow, blue, magenta, cyan 以及 white.
         * 另有可用的选项是: bold, underscore, blink, reverse (可开启 "reverse video" 模式，即将前景和背景颜色互换) 以及 conceal (设置前景的颜色为透明，可隐藏上屏的文字 - 却仍可以选择和复制; 此选项在要求用户键入敏感信息时常会用到)。
         */
        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('fire', $style);
        $output->writeln('<fire>foo</fire>');
        // green text / 绿字
        $output->writeln('<fg=green>foo</>');
        // black text on a cyan background / 青背景上的黑字
        $output->writeln('<fg=black;bg=cyan>foo</>');
        // bold text on a yellow background / 黄背景上的粗字
        $output->writeln('<bg=yellow;options=bold>foo</>');

        /**
         * 要求用户确认.
         *
         * 用户会被问到 "Continue with this action?"。如果用户回答 y 它就返回 true，或者 false，如果答案是 n 的话。
         * __construct() 的第二个参数，是当用户不键入任何有效input时，返回的默认值。如果没有提供第二个参数， true 会被取用。
         */
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Continue with this action?', false);
        if (!$helper->ask($input, $output, $question)) {
            return;
        }

        /**
         * 询问用户信息.
         *
         * 用户会被问 "Please enter the name of the bundle"。他们可以键入一些会被 ask() 方法返回的名称。
         * 如果用户留空，默认值 (此处是AcmeDemoBundle) 会被返回。
         */
        $question = new Question('Please enter the name of the bundle', 'AcmeDemoBundle');
        $bundle = $helper->ask($input, $output, $question);

        /**
         * 让用户从答案列表中选择.
         *
         * 如果你预定义了一组答案让用户从中选择，你可以使用 ChoiceQuestion，它确保用户只能从预定义列表中输入有效字符串:
         * 默认被选中的选项由构造器的第三个参数提供。默认是 null，代表没有默认的选项。
         * 如果用户输入了无效字符串，会显示一个错误信息，用户会被要求再一次提供答案，直到他们输入一个有效字符串，或是达到了尝试上限为止。
         * 默认的最大尝试次数是 null，代表可以无限次尝试。你可以使用 setErrorMessage() 定义自己的错误信息。
         */
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select your favorite color (defaults to red)',
            array('red', 'blue', 'yellow'),
            0
        );
        $question->setErrorMessage('Color %s is invalid.');
        $color = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: '.$color);

        /**
         * 多选.
         *
         * 有时，可以给出多个答案。 ChoiceQuestion 使用逗号分隔的值，提供了此项功能。默认是禁用的，开启它可使用 setMultiselect():
         * 现在，当用户键入 1,2，结果会是: You have just selected: blue, yellow。
         * 如果用户不键入任何内容，结果是: You have just selected: red, blue。
         */
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select your favorite colors (defaults to red and blue)',
            array('red', 'blue', 'yellow'),
            '0,1'
        );
        $question->setMultiselect(true);
        $colors = $helper->ask($input, $output, $question);
        $output->writeln('You have just selected: ' . implode(', ', $colors));

        /**
         * 自动完成.
         *
         * 对于给定的问题，你也可以提供一个潜在答案的数组。它们将根据用户的敲击而自动完成.
         */
        $bundles = array('AcmeDemoBundle', 'AcmeBlogBundle', 'AcmeStoreBundle');
        $question = new Question('Please enter the name of a bundle', 'FooBundle');
        $question->setAutocompleterValues($bundles);
        $name = $helper->ask($input, $output, $question);

        /**
         * 隐藏用户响应.
         *
         * 你也可以在问问题时隐藏响应。这对密码来说极为方便：
         * 当你提问并隐藏响应时，Symofny将使用一个二进制的change stty mode或是使用另一种手段来隐藏之。如果都不可用，它就回滚为响应可见，
         * 除非你像上例那样，使用 setHiddenFallback() 来将此行为设置成 false。本例中，一个 RuntimeException 异常会被抛出。
         */
        $question = new Question('What is the database password?');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $question);

        /**
         * 验证答案.
         *
         * 你甚至可以验证答案。例如，前面例子中你曾询问过bundle名称。根据Symfony的命名约定，它应该被施以 Bundle 后缀，你可以使用 setValidator() 方法来验证它:
         * $validator是一个callback，专门处理验证。它在有错误发生时应抛出一个异常。异常信息会被显示在控制台中，所以在里面放入一些有用的信息是一个很好的实践。回调函数在验证通过时，应该返回用户的input。
         * 你可以用 setMaxAttempts() 方法来设置（验证失败时的）最大的提问次数。如果达到最大值，它将使用默认值。使用 null 代表可以无限次尝试回答（直到验证通过）。用户将被始终提问，直到他们提供了有效答案为止，也只有输入有效时命令才会继续执行。
         */
        $question = new Question('Please enter the name of the bundle', 'AcmeDemoBundle');
        $question->setValidator(function ($answer) {
            if ('Bundle' !== substr($answer, -6)) {
                throw new \RuntimeException(
                    'The name of the bundle should be suffixed with \'Bundle\''
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(2);
        $name = $helper->ask($input, $output, $question);

        /**
         * 验证一个隐藏的响应.
         *
         * 你也可以在隐藏（答案输入）的提问中使用validator：
         */
        $helper = $this->getHelper('question');

        $question = new Question('Please enter your password');
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The password can not be empty');
            }

            return $value;
        });
        $question->setHidden(true);
        $question->setMaxAttempts(20);
        $password = $helper->ask($input, $output, $question);
    }
}
