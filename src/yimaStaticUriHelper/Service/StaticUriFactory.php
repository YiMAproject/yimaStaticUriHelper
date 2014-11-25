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

        // Set default variables
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $basePath  = $viewHelperManager->get('basepath');
        $serverUrl = $viewHelperManager->get('serverurl');

        ($stServer->getVariable($stServer::VAR_BASE_PATH))
            ?: $stServer->setVariable($stServer::VAR_BASE_PATH, $basePath());

        ($stServer->getVariable($stServer::VAR_SERVER_URL))
            ?: $stServer->setVariable($stServer::VAR_SERVER_URL, $serverUrl());

        return $stServer;
    }
}
