<?php
namespace yimaStaticUriHelper\View\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class StaticUriHelperFactory
 *
 * @package yimaStaticUriHelper\View\Helper
 */
class StaticUriHelperFactory implements FactoryInterface
{
    /**
     * Create Service, staticUri view helper
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed|StaticUri
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $serviceLocator \Zend\View\HelperPluginManager */
        $serviceManager = $serviceLocator->getServiceLocator();

        $conf     = $serviceManager->get('Config');
        $conf     = ( isset($conf['static_uri_helper']) && is_array($conf['static_uri_helper']) )
                  ? $conf['static_uri_helper']
                  : array();

        $stServer = new StaticUri($conf);

        return $stServer;
    }
}
