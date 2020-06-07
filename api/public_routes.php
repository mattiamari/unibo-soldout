<?php

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

$imgPath = IMAGE_URL;
$showSummaryQuery = "SELECT `show`.id, `show`.title, `show`.date,
    CONCAT(city.name, ', ', country.name) AS location,
    CONCAT('$imgPath', '/', `show`.id, '/', image.type, '/', image.name) AS imageUrl,
    image.altText AS imageAlt
    FROM `show`
    JOIN venue ON venue.id = `show`.venue_id
    JOIN city ON city.id = venue.city_id
    JOIN country ON country.id = city.country_id
    LEFT JOIN image ON image.subject_id = `show`.id AND image.subject = 'show' AND image.type = 'horizontal'
    WHERE `show`.enabled=1 ";

$showsSummaryByCategoryRoute = function (Request $request, ResponseInterface $response, $args) {
    global $showSummaryQuery;
    $sql = $showSummaryQuery . "AND `show`.show_category_id = :category";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':category', $args['category_id'], PDO::PARAM_STR);

    return query($response, $q, 'shows');
};

$newShowsRoute = function (Request $request, ResponseInterface $response, $args) {
    global $showSummaryQuery;
    $sql = $showSummaryQuery . "AND `show`.creation_date > DATE_SUB(NOW(), INTERVAL 30 DAY)
        ORDER BY `show`.date
        LIMIT 12";

    $q = $this->get('db')->prepare($sql);
    return query($response, $q, 'shows');
};

$hotShowsRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_URL;
    $sql =  "SELECT `show`.id, `show`.title, `show`.date,
        CONCAT(city.name, ', ', country.name) AS location,
        CONCAT('$imgPath', '/', `show`.id, '/', image.type, '/', image.name) AS imageUrl,
        image.altText AS imageAlt,
        COUNT(`order`.cart_id) as orders
        FROM `show`
        JOIN venue ON venue.id = `show`.venue_id
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        LEFT JOIN image ON image.subject_id = `show`.id AND image.subject = 'show' AND image.type = 'horizontal'
        JOIN ticket_type tt on tt.show_id = `show`.id
        JOIN cart_item ci on ci.ticket_type_id = tt.id
        JOIN cart on cart.id = ci.cart_id
        JOIN `order` on order.cart_id = cart.id
        WHERE `show`.enabled=1 AND `show`.date > NOW()
        GROUP BY `show`.id
        ORDER BY orders DESC, `show`.date
        LIMIT 12";

    $q = $this->get('db')->prepare($sql);
    return query($response, $q, 'shows');
};


$showCategoriesRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT * FROM `show_category`";
    $q = $this->get('db')->prepare($sql);
    return query($response, $q, 'show_categories');
};

$showsSummaryCategorizedRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_URL;
    $sql = "SELECT `show`.id, `show`.title, `show`.date, show_category.name AS category,
            CONCAT(city.name, ', ', country.name) AS location,
            CONCAT('$imgPath', '/', `show`.id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imageAlt
        FROM `show`
        JOIN show_category ON show_category.id = `show`.show_category_id
        JOIN venue ON venue.id = `show`.venue_id
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        LEFT JOIN image ON image.subject_id = `show`.id AND image.subject = 'show' AND image.type = 'horizontal'
        WHERE `show`.enabled=1
        ORDER BY `show`.date";

    $q = $this->get('db')->prepare($sql);

    return query($response, $q, 'shows');
};

$showSearchRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_URL;

    
    if (!isset($request->getQueryParams()['q'])) {
        return jsonResponse($response, 'shows', []);
    }

    $query = trim($request->getQueryParams()['q']);
    if ($query == "") {
        return jsonResponse($response, 'shows', []);
    }

    $sql = "SELECT `show`.id, `show`.title, `show`.date, show_category.name AS category,
            CONCAT(city.name, ', ', country.name) AS location,
            CONCAT('$imgPath', '/', `show`.id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imageAlt
        FROM `show`
        JOIN show_category ON show_category.id = `show`.show_category_id
        JOIN venue ON venue.id = `show`.venue_id
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        LEFT JOIN image ON image.subject_id = `show`.id AND image.subject = 'show' AND image.type = 'horizontal'
        WHERE `show`.enabled=1 AND `show`.title like :searchQuery";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':searchQuery', "%" . $query . "%", PDO::PARAM_STR);

    return query($response, $q, 'shows');
};

$showDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $imgPath = IMAGE_URL;

    // Fetch Show
    $sql = "SELECT `show`.id, `show`.title, `show`.date, `show`.max_tickets_per_order AS maxTicketsPerOrder,
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
            CONCAT('$imgPath', '/', :show_id, '/', image.type, '/', image.name) AS url
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
    $sql = "SELECT s.id AS showId, tt.id, tt.name, tt.description, tt.price, tt.max_tickets as maxTickets,
        SUM( IF(o.cart_id IS NOT NULL, ci.quantity, 0) ) AS quantitySold
        FROM `show` s
        JOIN `ticket_type` tt ON tt.show_id = s.id
        LEFT JOIN `cart_item` ci ON ci.ticket_type_id = tt.id
        LEFT JOIN `cart` c ON c.id = ci.cart_id
        LEFT JOIN `order` o ON o.cart_id = c.id
        WHERE show_id = :show_id
        GROUP BY s.id, tt.id";
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
    $imgPath = IMAGE_URL;

    $sql = "SELECT venue.id, venue.name, venue.description,
            CONCAT(venue.address, ', ', city.name, ', ', country.name) AS location,
            CONCAT('$imgPath', '/', :venue_id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imageAlt
        FROM venue
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        LEFT JOIN image ON image.subject_id = venue.id AND image.subject = 'venue' AND image.type = 'horizontal'
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
    $imgPath = IMAGE_URL;

    $sql = "SELECT artist.*,
            CONCAT('$imgPath', '/', :artist_id, '/', image.type, '/', image.name) AS imageUrl,
            image.altText AS imageAlt
        FROM artist
        LEFT JOIN image ON image.subject_id = artist.id AND image.subject = 'artist' AND image.type = 'horizontal'
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
