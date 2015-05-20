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

namespace CsnSessionInDB\Service\Factory;
 
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use CsnSessionInDB\Session\SaveHandler\DoctrineDB;
 
class DoctrineDBFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $saveHandler = new DoctrineDB($serviceLocator);

        return $saveHandler;
    }
}