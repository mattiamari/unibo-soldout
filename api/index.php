<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

$app = AppFactory::create();
$app->setBasePath('/api');

try {
    $db = new PDO("mysql:dbname=soldout;host=db", "soldout", "soldout");
} catch (PDOException $e) {
    die("Can't connect to db");
}

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/shows', function (Request $request, Response $response, $args) use ($db) {
    $sql = "select * from `show` where enabled=0";
    $res = $db->query($sql)->fetchAll();
    $json = json_encode($res);
    $response->getBody()->write($json);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();