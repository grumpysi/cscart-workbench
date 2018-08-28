<?php

namespace GrumpySi\Bench\Commands\Traits;

use Symfony\Component\Console\Input\InputInterface;

trait ValidateCartPathTrait
{
    /**
     * @param $abs_cart_path
     *
     * @param InputInterface $input
     *
     * @return bool
     */
    protected function isInvalidCSCartPath($abs_cart_path, InputInterface $input)
    {
        if ($input->getOption('skip-folder-test') == true) {
            return false;
        }

        return ((!file_exists($abs_cart_path . 'config.php') || !file_exists($abs_cart_path . 'config.local.php')));
    }
}