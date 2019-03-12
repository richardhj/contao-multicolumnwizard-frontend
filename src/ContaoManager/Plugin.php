<?php

namespace Richardhj\ContaoMultiColumnWizardFrontendBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use MenAtWork\MultiColumnWizardBundle\MultiColumnWizardBundle;
use Richardhj\ContaoMultiColumnWizardFrontendBundle\RichardhjContaoMultiColumnWizardFrontendBundle;

class Plugin implements BundlePluginInterface
{

    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(RichardhjContaoMultiColumnWizardFrontendBundle::class)
                ->setLoadAfter(
                    [
                        ContaoCoreBundle::class,
                        MultiColumnWizardBundle::class
                    ]
                )
        ];
    }
}
