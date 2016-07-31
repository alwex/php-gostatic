<?php

namespace GoStatic\Command;

use GoStatic\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Created by PhpStorm.
 * User: aguidet
 * Date: 30/07/16
 * Time: 2:28 PM
 */
class InitCommand extends Command
{

    protected function configure()
    {
        $this->setName('go:init')
            ->setDescription('Initialise the GoStatic configuration (see [gostatic_dir]/.gostatic/)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var $questions QuestionHelper */
        $questions = $this->getHelperSet()->get('question');

        $cacheTimeQuestion = new Question("Please enter the TTL <info>(default:5)</info>: ", "5");
        $cacheTime = $questions->ask($input, $output, $cacheTimeQuestion);

        $params = array(
            Configuration::KEY_CACHE => array(
                Configuration::KEY_TTL => $cacheTime,
                Configuration::KEY_EXCLUDE => array(
                ),
                //Configuration::KEY_ONLY => array(
                //)
            ),
        );

        $configuration = Configuration::create($params);

        $output->writeln("GoStatic initialized in:");
        $output->writeln("<info>".$configuration->getConfigurationDirectory()."</info>");
        $output->writeln("<info>".$configuration->getCacheDirectory()."</info>");
    }

}
