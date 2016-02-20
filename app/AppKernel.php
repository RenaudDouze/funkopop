<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * App kernel
 */
class AppKernel extends Kernel
{
    /**
     * Construct
     *
     * @param string $environment The environment
     * @param bool   $debug       Whether to enable debugging or not
     */
    public function __construct($environment, $debug)
    {
        parent::__construct($environment, $debug);

        // get rid of Warning: date_default_timezone_get(): It is not safe to rely on the system's timezone
        date_default_timezone_set('Europe/Paris');
    }

    /**
     * RegisterBundles
     *
     * @return array
     */
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),

            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),

            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),

            new Gregwar\ImageBundle\GregwarImageBundle(),

            new AppBundle\AppBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    /**
     * GetRootDir
     *
     * @return string
     */
    public function getRootDir()
    {
        return __DIR__;
    }

    /**
     * GetCacheDir
     *
     * @return string
     */
    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    /**
     * GetLogDir
     *
     * @return string
     */
    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    /**
     * RegisterContainerConfiguration
     *
     * @param  LoaderInterface $loader
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
