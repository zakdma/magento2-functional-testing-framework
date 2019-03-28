<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types = 1);

namespace Magento\FunctionalTestingFramework\Console;

use Magento\FunctionalTestingFramework\Config\MftfApplicationConfig;
use Magento\FunctionalTestingFramework\Exceptions\TestFrameworkException;
use Magento\FunctionalTestingFramework\Suite\SuiteGenerator;
use Magento\FunctionalTestingFramework\Test\Handlers\ActionGroupObjectHandler;
use Magento\FunctionalTestingFramework\Util\Manifest\ParallelTestManifest;
use Magento\FunctionalTestingFramework\Util\Manifest\TestManifestFactory;
use Magento\FunctionalTestingFramework\Util\TestGenerator;
use Magento\FunctionalTestingFramework\Util\DocGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateDocsCommand extends Command
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('generate:docs')
            ->setDescription('This command generates documentation for created MFTF files.')
            ->addOption(
                "output",
                'o',
                InputOption::VALUE_REQUIRED,
                'Output Directory'
            )->addOption(
                "clean",
                'c',
                InputOption::VALUE_NONE,
                'Clean Output Directory'
            );
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return void
     * @throws TestFrameworkException
     * @throws \Magento\FunctionalTestingFramework\Exceptions\TestReferenceException
     * @throws \Magento\FunctionalTestingFramework\Exceptions\XmlException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $input->getOption('output');
        $clean = $input->getOption('clean');

        $verbose = $output->isVerbose();

        MftfApplicationConfig::create(
            true,
            MftfApplicationConfig::GENERATION_PHASE,
            false,
            false
        );

        // create our manifest file here
        $testManifest = TestManifestFactory::makeManifest($config, []);
        TestGenerator::getInstance(null, []);
        if (empty($tests)) {
            SuiteGenerator::getInstance();
        }

        $allActionGroups = ActionGroupObjectHandler::getInstance()->getAllObjects();

        DocGenerator::getInstance()->createDocumentation($allActionGroups, $config, $clean);

        $output->writeln("Generate Docs Command Run");
    }
}