<?php

namespace Anvi\BitrixCreator\Command;

use Anvi\BitrixCreator\IConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Anvi\BitrixCreator\Configurator;
use Anvi\BitrixCreator\CompConfigurator;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class CreateComponentCommand extends Command
{

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
        $output->writeln('===> Create Bitrix component');

        // установка параметров
        $config = new CompConfigurator([
            'name' => $input->getArgument('name'),
            'path' => $input->getArgument('path'),
        ]);

        if ($input->getOption('type') === CompConfigurator::COMPLEX_COMPONENT) {
            $config->setType(CompConfigurator::COMPLEX_COMPONENT);
        } else {
            $config->setType(CompConfigurator::SIMPLE_COMPONENT);
        }

        $config->setParam('namespace', $input->getOption('namespace'))
            ->setParam('createLang', $input->getOption('langfile'))
            ->setParam('createParams', $input->getOption('parameters'))
            ->setParam('createDescr', $input->getOption('description'));

        $arComplexFiles = $input->getOption('complex_files');
        if (!empty($arComplexFiles)) {
            $config->setParam('complexFiles', $arComplexFiles);
        }

        // подтверждение создания
        if (!$this->approveCreating($input, $output, $config)) {
            $output->writeln('ОТмена создания компонента');
        } else {
            $output->writeln('Создаем компонент');
            // TODO: передать creatoru конфиг
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

}