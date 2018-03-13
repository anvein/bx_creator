<?php

namespace anvi\bxcreator\command;

use anvi\bxcreator\Application;
use anvi\bxcreator\creator\SimpleCompCreator;
use anvi\bxcreator\tools\Color;
use anvi\bxcreator\configurator\IConfigurator;
use anvi\bxcreator\configurator\CompConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Exception;


class CreateComponentCommand extends Command
{
    /**
     * Папка из которой происходит запуск скрипта
     * @var null
     */
    protected $launchDir = null;


    /**
     * @var InputInterface
     */
    protected $input = null;

    /**
     * @var OutputInterface
     */
    protected $output = null;

    /**
     * CreateComponentCommand constructor.
     * @param string $launchDir - путь, откуда будет запущен скрипт
     * @throws Exception - если путь $launchDir не существует или не указан
     */
    public function __construct($launchDir)
    {
        if (empty($launchDir)) {
            throw new \Exception('Не указан обязательный аргумент $launchDir');
        } elseif (!is_dir($launchDir)) {
            throw new \Exception("Указанный путь {$launchDir} не существует");
        }

        $this->launchDir =  realpath($launchDir);

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('bxcreator:create_comp')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Название компонента [news.list]'
            )
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Путь где нужно создать компонент'
            )
            ->addOption(
                'type',
                null,
                InputOption::VALUE_REQUIRED,
                'Тип компонента [simple*/complex]',
                CompConfigurator::SIMPLE_COMPONENT
            )
            ->addOption(
                'namespace',
                null,
                InputOption::VALUE_OPTIONAL,
                'Пространство имен в котором создать компонент (namespace)'
            )
            ->addOption(
                'lang',
                null,
//                InputOption::VALUE_REQUIRED,
                InputOption::VALUE_NONE,
                'Для каких языков создать lang файлы [--lang=ru,en]'
            )
            ->addOption(
                'params',
                null,
                InputOption::VALUE_NONE,
                'Создавать ли файл .parameters.php'
            )
            ->addOption(
                'descr',
                null,
                InputOption::VALUE_NONE,
                'Создавать ли файл .description.php'
            )
            ->addOption(
                'complex_file',
                null,
                InputOption::VALUE_REQUIRED,
                'Какие создать файлы для комплексного компонента? [news,news.list,news.detail]',
                []
            )
            ->setDescription('Создание структуры компонента битрикса')
            ->setHelp('Создание структуты компонента битрикса');
    }


    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        //$app = Application::getInstance();

        $output->writeln(Color::col('===> Create Bitrix component', 'g'));

        $config = new CompConfigurator('component');
        $config = $this->setConfigParams($config);

        if (!$config->validate()) {
            $this->printArray($config->getErrors(), Color::col('Обнаружены ошибки в параметрах:', 'r'));
        }

        if (!$this->approveCreating($config)) {
            $output->writeln(Color::col('Отмена создания компонента', 'r'));
            return;
        }

        $creator = new SimpleCompCreator($config);
        $creator->run();

        if ($creator->isSuccess()) {
            $output->writeln(Color::col('Компонент успешно создан', 'g'));
        } else {
            $this->printArray(
                $creator->getErrors(),
                Color::col('При создании компонента возникли ошибки:', 'r')
            );
            $output->writeln(Color::col('Компонент не создан', 'r'));
        }
    }


    /**
     * Выводит массив в консоль
     * @param array           $arInfo - массив, который надо вывести
     * @param string          $title - залоговок
     */
    private function printArray(array $arInfo = [], $title = '')
    {
        if (!empty($title)) {
            $this->output->writeln(Color::col($title, 'y'));
        }

        if (!empty($arInfo)) {
            foreach ($arInfo as $infoLine) {
                $this->output->writeln($infoLine);
            }
        }
    }


    /**
     * Интерактивный вопрос "Создавать ли компонент?"
     * @param IConfigurator   $config
     * @return bool - продолжить ли создание компонента?
     */
    private function approveCreating(IConfigurator $config)
    {
        $arInfo = $config->getInfo();
        $this->output->writeln(Color::col("==== Проверьте указанные параметры", 'y'));
        $this->printArray($arInfo, null, $this->output);

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            Color::col('Всё верно? Продолжить создание компонента? [y/n]', 'y'),
            false
        );
        $result = $helper->ask($this->input, $this->output, $question);

        return $result;
    }

    /**
     * Задает параметры конфигуратору из $input
     * @param IConfigurator  $config - объект конфигуратора
     * @return IConfigurator - объект конфигуратора с заданными настройками
     */
    private function setConfigParams(IConfigurator $config)
    {
        return $config
            ->setName($this->input->getArgument('name'))
            ->setPath($this->launchDir . DIRECTORY_SEPARATOR . $this->input->getArgument('path'))
            ->setNamespace($this->input->getOption('namespace'))
            ->setCreateLang((bool)$this->input->getOption('lang'))
            ->setCreateParams($this->input->getOption('params'))
            ->setCreateDescr($this->input->getOption('descr'))
            ->setType($this->input->getOption('type'));
    }

}