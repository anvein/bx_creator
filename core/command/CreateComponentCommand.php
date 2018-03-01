<?php

namespace anvi\bxcreator\command;

use anvi\bxcreator\Application;
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
     * InputInterface  $input
     * @var null
     */
    protected $input = null;

    /**
     * OutputInterface  $input
     * @var null
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
                'langs',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Создавать ли lang файлы',
                []
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
                'complex_files',
                null,
                InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED,
                'Какие создать файлы для комплексного компонента? [news.php]',
                []
            )
            ->setDescription('Создание структуты компонента битрикса')
            ->setHelp('Создание структуты компонента битрикса');
    }


    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$app = Application::getInstance();

        $output->writeln(Color::col('===> Create Bitrix component', 'g'));

        $config = new CompConfigurator('component');
        $config = $this->setConfigParams($config, $input);

        $resValidate = $config->validate();
        if (is_array($resValidate)) {
            $this->printArray($resValidate, Color::col('Обнаружены ошибки в параметрах:', 'r'), $output);
        }

        // подтверждение пользователя
        if ($this->approveCreating($input, $output, $config)) {
            // TODO: передать creatoru конфиг

            $output->writeln(Color::col('Компонент успешно создан', 'g'));
        } else {
            $output->writeln(Color::col('Отмена создания компонента', 'r'));
        }
    }


    /**
     * Выводит массив в консоль
     * @param array           $arInfo - массив, который надо вывести
     * @param string          $title - залоговок
     * @param OutputInterface $output - объект вывода консоли
     */
    private function printArray(array $arInfo = [], $title = '', OutputInterface $output)
    {
        if (!empty($title)) {
            $output->writeln(Color::col($title, 'y'));
        }

        if (!empty($arInfo)) {
            foreach ($arInfo as $infoLine) {
                $output->writeln($infoLine);
            }
        }
    }


    /**
     * Интерактивный вопрос "Создавать ли компонент?"
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @param IConfigurator   $config
     * @return bool - продолжить ли создание компонента?
     */
    private function approveCreating(InputInterface $input, OutputInterface $output, IConfigurator $config)
    {
        $arInfo = $config->getInfo();
        $output->writeln(Color::col("==== Проверьте указанные параметры", 'y'));
        $this->printArray($arInfo, null, $output);

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            Color::col('Всё верно? Продолжить создание компонента? [y/n]', 'y'),
            false
        );
        $result = $helper->ask($input, $output, $question);

        return $result;
    }


    /**
     * Задает параметры конфигуратору из $input
     * @param IConfigurator  $config - объект конфигуратора
     * @param InputInterface $input - объект ввода консоли
     * @return IConfigurator - объект конфигуратора с заданными настройками
     */
    private function setConfigParams(CompConfigurator $config, InputInterface $input)
    {
        return $config
            ->setName($input->getArgument('name'))
            ->setPath($this->launchDir . DIRECTORY_SEPARATOR . $input->getArgument('path'))
            ->setNamespace($input->getOption('namespace'))
            ->setCreateLang($input->getOption('langs'))
            ->setCreateParams($input->getOption('params'))
            ->setComplexFiles($input->getOption('complex_files'))
            ->setCreateDescr($input->getOption('descr'))
            ->setType($input->getOption('type'));
    }

}