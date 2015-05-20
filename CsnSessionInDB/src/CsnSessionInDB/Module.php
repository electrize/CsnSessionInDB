<?php

/**
 * CsnSessionInDB - Coolcsn Zend Framework 2 Saving Session in a DB Module
 *
 * @link https://github.com/coolcsn/CsnSessionInDB for the canonical source repository
 * @copyright Copyright (c) 2005-2014 LightSoft 2005 Ltd. Bulgaria
 * @license https://github.com/coolcsn/CsnSessionInDB/blob/master/LICENSE BSDLicense
 * @author Stoyan Cheresharov <stoyan@coolcsn.com>
 * @author Nikola Vasilev <niko7vasilev@gmail.com>
 * @author Svetoslav Chonkov <svetoslav.chonkov@gmail.com>
 */
namespace CsnSessionInDB;

use Zend\Session\Config\SessionConfig;
use Zend\Session\SessionManager;
use Zend\Session\Container;
use Zend\Session\Validator\HttpUserAgent;
use Zend\Session\Validator\RemoteAddr;

class Module
{
    public function onBootstrap($event)
    {
        //$start = microtime(true);
        if ($event->getRequest() instanceof \Zend\Http\Request) {
            $config = $event->getApplication()->getServiceManager()->get('Configuration');

            $sessionConfig = new SessionConfig();
            $sessionConfig->setOptions($config['session_config']);
            $saveHandler = $event->getApplication()->getServiceManager()->get('doctrine_db_save_handler');

            $sessionManager = $event->getApplication()->getServiceManager()->get('Zend\Session\SessionManager');
            $sessionManager->setConfig($sessionConfig);
            $sessionManager->setSaveHandler($saveHandler);

            // http://framework.zend.com/manual/2.1/en/modules/zend.session.validator.html NOT WORKS
            //$sessionManager->getValidatorChain()->attach('session.validate', array(new HttpUserAgent($_SERVER['HTTP_USER_AGENT']), 'isValid'));
            //$sessionManager->getValidatorChain()->attach('session.validate', array(new RemoteAddr(), 'isValid'));

            $sessionManager->start();
            Container::setDefaultManager($sessionManager);
            
            // change session id on every refresh
            // ??
        }
        /*$end = microtime(true);
        print "<br><br><br><br>CsnSessionInDB generated in ".round(($end - $start), 3)." seconds";*/
    }

    public function getConfig()
    {
        return include __DIR__.'/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__.'/../../src/'.__NAMESPACE__,
                ),
            ),
        );
    }
}
