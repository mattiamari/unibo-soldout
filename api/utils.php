<?php

use Psr\Http\Message\ResponseInterface as ResponseInterface;

function error($msg) {
    return json_encode(['error' => $msg]);
}

function jsonResponse(ResponseInterface $response, $rootName, $data) {
    $response->getBody()->write(json_encode(
        [$rootName => $data],
        JSON_NUMERIC_CHECK | JSON_PRESERVE_ZERO_FRACTION
    ));
    return $response;
}

function query(ResponseInterface $response, PDOStatement $query, $rootName) {
    if (!$query->execute()) {
        $response->getBody()->write(error($query->errorInfo()[2]));
        return $response->withStatus(500);
    }

    return jsonResponse($response, $rootName, $query->fetchAll(PDO::FETCH_ASSOC));
}

function imageUrl($showId, $type, $name) {
    return str_replace('/', DIRECTORY_SEPARATOR, "/i/$showId/$type/$name");
}
