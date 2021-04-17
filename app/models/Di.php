<?php 

use DI\ContainerBuilder;
use League\Plates\Engine;
use Delight\Auth\Auth;
use Aura\SqlQuery\QueryFactory;
use App\Models\Permissions;
// use PDO;



$containerBuilder = new ContainerBuilder;

$containerBuilder->addDefinitions([

    Engine::class => function (PDO $db_connection) {
    	
    	$templates = new Engine('../app/views');
    	$auth = new Auth($db_connection);

    	$templates->addFolder('layout', '../app/views/layout');
    	$templates->addData(['auth' => new Auth($db_connection), 'permissions' => new Permissions($auth)]);

    	// d($templates); die;
    	// $templates->addData(['name' => 'Jonathan']);
    	return $templates;
    },

    PDO::class => function () {
    	$driver = 'mysql';
    	$host = 'localhost';
    	$dbname = 'contest32';
    	$username = 'mad';
    	$password = '';
    	return new PDO("$driver:host=$host; dbname=$dbname", $username, $password);
    },

    Auth::class => function (PDO $db_connection) {
    	return new Auth($db_connection, $ipAddress = null, $dbTablePrefix = null, $throttling = false);
    },

    QueryFactory::class => function () {
    	return new QueryFactory('mysql');
    }
]);


$container = $containerBuilder->build();
