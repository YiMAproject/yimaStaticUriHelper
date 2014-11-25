<?php
namespace yimaStaticUriHelper\View\Helper;

use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class StaticUriHelper extends AbstractHelper implements
    ServiceLocatorAwareInterface
{
    /**
     * @var
     */
    protected $serviceLocator;

    /**
     * Class act as functor
     *
     */
    public function __invoke()
    {
        /** @var ServiceManager $sm */
        $sm = $this->getServiceLocator()
            ->getServiceLocator();

        $stServer = $sm->get('staticsUri');

        return call_user_func_array(array($stServer, '__invoke'), func_get_args());
    }

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }
}
