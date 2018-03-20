<?php

namespace anvein\bx_creator\command;

use anvein\bx_creator\configurator\ModuleConfigurator;
use anvein\bx_creator\creator\CreatorModule;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use anvein\bx_creator\tools\Helper;
use anvein\bx_creator\tools\Color;
use Exception;
use DateTime;

class CreateModuleCommand extends CommandBase
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('bxcreator:create_mod')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'Название (код) модуля [bxcreator]'
            )
            ->addArgument(
                'vendor',
                InputArgument::REQUIRED,
                'Производитель модуля [anvein]'
            )
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Путь где нужно создать модуль'
            )
            ->addOption(
                'ver',
                null,
                InputOption::VALUE_OPTIONAL,
                'Версия модуля',
                '0.0.1'
            )
            ->addOption(
                'ver_date',
                null,
                InputOption::VALUE_OPTIONAL,
                'Дата версии модуля [Y-m-d H:i:s]',
                (new DateTime())->format('Y-m-d') . ' 18:00:00'
            )
            ->addOption(
                'title',
                null,
                InputOption::VALUE_REQUIRED,
                'Название модуля'
            )
            ->addOption(
                'descr',
                null,
                InputOption::VALUE_REQUIRED,
                'Описание модуля'
            )
            ->addOption(
                'vendor_uri',
                null,
                InputOption::VALUE_OPTIONAL,
                'Ссылка на сайт производителя',
                '/'
            )
            ->addOption(
                'add_langs',
                null,
                InputOption::VALUE_OPTIONAL,
                'Дополнительные языки для которых должен быть задан перевод [en,us]'
            )
            ->addOption(
                'auto_translate',
                null,
                InputOption::VALUE_NONE,
                'Сделать автоматический перевод на дополнительные языки'
            )
            ->setDescription('Генерация скелета модуля битрикса')
            ->setHelp('Генерация скелета модуля битрикса');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $output->writeln(Color::col('===> Create skeleton of Bitrix module ', 'g'));
        $config = new ModuleConfigurator('модуль');
        $config = $this->setConfigParams($config);

        if (!$config->validate()) {
            $this->printArray($config->getErrors(), Color::col('Обнаружены ошибки в параметрах:', 'r'));
            $output->writeln(Color::col('Отмена создания модуля', 'r'));

            return;
        }

        if (!$this->approveCreating($config)) {
            $output->writeln(Color::col('Отмена создания модуля', 'r'));

            return;
        }

        $creator = new CreatorModule($config);
        $creator->run();

        if ($creator->isSuccess()) {
            $output->writeln(Color::col('Модуль успешно создан', 'g'));
        } else {
            $this->printArray(
                $creator->getErrors(),
                Color::col('При создании модуля возникли ошибки:', 'r')
            );
            $output->writeln(Color::col('Модуль не создан', 'r'));
        }
    }

    /**
     * Задает параметры конфигуратору из $input.
     *
     * @param ModuleConfigurator $config - объект конфигуратора
     *
     * @return ModuleConfigurator - объект конфигуратора с заданными настройками
     */
    protected function setConfigParams(ModuleConfigurator $configurator)
    {
        $configurator
            ->setName($this->input->getArgument('name'))
            ->setPath($this->input->getArgument('path'))
            ->setVendor($this->input->getArgument('vendor'))
            ->setVersion($this->input->getOption('ver'))
            ->setVersionDate($this->input->getOption('ver_date'))
            ->setTitle($this->input->getOption('title'))
            ->setDescription($this->input->getOption('descr'))
            ->setVendorUri($this->input->getOption('vendor_uri'))
            ->setAutoTranslate($this->input->getOption('auto_translate'));

        $addLangs = $this->input->getOption('add_langs');
        if (!empty($addLangs)) {
            $configurator->setAdditionalLangs(Helper::convertStringToArray($addLangs));

        }

        return $configurator;
    }

}
