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

namespace CsnSessionInDB\Session\SaveHandler;

use Zend\Session\SaveHandler\SaveHandlerInterface;

use CsnSessionInDB\Entity\Session;

/**
 * DoctrineDB session save handler
 */
class DoctrineDB implements SaveHandlerInterface
{
    
    /**
     * Session Name
     *
     * @var string
     */
    protected $sessionName;

    /**
     * Lifetime
     * @var int
     */
    protected $lifetime;

    /**
     * Doctrine Entity Manager
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * Service Locator
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * Constructor
     *
     * @param Service Locator
     */
    public function __construct($serviceLocator)
    {
        $this->entityManager = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $this->serviceLocator = $serviceLocator;
        
    }

    /**
     * Open Session
     *
     * @param  string $savePath
     * @param  string $name
     * @return bool
     */
    public function open($savePath, $name)
    {
        // Note: session save path is not used

        $this->sessionName     = $name;

        if(array_key_exists('gc_maxlifetime', $this->serviceLocator->get('config')['session_config'])){
            $this->lifetime = $this->serviceLocator->get('config')['session_config']['gc_maxlifetime'];
        } else {
            $this->lifetime = ini_get('session.gc_maxlifetime');
        }

        return true;
    }

    /**
     * Close session
     *
     * @return bool
     */
    public function close()
    {

            return true;
    }

    /**
     * Read session data
     *
     * @param string $id
     * @return string
     */
    public function read($id)
    {
        //echo 'Read ID:';
        $query = $this->entityManager->createQuery( "SELECT s FROM CsnSessionInDB\Entity\Session s WHERE s.id = '$id' AND s.name = '$this->sessionName'" );
        $rows = $query->getResult( \Doctrine\ORM\Query::HYDRATE_OBJECT );

        if ( isset( $rows[ 0 ] ) ) {
            $row = $rows[ 0 ];
            $current_date = time();

            if($row->getModified() + $row->getLifetime() > $current_date){

                return $row->getData();
            }
            $this->destroy($id);
        }

        return '';
    }

    /**
     * Write session data
     *
     * @param string $id
     * @param string $data
     * @return bool
     */
    public function write($id, $data)
    {
        if(empty($data)) return false;

        $modified     = time();
        $data     = (string) $data;
        
        $query = $this->entityManager->createQuery( "SELECT s FROM CsnSessionInDB\Entity\Session s WHERE s.id = ?1 AND s.name = ?2" );
        $query->setParameters(array(
                '1' => $id,
                '2' => $this->sessionName,
            ));
        $sess = $query->getOneOrNullResult();

        if ( isset( $sess ) ) {
            
            //echo 'UPDATE';
            $query = $this->entityManager->createQuery( "UPDATE CsnSessionInDB\Entity\Session s SET s.modified = ?1, s.data = ?2 WHERE s.id = ?3 AND s.name = ?4" );
            $query->setParameters(array(
                '1' => $modified,
                '2' => $data,
                '3' => $id,
                '4' => $this->sessionName,
            ));
            $numUpdated = $query->execute();

            return (bool) $numUpdated;
        }

        $user = NULL;
        $authService     = $this->serviceLocator->get( 'Zend\Authentication\AuthenticationService' );
        if($authService->hasIdentity()) {
            $user = $authService->getIdentity();
            $container = new \Zend\Session\Container('Login');
            if($container->offsetGet('rememberme') == 1) {
                $this->lifetime = $this->serviceLocator->get('config')['session_config']['remember_me_seconds'];
            }
        }

        $session = new Session();
        $remoteAddress = new \Zend\Http\PhpEnvironment\RemoteAddress();
        $ip = $remoteAddress->getIpAddress();
        $httpUserAgent = new \Zend\Session\Validator\HttpUserAgent();
        $userAgent = $httpUserAgent->getData();

        // Delete all unused session from current ip and current browser (One Browser - One Session in DB)
        // Some user can delete session cookie and refresh browser constantly. This will create unknown number of sessions in DB.
        $query = $this->entityManager->createQuery( "DELETE FROM CsnSessionInDB\Entity\Session s WHERE s.ip = ?1 AND s.userAgent = ?2" );
        $query->setParameters(array(
                '1' => $ip,
                '2' => $userAgent,
            ));
        $numDeleted = $query->execute(); 
        // If nothing to delete ($numDeleted = 0) it's posible the attacker to executs following code (session to be hijacked). So we search DB for current $id
        if(!$numDeleted){
            //check if session is hijacked
            $query = $this->entityManager->createQuery( "SELECT s FROM CsnSessionInDB\Entity\Session s WHERE s.id = '$id' AND s.name = '$this->sessionName'" );
            $numSelected = $query->execute();
            if($numSelected) {
                // Account has been hacked
                echo 'HACKED';
                $authService->clearIdentity();
                $this->destroy($id);
                return false;
            }
        }
        $session->setId($id);
        $session->setIp($ip);
        $session->setUserAgent($userAgent);
        $session->setUser($user);
        $session->setName($this->sessionName);
        $session->setData($data);
        $session->setLifetime($this->lifetime);
        $this->entityManager->persist( $session );
        $this->entityManager->flush();

        return true;
    }

    /**
     * Destroy session
     *
     * @param  string $id
     * @return bool
     */
    public function destroy($id)
    {
        $query = $this->entityManager->createQuery( "DELETE FROM CsnSessionInDB\Entity\Session s WHERE s.id = '$id' AND s.name = '$this->sessionName'" );
        
        $numDeleted = $query->execute();
        
        return (bool) $numDeleted;
    }

    /**
     * Garbage Collection
     *
     * @param int $maxlifetime
     * @return true
     */
    public function gc($maxlifetime)
    {
        $current_date = time();
        //$old = $current_date - $maxlifetime;

        //$query = $this->entityManager->createQuery( "DELETE CsnSessionInDB\Entity\Session s WHERE s.modified < '$old'" );
        $query = $this->entityManager->createQuery( "DELETE FROM CsnSessionInDB\Entity\Session s WHERE s.modified < ($current_date-s.lifetime)" );

        $numDeleted = $query->execute();
        //echo $numDeleted;
        return (bool) $numDeleted;
    }
}
