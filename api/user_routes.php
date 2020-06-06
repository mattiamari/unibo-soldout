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
        JOIN cart ON cart.id = `order`.cart_id
        JOIN cart_item ON cart_item.cart_id = cart.id
        JOIN ticket_type ON ticket_type.id = cart_item.ticket_type_id
        JOIN `show` ON `show`.id = ticket_type.show_id
        WHERE customer_id = :customer_id
        GROUP BY `order`.cart_id
        ORDER BY `order`.date DESC";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':customer_id', $request->getAttribute('user_id'));

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $orders = $q->fetchAll();

    return jsonResponse($response, 'orders', $orders);
};

$orderDetailsRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT `order`.*
        FROM `order`
        JOIN cart ON cart.id = `order`.cart_id
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
            ticket_type.name AS `type`,
            ticket_type.price
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
    $sql = "SELECT n.*, un.read FROM user_notification un
        JOIN notification n ON n.id = un.notification_id
        WHERE user_id = :user_id";
    
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    return query($response, $q, 'notifications');
};

$unreadNotificationsCountRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT count(*) AS notifications_count FROM user_notification
        WHERE `user_id` = :user_id AND `read` = 0";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $count = $q->fetchColumn();

    return jsonResponse($response, 'unread_notifications_count', $count);
};

$markReadNotificationsRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "UPDATE user_notification un
        JOIN notification n on n.id = un.notification_id
        SET `read` = 1
        WHERE `user_id` = :user_id AND `read` = 0 AND `date` <= :older";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    $older = $request->getParsedBody()['older'];
    if (!$older) {
        $older = (new DateTime())->format('Y-m-d H:i:s');
    }
    $q->bindValue(':older', $older);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    return $response->withStatus(200);
};

$loginRoute = function (Request $request, ResponseInterface $response, $args) {
    $db = $this->get('db');

    $sql = "SELECT user.* FROM customer
        JOIN user ON user.id = customer.user_id
        WHERE email = :email";
    
    $q = $db->prepare($sql);
    $q->bindValue(':email', $request->getParsedBody()['email']);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $user = $q->fetch();

    if (!$user) {
        return $response->withStatus(401);
    }

    $password = hashPassword($request->getParsedBody()['password'], $user['salt']);
    if ($password !== $user['password']) {
        return $response->withStatus(401);
    }

    $apiKey = generateApiKey();

    $sql = "INSERT INTO api_key VALUES (:key, :user_id, :expiry)";
    $apiKeyQ = $db->prepare($sql);
    $apiKeyQ->bindValue(':key', $apiKey['key']);
    $apiKeyQ->bindValue(':user_id', $user['id']);
    $apiKeyQ->bindValue(':expiry', $apiKey['expiry']);

    if (!$apiKeyQ->execute()) {
        $response->getBody()->write(error($apiKeyQ->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $response = jsonResponse($response, 'login', [
        'userId' => $user['id'],
        'key' => $apiKey['key']
    ]);

    return $response->withStatus(200);
};

$signupRoute = function (Request $request, ResponseInterface $response, $args) {
    $userData = $request->getParsedBody();

    if ($userData['password'] !== $userData['passwordRepeat']) {
        return $response->withStatus(400);
    }

    $salt = generateSalt();
    $password = hashPassword($userData['password'], $salt);
    $userId = generateId();
    $apiKey = generateApiKey();
    $db = $this->get('db');

    $sql = "INSERT INTO user VALUES (:id, :email, :password, :salt)";
    $userQ = $db->prepare($sql);
    $userQ->bindValue(':id', $userId);
    $userQ->bindValue(':email', $userData['email']);
    $userQ->bindValue(':password', $password);
    $userQ->bindValue(':salt', $salt);

    $sql = "INSERT INTO customer VALUES (:id, :firstname, :lastname, :birthdate)";
    $customerQ = $db->prepare($sql);
    $customerQ->bindValue(':id', $userId);
    $customerQ->bindValue(':firstname', $userData['firstname']);
    $customerQ->bindValue(':lastname', $userData['lastname']);
    $customerQ->bindValue(':birthdate', $userData['birthdate']);

    $sql = "INSERT INTO api_key VALUES (:key, :user_id, :expiry)";
    $apiKeyQ = $db->prepare($sql);
    $apiKeyQ->bindValue(':key', $apiKey['key']);
    $apiKeyQ->bindValue(':user_id', $userId);
    $apiKeyQ->bindValue(':expiry', $apiKey['expiry']);

    $db->beginTransaction();
    if (!$userQ->execute()) {
        $response->getBody()->write(error($userQ->errorInfo()[2]));
        $db->rollback();
        return $response->withStatus(500);
    }
    if (!$customerQ->execute()) {
        $response->getBody()->write(error($customerQ->errorInfo()[2]));
        $db->rollback();
        return $response->withStatus(500);
    }
    if (!$apiKeyQ->execute()) {
        $response->getBody()->write(error($apiKeyQ->errorInfo()[2]));
        $db->rollback();
        return $response->withStatus(500);
    }
    $db->commit();

    $response = jsonResponse($response, 'login', [
        'userId' => $userId,
        'key' => $apiKey['key']
    ]);

    return $response->withStatus(200);
};

$logoutRoute = function (Request $request, ResponseInterface $response, $args) {
    try {
        $auth = parseAuthHeader($request->getHeader('x-auth')[0]);

        $sql = "DELETE FROM api_key WHERE user_id = :user_id AND api_key = :api_key";
        $q = $this->get('db')->prepare($sql);
        $q->bindValue(':user_id', $auth[0]);
        $q->bindValue(':api_key', $auth[1]);
        $q->execute();
    } catch (Exception $e) {
        // noop
    }

    return $response->withStatus(200);
};

$cartRoute = function (Request $request, ResponseInterface $response, $args) {
    $sql = "SELECT ticket_type_id AS ticketTypeId, quantity, show_id
        FROM cart
        JOIN cart_item ON cart_item.cart_id = cart.id
        JOIN ticket_type ON ticket_type.id = cart_item.ticket_type_id
        WHERE customer_id = :user_id
            AND cart.id NOT IN (SELECT cart_id FROM `order`)";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $items = $q->fetchAll();
    return jsonResponse($response, 'cartItems', $items);
};

$cartAddRoute = function (Request $request, ResponseInterface $response, $args) {
    $cartId = getCurrentCart($this->get('db'), $request->getAttribute('user_id'));
    $body = $request->getParsedBody();

    $sql = "INSERT INTO cart_item VALUES (:cart_id, :ticket_type_id, :quantity)
        ON DUPLICATE KEY UPDATE quantity = quantity + :quantity";
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':cart_id', $cartId);
    $q->bindValue(':ticket_type_id', $body['ticketTypeId']);
    $q->bindValue(':quantity', $body['quantity'], PDO::PARAM_INT);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    return $response->withStatus(201);
};

$cartRemoveRoute = function (Request $request, ResponseInterface $response, $args) {
    $cartId = getCurrentCart($this->get('db'), $request->getAttribute('user_id'));

    $sql = "DELETE cart_item FROM cart_item
        JOIN cart ON cart.id = cart_item.cart_id
        WHERE cart_id = :cart_id AND ticket_type_id = :ticket_type_id AND customer_id = :user_id";
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':cart_id', $cartId);
    $q->bindValue(':ticket_type_id', $args['ticket_type_id']);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    return $response->withStatus(204);
};

$cartClearRoute = function (Request $request, ResponseInterface $response, $args) {
    $cartId = getCurrentCart($this->get('db'), $request->getAttribute('user_id'));

    $sql = "DELETE FROM cart_item
        JOIN cart on cart.id = cart_item.cart_id
        WHERE cart_id = :cart_id AND customer_id = :user_id";
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':cart_id', $cartId);
    $q->bindValue(':user_id', $request->getAttribute('user_id'));

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    return $response->withStatus(204);
};

$makeOrderRoute = function (Request $request, ResponseInterface $response, $args) {
    $cartId = getCurrentCart($this->get('db'), $request->getAttribute('user_id'));

    // Count items in cart
    $sql = "SELECT COUNT(*) FROM cart_item
        JOIN cart ON cart.id = cart_item.cart_id
        WHERE cart.customer_id = :customer_id
            AND cart.id = :cart_id";

    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':customer_id', $request->getAttribute('user_id'));
    $q->bindValue(':cart_id', $cartId);
    
    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    $count = $q->fetchColumn();

    if (!$count) {
        return $response->withStatus(400);
    }

    $orderRef = generateOrderRef();
    $sql = "INSERT INTO `order` (cart_id, reference, status) VALUES (:cart_id, :ref, 'paid')";
    $q = $this->get('db')->prepare($sql);
    $q->bindValue(':cart_id', $cartId);
    $q->bindValue(':ref', $orderRef);

    if (!$q->execute()) {
        $response->getBody()->write(error($q->errorInfo()[2]));
        return $response->withStatus(500);
    }

    return $response->withStatus(201);
};

/**
 * Returns the current cart_id or creates a new one
 */
function getCurrentCart($db, $userId) {
    $sql = "SELECT cart.id FROM cart
       WHERE cart.id NOT IN (SELECT cart_id FROM `order`)
        AND cart.customer_id = :user_id";
    $q = $db->prepare($sql);
    $q->bindValue(':user_id', $userId);

    if (!$q->execute()) {
        return false;
    }

    $cartId = $q->fetchColumn();

    if ($cartId) {
        return $cartId;
    }

    $cartId = generateId();
    $sql = "INSERT INTO cart VALUES (:cart_id, :user_id)";
    $q = $db->prepare($sql);
    $q->bindValue(':cart_id', $cartId);
    $q->bindValue(':user_id', $userId);

    if (!$q->execute()) {
        return false;
    }

    return $cartId;
}
