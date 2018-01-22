<?php declare(strict_types=1);

namespace Fazland\DtoManagementBundle\Tests\Fixtures\Proxy;

use Fazland\DtoManagementBundle\DtoManagementBundle;
use Fazland\DtoManagementBundle\Tests\Fixtures\TestKernel;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends TestKernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles(): array
    {
        return [
            new FrameworkBundle(),
            new SensioFrameworkExtraBundle(),
            new DtoManagementBundle(),
            new SecurityBundle(),
            new AppBundle(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__.'/config.yml');
    }
}