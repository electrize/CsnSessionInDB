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

namespace CsnSessionInDB\Entity;

use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;


/**
 * Session
 *
 * @ORM\Table(name="session")
 * @ORM\Entity
 */
class Session
{

    /**
     * @var string
     *
     * @ORM\Column(name="id", type="string", length=60, unique = true)
     * @ORM\Id
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="lifetime", type="integer", nullable=false)
     */
    protected $lifetime;

    /**
     * @var string
     *
     * @ORM\Column(name="modified", type="integer", nullable=false)
     */
    protected $modified;

    /**
     * @var CsnUser\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="CsnUser\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    protected $user;

    /**
     * @var string
     *
     * @ORM\Column(name="ip", type="string", length=17, nullable=true)
     */
    protected $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="text", nullable=true)
     */
    protected $userAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=60, nullable=false)
     */
    protected $name;

    /**
     * 
     * @var string
     *
     * @ORM\Column(name="data", type="text", nullable=false)
     */
    protected $data;


    public function __construct()
    {
        $this->modified = time();
    }
	
    /**
     * Get Id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Id
     *
     * @param  string $id
     * @return Session
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Set user
     *
     * @param  CsnUser\Entity\User   $user
     * @return Session
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return ORM\ManyToMany\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get Name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set Name
     *
     * @param  string  $name
     * @return Session
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get Ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set Ip
     *
     * @param  string  $ip
     * @return Session
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get UserAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set UserAgent
     *
     * @param  string  $userAgent
     * @return Session
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get Modified
     *
     * @return int
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set Modified
     *
     * @param  int  $modified
     * @return Session
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get Lifetime
     *
     * @return int
     */
    public function getLifetime()
    {
        return $this->lifetime;
    }

    /**
     * Set Lifetime
     *
     * @param  int  $lifetime
     * @return Session
     */
    public function setLifetime($lifetime)
    {
        $this->lifetime = $lifetime;

        return $this;
    }

    /**
     * Get Data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set Data
     *
     * @param  string $data
     * @return Session
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
	
}
