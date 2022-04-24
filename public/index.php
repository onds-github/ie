<?php

define('VERSION_CACHE', '?v=V200131.01');

define('APIURL', 'https://bosyojqfuzxoilgcetnh.supabase.co');
define('APIKEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImJvc3lvanFmdXp4b2lsZ2NldG5oIiwicm9sZSI6ImFub24iLCJpYXQiOjE2NDc5MDgzMTAsImV4cCI6MTk2MzQ4NDMxMH0.yoraOaQS1GDQ5mDif3G2sFWWA15Zd4taf-bn2RdW5xg');
define('AUTHORIZATION', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6ImJvc3lvanFmdXp4b2lsZ2NldG5oIiwicm9sZSI6InNlcnZpY2Vfcm9sZSIsImlhdCI6MTY0NzkwODMxMCwiZXhwIjoxOTYzNDg0MzEwfQ.ZDALC_ruT2C_22jp6HX_QXFOvCJEQF3Ghv-fspT3h_Q');

define('CREATE_MESSAGE_SUCCESS', 'O registro foi salvo!');
define('UPDATE_MESSAGE_SUCCESS', 'O registro foi salvo!');
define('DELETE_MESSAGE_SUCCESS', 'O registro foi excluído!');
define('CRUD_MESSAGE_ERROR', 'Ocorreu um erro, por favor, entre em contato com o suporte técnico!');

// Define path to application directory
defined('APPLICATION_PATH') || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library') . PATH_SEPARATOR .
    realpath(APPLICATION_PATH . '/models'),
    realpath(APPLICATION_PATH . '/modules/account/models'),
    realpath(APPLICATION_PATH . '/modules/project/models'),
    realpath(APPLICATION_PATH . '/modules/finance/models'),
    realpath(APPLICATION_PATH . '/modules/website/models'),
    realpath(APPLICATION_PATH . '/modules/admin/models'),
    realpath(APPLICATION_PATH . '/modules/marketing/models'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
        APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini'
);

//Connect DataBase
$config = new Zend_Config_Ini('application/configs/application.ini', 'database');
$default = Zend_Db::factory($config->resources->default->adapter, $config->resources->default->config->toArray());
$pagekit = Zend_Db::factory($config->resources->pagekit->adapter, $config->resources->pagekit->config->toArray());
Zend_Db_Table_Abstract::setDefaultAdapter($default);
Zend_Registry::set('default', $default);
Zend_Registry::set('pagekit', $pagekit);

$application->bootstrap()
        ->run();
