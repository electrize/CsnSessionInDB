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

return array(
    // https://github.com/zendframework/zf2/blob/master/library/Zend/Session/Config/SessionConfig.php
    // http://php.net/manual/en/session.configuration.php
    'session_config' => array(
        //'save_path' => dir,                   // session.save_path        (default - "") {The path where the session files are created}
        'name' => "SESSID",                  // session.name             (default - 'PHPSESSID') {Name of the session (used as cookie name).}
        'gc_probability' => 1,                  // session.gc_probability   (default - 1) {1/100 ('gc_probability'/'gc_divisor') means there is a 1% chance that the GC process starts on each request.}
        'gc_divisor' => 100,                      // session.gc_divisor       (default - 100)
        'gc_maxlifetime' => 1440,               // session.gc_maxlifetime   (default - 1440) {After this number of seconds, stored data in DB will be seen as 'garbage'}
        'cookie_lifetime' => 0,                 // session.cookie_lifetime  (default - 0) {Lifetime in seconds of cookie or, if 0, until browser is restarted.}
        'use_only_cookies' => true,             // {Specifies that only cookies are used and not session ids in URLs.}
        'remember_me_seconds' => 14*24*60*60,   // remember_me_seconds      ( > 0 ). {This option changes cookie_lifetime and lifetime in DB}
        //'cookie_path' => > string,            // session.cookie_path      (default - '/') {The path for which the cookie is valid.}
        //'cookie_domain' => > string,          // session.cookie_domain    (default - "") {The domain for which the cookie is valid.}
        //'cookie_secure' => > bool,            // session.cookie_secure    (default - "") {If and only if we use HTTPS.}
        'cookie_httponly' => true,              // session.cookie_httponly  (default - true) {Marks the cookie as accessible only through the HTTP protocol.Helps reduce identity theft through XSS attacks.}
        'use_cookies' => true,                  // session.use_cookies      (default - true) {Whether to use cookies. Prevents session fixation.}
        //'entropy_file' => file,               // session.entropy_file     (default - ?) {Specified here to create the session id.}
        //'entropy_length' => (> 0),            // session.entropy_length   (default - ?) {How many bytes to read from the file.}
        //'cache_expire' => (> 0),              // session.cache_expire     (default - 180) {Document expires after n minutes.}
        //'hash_bits_per_character' => numeric, // session.hash_bits_per_character The possible values are '4' (0-9, a-f), '5' (0-9, a-v), and '6' (0-9, a-z, A-Z, "-", ",").
    ),
    'service_manager' => array (
        'factories' => array(
            'doctrine_db_save_handler' => 'CsnSessionInDB\Service\Factory\DoctrineDBFactory',
            'Zend\Session\SessionManager' => 'Zend\Session\Service\SessionManagerFactory',
            'Zend\Authentication\AuthenticationService' => 'CsnUser\Service\Factory\AuthenticationFactory',
        ),

    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver',
                )
            )
        )
    ),
);
