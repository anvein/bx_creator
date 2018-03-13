<?php

namespace anvein\bx_creator\command;

use anvein\bx_creator\Application;
use anvein\bx_creator\creator\SimpleCompCreator;
use anvein\bx_creator\tools\Color;
use anvein\bx_creator\configurator\IConfigurator;
use anvein\bx_creator\configurator\CompConfigurator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class CreateComponentCommand extends CommandBase
{

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

        $config = new CompConfigurator('компонент');
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
     * Задает параметры конфигуратору из $input
     * @param IConfigurator  $config - объект конфигуратора
     * @return IConfigurator - объект конфигуратора с заданными настройками
     */
    private function setConfigParams(IConfigurator $config)
    {
        return $config
            ->setName($this->input->getArgument('name'))
            ->setPath($this->launchDir . '/' . $this->input->getArgument('path'))
            ->setNamespace($this->input->getOption('namespace'))
            ->setCreateLang((bool)$this->input->getOption('lang'))
            ->setCreateParams($this->input->getOption('params'))
            ->setCreateDescr($this->input->getOption('descr'))
            ->setType($this->input->getOption('type'));
    }

}