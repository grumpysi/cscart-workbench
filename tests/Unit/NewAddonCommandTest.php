<?php

namespace Test\Unit;

use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Tests\DefaultTestCase;
use Symfony\Component\Console\Application;
use GrumpySi\Bench\Commands\NewAddonCommand;
use Symfony\Component\Console\Tester\CommandTester;

class NewAddonCommandTest extends DefaultTestCase
{
    /**
     * @var Filesystem
     */
    protected $fs;

    /**
     * @var CommandTester
     */
    private $commandTester;

    /**
     *
     */
    protected function setUp()
    {
        $application = new Application();
        $application->add(new NewAddonCommand());
        $command = $application->find('new');
        $this->commandTester = new CommandTester($command);

        $this->fs = new Filesystem();

        $this->fs->remove(dirname(__FILE__).'/../workbench');
    }

    /**
     *
     */
    protected function tearDown()
    {
        $this->commandTester = null;
    }

    /**
     * @test
     */
    public function must_provide_name_argument()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage(sprintf('Not enough arguments (missing: "%s")', 'name'));

        $this->commandTester->execute([]);

        $this->assertEquals('Name 1', trim($this->commandTester->getDisplay()));
    }

    /**
     * @test
     */
    public function must_be_in_a_valid_cscart_root_folder()
    {
        $this->commandTester->execute([
            'name' => 'TestAddon'
        ]);

        $this->assertContains('Cannot find a valid CS-Cart installation at ', trim($this->commandTester->getDisplay()));
    }

    /**
     * @test
     */
    public function displays_completion_message_to_user()
    {
        $this->commandTester->execute([
            'name' => 'TestAddon',
            '--skip-folder-test' => true
        ]);

        $this->assertEquals('Scaffolding new addon called TestAddon... OK', trim($this->commandTester->getDisplay()));
    }
    
}