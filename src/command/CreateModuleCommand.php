<?php

namespace anvein\bx_creator\command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Exception;

class CreateModuleCommand extends Command
{
    /**
     * Папка из которой происходит запуск скрипта.
     *
     * @var null
     */
    protected $launchDir = null;

    /**
     * CreateModuleCommand constructor.
     *
     * @param string $launchDir - путь, откуда будет запущен скрипт
     *
     * @throws Exception - если путь $launchDir не существует или не указан
     */
    public function __construct($launchDir)
    {
        if (empty($launchDir)) {
            throw new \Exception('Не указан обязательный аргумент $launchDir');
        } elseif (!is_dir($launchDir)) {
            throw new \Exception("Указанный путь {$launchDir} не существует");
        }

        $this->launchDir = realpath($launchDir);

        parent::__construct();
    }

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
                'Название модуля [anvein.pipedrive]'
            )
            ->addArgument(
                'path',
                InputArgument::REQUIRED,
                'Путь где нужно создать компонент'
            )
            ->setDescription('Создание структуты модуля битрикса')
            ->setHelp('Создание структуты модуля битрикса');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
