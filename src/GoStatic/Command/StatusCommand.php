<?php
/**
 * Created by PhpStorm.
 * User: aguidet
 * Date: 31/07/16
 * Time: 10:50 AM
 */

namespace GoStatic\Command;


use GoStatic\Cache;
use GoStatic\Configuration;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StatusCommand extends Command
{
    protected function configure()
    {
        $this->setName('go:status')
            ->setDescription('Show the actual status');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheSize = 0;
        foreach (glob(Configuration::CACHE_DIR.'/*'.Cache::EXTENTION) as $file) {
            $cacheSize += filesize($file);
        }
        $output->writeln("<info>Cache size: $cacheSize</info>");
    }
}