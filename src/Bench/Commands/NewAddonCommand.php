<?php

namespace GrumpySi\Bench\Commands;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GrumpySi\Bench\Commands\Traits\ValidateCartPathTrait;

class NewAddonCommand extends Command
{
    use ValidateCartPathTrait;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this
            ->setName('new')
            ->setDescription(
                'Create a new addon scaffold with company branding.'
            )
            ->addArgument('name',
                InputArgument::REQUIRED,
                'Add-on ID (name)'
            )
            ->addOption('skip-folder-test',
                's',
                InputOption::VALUE_OPTIONAL,
                'Skips the test to check if command is being executed in a valid CS-Cart root folder.',
                false
            );
    }
    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Setup
        $fs = new Filesystem();
        $addon_id = $input->getArgument('name');
        $abs_cart_path = rtrim(realpath(getcwd()), '\\/') . '/';
        $stubs_folder_path = dirname(__FILE__).'/../stubs';
        $workbench_folder_path = $abs_cart_path.'workbench';
        $addon_development_path = $workbench_folder_path.'/'.$addon_id;

        // Validations
        if (! $this->isValidAddonName($addon_id)) {
            $output->writeln('<fg=red;options=bold>'
                . 'Cannot create addon.' . PHP_EOL
                . 'Invalid addon name.</>'
            );

            return;
        }
        if ( $this->isInvalidCSCartPath($abs_cart_path, $input) ) {
            $output->writeln(sprintf(
                '<fg=red;options=bold>'
                . 'Cannot find a valid CS-Cart installation at "%s"' . PHP_EOL
                . 'Please make sure that you are in the root folder of a valid CS-Cart installation.</>',
                $abs_cart_path
            ));

            return;
        };

        // Create the addon entity - This should hold logic below.
        // $addon = new Addon($addon_id, $abs_cart_path);

        // Ensure workbench folder exists
        if (! $fs->exists($workbench_folder_path)) {
            $fs->mkdir($workbench_folder_path);
        }

        // Check new addon development folder does not exist
        if ($fs->exists($addon_development_path)) {
            $output->writeln(sprintf('<fg=red;options=bold>Cannot create addon, folder already exists for "%s"', $addon_id));

            return;
        }

        $output->write(sprintf('Scaffolding new addon called %s... ', $addon_id));

        // Clone stub addon into new addon development folder
        $fs->mirror($stubs_folder_path, $addon_development_path);

        // Rename folders with new addon name
        $fs->rename($addon_development_path.'/app/addons/pluginname', $addon_development_path.'/app/addons/'.$addon_id);
        $fs->rename($addon_development_path.'/design/backend/css/addons/pluginname', $addon_development_path.'/design/backend/css/addons/'.$addon_id);
        $fs->rename($addon_development_path.'/design/backend/media/images/addons/pluginname', $addon_development_path.'/design/backend/media/images/addons/'.$addon_id);
        $fs->rename($addon_development_path.'/design/backend/templates/addons/pluginname', $addon_development_path.'/design/backend/templates/addons/'.$addon_id);

        // Rename language file
        $fs->rename($addon_development_path.'/var/langs/en/addons/pluginname.po', $addon_development_path.'/var/langs/en/addons/'.$addon_id.'.po');

        // Replace 'pluginname' with actual addon name
        $this->replace_in_file($addon_development_path.'/app/addons/'.$addon_id.'/addon.xml', 'pluginname', $addon_id);
        $this->replace_in_file($addon_development_path.'/design/backend/css/addons/'.$addon_id.'/styles.css', 'pluginname', $addon_id);
        $this->replace_in_file($addon_development_path.'/design/backend/templates/addons/'.$addon_id.'/hooks/index/styles.post.tpl', 'pluginname', $addon_id);
        $this->replace_in_file($addon_development_path.'/var/langs/en/addons/'.$addon_id.'.po', 'pluginname', $addon_id);
        $this->replace_in_file($addon_development_path.'/README.md', 'pluginname', $addon_id);

        // Setup symlinks
        $fs->symlink($addon_development_path.'/app/addons/'.$addon_id, $abs_cart_path.'/app/addons/'.$addon_id);
        $fs->symlink($addon_development_path.'/design/backend/css/addons/'.$addon_id, $abs_cart_path.'/design/backend/css/addons/'.$addon_id);
        $fs->symlink($addon_development_path.'/design/backend/media/images/addons/'.$addon_id, $abs_cart_path.'/design/backend/media/images/addons/'.$addon_id);
        $fs->symlink($addon_development_path.'/design/backend/templates/addons/'.$addon_id, $abs_cart_path.'/design/backend/templates/addons/'.$addon_id);
        $fs->symlink($addon_development_path.'/var/langs/en/addons/'.$addon_id.'.po', $abs_cart_path.'/var/langs/en/addons/'.$addon_id.'.po');

        $output->writeln('<info>OK</info>');
    }

    /**
     * @param $str
     *
     * @return bool
     */
    protected function isValidAddonName($str) {
        return !preg_match('/[^A-Za-z0-9._#\\-$]/', $str);
    }

    /**
     * Replaces a string in a file
     *
     * @param string $FilePath
     * @param string $OldText text to be replaced
     * @param string $NewText new text
     * @return array $Result status (success | error) & message (file exist, file permissions)
     */
    protected function replace_in_file($FilePath, $OldText, $NewText)
    {
        $Result = array('status' => 'error', 'message' => '');
        if(file_exists($FilePath)===TRUE)
        {
            if(is_writeable($FilePath))
            {
                try
                {
                    $FileContent = file_get_contents($FilePath);
                    $FileContent = str_replace($OldText, $NewText, $FileContent);
                    if(file_put_contents($FilePath, $FileContent) > 0)
                    {
                        $Result["status"] = 'success';
                    }
                    else
                    {
                        $Result["message"] = 'Error while writing file';
                    }
                }
                catch(Exception $e)
                {
                    $Result["message"] = 'Error : '.$e;
                }
            }
            else
            {
                $Result["message"] = 'File '.$FilePath.' is not writable !';
            }
        }
        else
        {
            $Result["message"] = 'File '.$FilePath.' does not exist !';
        }
        return $Result;
    }
}