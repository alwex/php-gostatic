<?php
/**
 * Created by PhpStorm.
 * User: aguidet
 * Date: 31/07/16
 * Time: 10:37 AM
 */

namespace GoStatic\Command;


use GoStatic\Cache;
use GoStatic\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ClearCommand extends Command
{
    protected function configure()
    {
        $this->setName('go:clear')
            ->setDescription('Clear the actual cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (glob(Configuration::CACHE_DIR.'/*'.Cache::EXTENTION) as $file) {
            $output->writeln('delete: '.$file);
            unlink($file);
        }
        $output->writeln('<info>Cache cleared</info>');
    }
}