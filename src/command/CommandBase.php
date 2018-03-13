<?php

namespace anvein\bx_creator\command;

use Exception;
use anvein\bx_creator\tools\Color;
use anvein\bx_creator\configurator\IConfigurator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Class CommandBase
 * Базовый класс для консольных команд
 * @package anvein\bxcreator\command
 */
class CommandBase extends Command
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
            throw new \Exception("Указанный путь {$launchDir} не существует или не является дирректорией");
        }

        $this->launchDir =  realpath($launchDir);

        parent::__construct();
    }

    /**
     * Выводит массив в консоль
     * @param array           $arInfo - массив, который надо вывести
     * @param string          $title - залоговок
     */
    protected function printArray(array $arInfo = [], $title = '')
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
    protected function approveCreating(IConfigurator $config)
    {
        $arInfo = $config->getInfo();
        $this->output->writeln(Color::col("==== Проверьте указанные параметры", 'y'));
        $this->printArray($arInfo, null);

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            Color::col("Всё верно? Продолжить создвать {$config->getTitle()}? [y/n]", 'y'),
            false
        );
        $result = $helper->ask($this->input, $this->output, $question);

        return $result;
    }
}