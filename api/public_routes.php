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

$showsSummaryCategorizedRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_DIR;
    $sql = "SELECT `show`.id, `show`.title, `show`.date, show_category.name AS category,
            CONCAT(city.name, ', ', country.name) AS location,
            CONCAT('$imgPath', `show`.id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imageAlt
        FROM `show`
        JOIN show_category ON show_category.id = `show`.show_category_id
        JOIN venue ON venue.id = `show`.venue_id
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        LEFT JOIN image ON image.subject_id = `show`.id AND image.subject = 'show' AND image.type = 'horizontal'
        WHERE `show`.enabled=1";

    $q = $this->get('db')->prepare($sql);

    return query($response, $q, 'shows');
};

$showDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_DIR;

    // Fetch Show
    $sql = "SELECT `show`.id, `show`.title, `show`.date,
            CONCAT(venue.name, ', ', city.name, ', ', country.name) AS location,
            `show`.description,
            venue.id AS venueId,
            artist.name AS artist, artist.id AS artistId
        FROM `show`
        JOIN artist ON artist.id = `show`.artist_id
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

$venueDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_DIR;

    $sql = "SELECT venue.id, venue.name, venue.description,
            CONCAT(venue.address, ', ', city.name, ', ', country.name) AS location,
            CONCAT('$imgPath', :venue_id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imgAlt
        FROM venue
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        LEFT JOIN image ON image.subject_id = venue.id AND image.subject = 'venue' AND image.type = 'vertical'
        WHERE venue.id = :venue_id";
    
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':venue_id', $args['id'], PDO::PARAM_STR);
    
    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $venue = $q->fetch();
    if (!$venue) {
        return $response->withStatus(404);
    }

    return jsonResponse($response, 'venue', $venue);
};

$artistDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_DIR;

    $sql = "SELECT artist.*,
            CONCAT('$imgPath', :artist_id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imgAlt
        FROM artist
        LEFT JOIN image ON image.subject_id = artist.id AND image.subject = 'artist' AND image.type = 'vertical'
        WHERE artist.id = :artist_id";
    
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':artist_id', $args['id'], PDO::PARAM_STR);
    
    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $artist = $q->fetch();
    if (!$artist) {
        return $response->withStatus(404);
    }

    return jsonResponse($response, 'artist', $artist);
};
