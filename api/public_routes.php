<?php

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

$showsSummaryByCategoryRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_DIR;
    $sql = "SELECT `show`.id, `show`.title, `show`.date,
            CONCAT(city.name, ', ', country.name) AS location,
            CONCAT('$imgPath', `show`.id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imageAlt
        FROM `show`
        JOIN venue ON venue.id = `show`.venue_id
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        LEFT JOIN image ON image.subject_id = `show`.id AND image.subject = 'show' AND image.type = 'horizontal'
        WHERE `show`.enabled=1
            AND `show`.show_category_id = :category";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':category', $args['category_id'], PDO::PARAM_STR);

    return query($response, $q, 'shows');
};

$showCategoriesRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT * FROM `show_category`";
    $q = $this->get('db')->prepare($sql);
    return query($response, $q, 'show_categories');
};

$showDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_DIR;

    // Fetch Show
    $sql = "SELECT `show`.id, `show`.title, `show`.date,
            CONCAT(venue.name, ', ', city.name, ', ', country.name) AS location,
            `show`.description
        FROM `show`
        JOIN venue ON venue.id = `show`.venue_id
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        WHERE `show`.id = :show_id";
    
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':show_id', $args['id'], PDO::PARAM_STR);
    
    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $show = $q->fetch();
    if (!$show) {
        return $response->withStatus(404);
    }

    // Fetch Images
    $sql = "SELECT `type`, altText AS alt,
            CONCAT('$imgPath', :show_id, '/', image.type, '/', image.name) AS url
        FROM image
        WHERE subject_id = :show_id";
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':show_id', $args['id'], PDO::PARAM_STR);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $images = $q->fetchAll();

    // Fetch TicketTypes
    $sql = "SELECT id, name, description, price
        FROM ticket_type
        WHERE show_id = :show_id";
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':show_id', $args['id'], PDO::PARAM_STR);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $ticketTypes = $q->fetchAll();

    $show['images'] = $images;
    $show['ticketTypes'] = $ticketTypes;

    return jsonResponse($response, 'show', $show);
};
