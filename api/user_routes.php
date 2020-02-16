<?php

use Psr\Http\Message\ResponseInterface as ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

require_once 'auth.php';
require_once 'idgen.php';

$accountDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT user_id AS id, email, firstname, lastname, birthdate
        FROM `user`
        JOIN customer ON customer.user_id = `user`.id
        WHERE user_id = :user_id";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $account = $q->fetch();
    if (!$account) {
        return $response->withStatus(404);
    }

    return jsonResponse($response, 'account', $account);
};

$ordersRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT `order`.*, `show`.title AS contentSummary,
            COUNT(DISTINCT `show`.id) AS showsCount
        FROM `order`
        JOIN cart ON cart.id = order.cart_id
        JOIN cart_item ON cart_item.cart_id = cart.id
        JOIN ticket_type ON ticket_type.id = cart_item.ticket_type_id
        JOIN `show` ON `show`.id = ticket_type.show_id
        WHERE customer_id = :customer_id
        GROUP BY `order`.cart_id";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':customer_id', $request->getAttribute('user_id'));

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $orders = $q->fetchAll();
    if (!$orders) {
        return $response->withStatus(404);
    }

    return jsonResponse($response, 'orders', $orders);
};

$orderDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT `order`.*
        FROM `order`
        JOIN cart ON cart.id = order.cart_id
        WHERE customer_id = :customer_id AND `order`.cart_id = :order_id";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':customer_id', $request->getAttribute('user_id'));
    $q->bindValue(':order_id', $args['order_id']);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $order = $q->fetch();
    if (!$order) {
        return $response->withStatus(404);
    }

    $sql = "SELECT `show`.title, `show`.date,
            CONCAT(venue.name, ', ', city.name, ', ', country.name) AS location,
            ticket_type.name AS ticketType
        FROM cart_item
        JOIN cart ON cart.id = cart_item.cart_id
        JOIN `order` ON `order`.cart_id = cart.id
        JOIN ticket_type ON ticket_type.id = cart_item.ticket_type_id
        JOIN `show` ON `show`.id = ticket_type.show_id
        JOIN venue ON venue.id = `show`.venue_id
        JOIN city ON city.id = venue.city_id
        JOIN country ON country.id = city.country_id
        WHERE cart.customer_id = :customer_id AND `order`.cart_id = :order_id";
    
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':customer_id', $request->getAttribute('user_id'));
    $q->bindValue(':order_id', $args['order_id']);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $tickets = $q->fetchAll();
    $order['tickets'] = $tickets;

    return jsonResponse($response, 'order', $order);
};

$notificationsRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT notification.* FROM user_notification
        JOIN notification ON notification.id = user_notification.notification_id
        WHERE user_id = :user_id";
    
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    return query($response, $q, 'notifications');
};

$loginRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT user.* FROM customer
        JOIN user ON user.id = customer.user_id
        WHERE email = :email";
    
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':email', $request->getParsedBody()['email']);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $user = $q->fetch();

    if (!$user) {
        return $response->withStatus(401);
    }

    $password = hashPassword($request->getParsedBody()['password']);
    if ($password !== $user['password']) {
        return $response->withStatus(401);
    }

    return $response->withStatus(204);
};

$signupRoute = function (Request $request, ResponseInterface $response, $args) {
    $userData = $request->getParsedBody();

    if ($userData['password'] !== $userData['passwordRepeat']) {
        return $response->withStatus(400);
    }

    $salt = generateSalt();
    $password = hashPassword($userData['password'], $salt);
    $userId = generateId();
    $db = $this->get('db');

    $sql = "INSERT INTO user values (:id, :email, :password, :salt)";
    $userQ = $db->prepare($sql);
    $userQ->bindValue(':id', $userId);
    $userQ->bindValue(':email', $userData['email']);
    $userQ->bindValue(':password', $password);
    $userQ->bindValue(':salt', $salt);

    $sql = "INSERT INTO customer values (:id, :firstname, :lastname, :birthdate)";
    $customerQ = $db->prepare($sql);
    $customerQ->bindValue(':id', $userId);
    $customerQ->bindValue(':firstname', $userData['firstname']);
    $customerQ->bindValue(':lastname', $userData['lastname']);
    $customerQ->bindValue(':birthdate', $userData['birthdate']);

    $db->beginTransaction();
    if (!$userQ->execute()) {
        $db->rollback();
        return $response->withStatus(500);
    }
    if (!$customerQ->execute()) {
        $db->rollback();
        return $response->withStatus(500);
    }
    $db->commit();

    return $response->withStatus(204);
};
