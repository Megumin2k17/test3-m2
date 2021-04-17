<?php 

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/', ['App\Controllers\SiteController', 'users']);
    $r->addRoute('GET', '/users', ['App\Controllers\SiteController', 'users']);

    $r->addRoute('GET', '/profile/{id:\d+}', ['App\Controllers\SiteController', 'profile']);


    $r->addRoute('GET', '/login', ['App\Controllers\SiteController', 'login']);
    $r->addRoute('POST', '/login', ['App\Controllers\AuthController', 'login']);
    $r->addRoute('GET', '/logout', ['App\Controllers\AuthController', 'logout']);


    $r->addRoute('GET', '/register', ['App\Controllers\SiteController', 'register']);
    $r->addRoute('POST', '/register', ['App\Controllers\AuthController', 'register']);

    $r->addRoute('GET', '/verification', ['App\Controllers\AuthController', 'email_verification']);


    $r->addRoute('GET', '/create', ['App\Controllers\UserController', 'createShow']);
    $r->addRoute('POST', '/create', ['App\Controllers\UserController', 'create']);

   
   	
   
   	$r->addRoute('GET', '/edit/{id:\d+}', ['App\Controllers\UserController', 'editShow']);
   	// $r->addRoute(['GET', 'POST'], '/update/{id:\d+}', ['App\Controllers\UserController', 'edit']);
   	$r->addRoute('POST', '/edit', ['App\Controllers\UserController', 'edit']);
   	// $r->addRoute('GET', '/update/{id:\d+}', ['App\Controllers\UserController', 'edit']);

   	$r->addRoute('GET', '/security/{id:\d+}', ['App\Controllers\UserController', 'securityShow']);
   	$r->addRoute('POST', '/security', ['App\Controllers\UserController', 'security']);

   	$r->addRoute('GET', '/media/{id:\d+}', ['App\Controllers\UserController', 'mediaShow']);
   	$r->addRoute('POST', '/media', ['App\Controllers\UserController', 'media']);

    $r->addRoute('GET', '/status/{id:\d+}', ['App\Controllers\UserController', 'statusShow']);  
    $r->addRoute('POST', '/status', ['App\Controllers\UserController', 'status']);

    $r->addRoute('GET', '/delete', ['App\Controllers\UserController', 'delete']);
   
    // {id} must be a number (\d+)
    // $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
    // The /{title} suffix is optional
    // $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo 'ERROR 404 PAGE NOT FOUND!';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // d($routeInfo[0]); die;
        echo 'ERROR 405 METHOD NOT ALLOWED!';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        if($_POST) {
        	$vars = $_POST;
        }
        // d($routeInfo[1]); die;
        // d($vars); die;
        $container->call($handler, $vars);

        // $handler[0] = new SiteController;
        // $builder = new \DI\ContainerBuilder();
		// $container = $builder->build();
        // $userManager = $container->get($handler[0]);
        
        // var_dump($handler); die;

        // $container->call($handler);

        // $handler[0] = new SiteController;

        // call_user_func($handler, $vars);
        // ... call $handler with $vars
        break;
}