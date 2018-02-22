<?php

namespace Anvi\BitrixCreator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Anvi\BitrixCreator\Configurator;
use Anvi\BitrixCreator\ComponentConfigurator;
use Symfony\Component\Console\Question\ConfirmationQuestion;


class CreateSimpleComponentCommand extends Command
{

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('bxcreator:create_simcomp')
            ->addOption(
                'name',
                null,
                InputArgument::REQUIRED,
                'Название компонента [news.list]'
            )
            ->addOption(
                'path',
                null,
                InputArgument::REQUIRED,
                'Путь где нужно создать компонент'
            )
            ->addOption(
                'type',
                null,
                InputOption::VALUE_REQUIRED,
                'Тип компонента [simple*/complex]',
                ComponentConfigurator::SIMPLE_COMPONENT
            )
            ->addOption(
                'namespace',
                null,
                InputOption::VALUE_OPTIONAL,
                'Пространство имен в котором создать компонент (namespace)'
            )
            ->addOption(
                'langfile',
                'l',
                InputOption::VALUE_NONE,
                'Создавать ли lang файлы'
            )
            ->addOption(
                'parameters',
                'p',
                InputOption::VALUE_NONE,
                'Создавать ли файл .parameters.php'
            )
            ->setDescription('Создание модуля битрикса')
            ->setHelp('Создание модуля битрикса');
    }

    /**
     * @inheritdoc
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('===> Create Bitrix simple component');

        $configurator = new ComponentConfigurator();
        $configurator->setName($input->getArgument('name'));
        $configurator->setPath($input->getArgument('path'));




        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion('Что делать?', false);
        $res = $helper->ask($input, $output, $question);

        echo '<pre>';
        var_dump($res);
        echo '</pre>';
        die();

        if (!$helper->ask($input, $output, $question)) {
            return;
        }





        if ($input->getOption('langfile')) {
            $configurator->setParam('langfile', $input->getOption('langfile'));
        }



//        if (!empty($input->getArgument('langfile'))) {
//            $configurator->setParam('langfile', $input->getOption('langfile'));
//        }
//
//        if (!empty($input->getArgument('langfile'))) {
//            $configurator->setParam('langfile', $input->getOption('langfile'));
//        }



        // TODO: Передать конструктору и profit - дальше вся движуха в конструкторе
    }
}