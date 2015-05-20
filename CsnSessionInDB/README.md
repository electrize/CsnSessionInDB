CsnSessionInDB
======
Zend Framework 2 Module

### What is CsnSessionInDB? ###
CsnSessionInDB is a module that stores sessions in the database based on `DoctrineORMModule`.

### What exactly does CsnSessionInDB do? ###
CsnSessionInDB has been created with educational purposes to demonstrate how saving session in a database can be done. It is fully functional, working in perfect harmony with *Doctrine* and the other Csn modules.

Installation
------------
1. Installation via composer is supported, simply run (make sure you've set `"minimum-stability": "dev"` in your *composer.json* file):
`php composer.phar require coolcsn/csn-session-in-db:dev-master`

2. Configure referenced module ([CsnUser](https://github.com/coolcsn/CsnUser) and follow the instructions.

3. Add 'CsnSessionInDB' to your application configuration in `config/application.config.php`. An example application configuration could look like the following:

```
'modules' => array(
    'Application',
    'DoctrineModule',
    'DoctrineORMModule',
    'CsnUser',
    'CsnSessionInDB',
)
```

4. Run `./vendor/bin/doctrine-module orm:schema-tool:update` to update the database schema (**Note:** You may need to force the update by adding ` --force` to the command).

Dependencies
------------
This Module depends on the following Module:

- [CsnUser](https://github.com/coolcsn/CsnUser) `Which depends on DoctrineORMModule`

Recommends
----------
- [coolcsn/CsnUser](https://github.com/coolcsn/CsnUser) - Authentication (login, registration) module.
- [coolcsn/CsnAuthorization](https://github.com/coolcsn/CsnAuthorization) - Authorization module.
- [coolcsn/CsnCms](https://github.com/coolcsn/CsnCms) - CMS module.

TODO's
----------
- onBootstrap error handling (try - cache)
- Find way to change cookie_lifetime more elegant