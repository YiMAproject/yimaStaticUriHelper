<?php
namespace yimaStaticUriHelper;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\InitProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\ServiceManager\ServiceManager;

class Module implements
    InitProviderInterface,
    ConfigProviderInterface,
    ViewHelperProviderInterface,
    AutoloaderProviderInterface
{
    /**
     * Initialize workflow
     *
     * @param ModuleManagerInterface $moduleModuleManager
     *
     * @return void
     */
    public function init(ModuleManagerInterface $moduleModuleManager)
    {
        /** @var $moduleModuleManager \Zend\ModuleManager\ModuleManager */

        /** @var \Zend\ModuleManager\ModuleEvent $event */
        $event = $moduleModuleManager->getEvent();
        /** @var ServiceManager $sm */
        $sm    = $event->getParam('ServiceManager');
        $sm->setFactory('staticsUri', __NAMESPACE__.'\Service\StaticUriFactory');
        $sm->setShared('staticsUri', false);
    }

    /**
     * Expected to return \Zend\ServiceManager\Config object or array to
     * seed such an object.
     *
     * @return array|\Zend\ServiceManager\Config
     */
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'staticsUri' => __NAMESPACE__.'\View\Helper\StaticUriHelper',
            ),
        );
    }

    /**
     * Returns configuration to merge with application configuration
     *
     * @return array|\Traversable
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    /**
     * Return an array for passing to Zend\Loader\AutoloaderFactory.
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ ,
                ),
            ),
        );
    }
}
