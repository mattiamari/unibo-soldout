<?php

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

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
    if (!$request->hasHeader('Authorization')) {
        $response = new Response();
        return $response->withHeader('WWW-Authenticate', 'Basic')->withStatus(401);
    }

    $auth = $request->getHeader('Authorization');

    try {
        $auth = explode(' ', $auth[0]); // Split "Basic" from the actual base64 key
        $auth = base64_decode($auth[1]);
        
        // Basic auth. $auth[0] = user_id, $auth[1] = api_key
        $auth = explode(':', $auth);
    } catch (Exception $e) {
        $response = new Response();
        return $response->withHeader('WWW-Authenticate', 'Basic')->withStatus(401);
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
        return $response->withHeader('WWW-Authenticate', 'Basic')->withStatus(401);
    }

    $request = $request->withAttribute('user_id', $auth[0]);
    $response = $handler->handle($request);

    return $response;
};
