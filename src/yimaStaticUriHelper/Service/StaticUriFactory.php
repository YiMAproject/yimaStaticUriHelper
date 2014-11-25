<?php
namespace yimaStaticUriHelper\Service;

use yimaStaticUriHelper\StaticUri;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class StaticUriFactory implements FactoryInterface
{
    /**
     * Create Service, staticUri
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return StaticUri
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceManager $serviceLocator */
        $conf     = $serviceLocator->get('Config');
        $conf     = ( isset($conf['statics.uri']) && is_array($conf['statics.uri']) )
                  ? $conf['statics.uri']
                  : array();

        $stServer = new StaticUri($conf);

        return $stServer;
    }
}
