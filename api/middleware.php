<?php

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once 'auth.php';

$corsMiddleware = function (Request $request, RequestHandler $handler) {
    $response = $handler->handle($request);

    return $response
        ->withHeader('Access-Control-Allow-Origin', 'http://mysite')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
};

$jsonHeaderMiddleware = function (Request $request, RequestHandler $handler) {
    $response = $handler->handle($request);

    if ($response->hasHeader('Content-Type')) {
        return $response;
    }

    return $response->withHeader('Content-Type', 'application/json');
};

$authMiddleware = function (Request $request, RequestHandler $handler) {
    if (!$request->hasHeader('x-auth')) {
        $response = new Response();
        return $response->withStatus(401);
    }

    try {
        $auth = parseAuthHeader($request->getHeader('x-auth')[0]);
    } catch (Exception $e) {
        $response = new Response();
        return $response->withStatus(401);
    }
    
    $sql = "SELECT COUNT(*) FROM api_key
        WHERE `user_id` = :user_id
            AND api_key = :api_key
            AND NOW() < expiry";
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':user_id', $auth[0]);
    $q->bindValue(':api_key', $auth[1]);

    if (!$q->execute()) {
        $response = new Response();
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    if (!$q->fetchColumn()) {
        $response = new Response();
        return $response->withStatus(401);
    }

    $request = $request->withAttribute('user_id', $auth[0]);
    $response = $handler->handle($request);

    return $response;
};
