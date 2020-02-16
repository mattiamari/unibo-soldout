<?php

use DI\Container;
use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/middleware.php';
require_once __DIR__ . '/public_routes.php';
require_once __DIR__ . '/user_routes.php';

const COVER_IMAGE_TYPE = 'horizontal';
const IMAGE_DIR = '/app/i/';


$container = new Container();

AppFactory::setContainer($container);
$app = AppFactory::create();
$app->setBasePath('/api');
$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();

$app->add($corsMiddleware);
$app->add($jsonHeaderMiddleware);
$app->addErrorMiddleware(true, true, true);

// Add db connection to DI container
$container->set('db', function () {
    $pdo = new PDO("mysql:dbname=soldout;host=db", "soldout", "soldout");
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
});

// Routes
$app->get('/', function (Request $request, ResponseInterface $response, $args) {
    $response->getBody()->write(
"Hi!

 /\     /\
{  `---'  }
{  O   O  }
~~>  V  <~~
 \  \|/  /
  `-----'__
  /     \  `^\_
 {       }\ |\_\_
 |  \_/  |/ /  \_\_( )
  \__/  /(_/     \__/
    (__/
");
    return $response->withHeader('Content-Type', 'text/plain');
});

$app->options('/{routes:.+}', function ($request, $response, $args) {
    return $response;
});

$app->get('/shows/{category_id}', $showsSummaryByCategoryRoute);
$app->get('/show-categories', $showCategoriesRoute);
$app->get('/show/{id}', $showDetailsRoute);

$app->get('/account', $accountDetailsRoute)
    ->add($authMiddleware);

$app->get('/orders', $ordersRoute)
    ->add($authMiddleware);

$app->get('/order/{order_id}', $orderDetailsRoute)
    ->add($authMiddleware);

$app->get('/notifications', $notificationsRoute)
    ->add($authMiddleware);

$app->post('/login', $loginRoute);
$app->post('/signup', $signupRoute);

$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
    throw new HttpNotFoundException($request);
});

$app->run();
