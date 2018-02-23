<?php

namespace anvi\bxcreator\Command;

use anvi\bxcreator\IConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use anvi\bxcreator\Configurator;
use anvi\bxcreator\CompConfigurator;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Exception;
use anvi\bxcreator\ConsoleApplication;


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
            ->setName('bxcreator:create_simcomp')
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
                'langfile',
                null,
                InputOption::VALUE_NONE,
                'Создавать ли lang файлы'
            )
            ->addOption(
                'parameters',
                null,
                InputOption::VALUE_NONE,
                'Создавать ли файл .parameters.php'
            )
            ->addOption(
                'description',
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
        require_once __DIR__ . '../Application/ConsoleApplication.php';
        $consApp = ConsoleApplication::getInstance();


        $output->writeln('===> Create Bitrix component');

        $config = new Configurator('component');
        $config = $this->setConfugParams($config, $input);

        $resValidate = $config->validate();
        if (is_array($resValidate)) {
            $this->printArray($resValidate, 'Обнаружены ошибки в параметрах:', $output);
        }

        // подтверждение пользователя
        if ($this->approveCreating($input, $output, $config)) {
            // TODO: передать creatoru конфиг

        } else {
            $output->writeln('Отмена создания компонента');
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
            $output->writeln($title);
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
        $output->writeln('==== Все ли настройки указаны верно');
        $this->printArray($arInfo, null, $output);

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Всё верно? Продолжить создание компонента? [y/n]', false);
        $result = $helper->ask($input, $output, $question);

        return $result;
    }



    /**
     * Задает параметры конфигуратору из $input
     * @param IConfigurator  $config - объект конфигуратора
     * @param InputInterface $input - объект ввода консоли
     * @return IConfigurator - объект конфигуратора с заданными настройками
     */
    private function setConfugParams(IConfigurator $config, InputInterface $input)
    {
        return $config
            ->setName($input->getArgument('name'))
            ->setPath($this->launchDir . DIRECTORY_SEPARATOR . $input->getArgument('path'))
            ->setNamespace($input->getArgument('namespace'))
            ->setCreateLang($input->getOption('langfile'))
            ->setCreateParams($input->getOption('parameters'))
            ->setComplexFiles($input->getOption('complex_files'))
            ->setCreateDescr($input->getOption('description'))
            ->setType($input->getOption('type'));
    }

}