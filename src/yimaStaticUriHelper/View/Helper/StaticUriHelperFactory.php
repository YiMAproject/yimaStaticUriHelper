<?php
namespace yimaStaticUriHelper\View\Helper;

use yimaStaticUriHelper\StaticUri;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StaticUriHelperFactory implements FactoryInterface
{
    /**
     * Create Service, staticUri view helper
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return StaticUri
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        d_e($serviceLocator);
    }
}
