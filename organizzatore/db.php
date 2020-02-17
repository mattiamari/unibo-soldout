<?php

function generateId() {
    $id = base64_encode(random_bytes(8));
    $id = str_replace("+", "-", $id);
    $id = str_replace("/", "_", $id);
    $id = str_replace("=", "", $id);
    return $id;
}

function generateOrderRef() {
    return random_int(1, 9) * 1000
        + random_int(0, 999);
}

class Evento {
    public $id;
    public $artist_id;
    public $venue_id;
    public $title;
    public $description;
    public $date;
    public $bigliettiVenduti;
    public $bigliettiTotali;

    public function __construct($id, $nome, $data, $artist_id, $venue_id, $bigliettiTotali = 0, $bigliettiVenduti = 0, $descrizione = "") {
        $this->id = $id;
        $this->artist_id = $artist_id;
        $this->venue_id = $venue_id;
        $this->title = $nome;
        $this->description = $descrizione;
        $this->description = $data;
        $this->bigliettiVenduti = $bigliettiVenduti;
        $this->bigliettiTotali = $bigliettiTotali;
    }
}

class Db {
    
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    function getArtistList() {
        $sql = "SELECT id,name,description FROM artist";
        $result = $this->pdo->query($sql);
        return $result->fetchAll();
    }

    function getVenueList() {
        $sql = "SELECT id,name, description, address, city_id FROM venue";
        $result = $this->pdo->query($sql);
        return $result->fetchAll();
    }

    function getEventList() {
        $sql = ("SELECT `show`.id, `show`.title, `show`.date, `show`.enabled,
                sum(ticket_type.max_tickets) AS tickets_total, 
                sum(cart_item.quantity) AS tickets_sold, 
                sum(cart_item.quantity * ticket_type.price) AS total_profit
            FROM `show` 
            LEFT JOIN ticket_type ON ticket_type.show_id = `show`.id
            JOIN cart_item ON cart_item.ticket_type_id = ticket_type.id
            GROUP BY `show`.id");
        $result = $this->pdo->query($sql);
        return $result->fetchAll();
    }

    function getProfitByEventId($eventId) {
        $sql = $this->pdo->prepare("SELECT SUM(total.total_profit)
        FROM
            (SELECT ROUND(SUM(cart_item.quantity) * ticket_type.price, 2) AS total_profit FROM cart_item
             JOIN ticket_type ON ticket_type.id = cart_item.ticket_type_id
             WHERE ticket_type.show_id = :eventId
             GROUP BY cart_item.ticket_type_id) as total");
        $sql->bindParam(':eventId', $eventId);
        $result = $sql->execute();
        return $sql->fetchColumn();

    }


    function getShowCategoryList() {
        $sql = "SELECT id, name FROM show_category";
        $result = $this->pdo->query($sql);
        return $result->fetchAll();
    }

    function getArtistById($artistId) {
        $sql = $this->pdo->prepare("SELECT id, name, description FROM artist WHERE id=:artistId");
        $sql->bindParam(':artistId', $artistId);
        $result = $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    function getVenueById($venueId) {
        $sql = $this->pdo->prepare("SELECT id, name, description, address, city_id FROM venue WHERE id=:venueId");
        $sql->bindParam(':venueId', $venueId);
        $result = $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    function getEventById($eventId) {
        $sql = $this->pdo->prepare("SELECT id, title, date, show_category_id, max_tickets_per_order, venue_id,
        artist_id, description FROM `show` WHERE id=:eventId");
        $sql->bindParam(':eventId', $eventId);
        $result = $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    function getTicketTypesByEventId($showId) {
        $sql = $this->pdo->prepare("SELECT id, name, description, price, max_tickets, show_id FROM ticket_type WHERE show_id=:showId");
        $sql->bindParam(':showId', $showId);
        $result = $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getTicketTypeByTicketId($ticketId) {
        $sql = $this->pdo->prepare("SELECT name, description, price, max_tickets FROM ticket_type WHERE id=:id");
        $sql->bindParam(':id', $ticketId);
        $result = $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    function insertNewArtist($id, $name, $description) {
        $sql = $this->pdo->prepare("INSERT INTO artist (id, name, description) VALUES (:id, :name, :description)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':name', $name);
        $sql->bindParam(':description', $description);
        return $sql->execute();
    }

    function insertNewVenue($id, $name, $description, $address, $city_id) {
        $sql = $this->pdo->prepare("INSERT INTO venue (id, name, description, address, city_id) VALUES (:id, :name, :description, :address, :city_id)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':name', $name);
        $sql->bindParam(':description', $description);
        $sql->bindParam(':address', $address);
        $sql->bindParam(':city_id', $city_id);
        return $sql->execute();
    }

    function insertNewShow($id, $title, $date, $description, $show_category, $max_tickets_per_order, $venue_id, $artist_id, $enabled) {
        $sql = $this->pdo->prepare("INSERT INTO `show` (id, title, date, description show_category, max_tickets_per_order, venue_id, artist_id, `enabled`) VALUES (:id, :title, :show_Category, :max_ticket_per_order, :venue_id, :artist_id, `:enabled`)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':title', $title);
        $sql->bindParam(':date', $date);
        $sql->bindParam(':description', $description);
        $sql->bindParam(':show_category', $show_category);
        $sql->bindParam(':max_tickets_per_order', $max_tickets_per_order, PDO::PARAM_INT);
        $sql->bindParam(':venue_id', $venue_id);
        $sql->bindParam(':artist_id', $artist_id);
        $sql->bindParam(':enabled', $enabled, PDO::PARAM_INT);
        return $sql->execute();
    }

    function insertNewTicketType($id, $showId, $name, $description, $price, $max_tickets) {
        $sql = $this->pdo->prepare("INSERT INTO ticket_type (id, show_id, name, description, price, max_tickets) VALUES (:id, :showId, :name, :description, :price, :max_tickets)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':showId', $showId);
        $sql->bindParam(':name', $name);
        $sql->bindParam(':description', $description);
        $sql->bindParam(':price', $price);
        $sql->bindParam(':max_tickets', $max_tickets);
        return $sql->execute();
    }

    function insertNewNotification($id, $content, $action) {
        $sql = $this->pdo->prepare("INSERT INTO notification (id, content, action) VALUES (:id, :content, :action)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':content', $content);
        $sql->bindParam(':action', $action);
        return $sql->execute();
    }

    function insertNewUserNotification($notificationId, $userIds) {
        $q = "INSERT INTO user_notification (notification_id, user_id) VALUES ";
        $q = $q . implode(',', array_map(function($e) {return "(:notificationId, '$e')"; }, $userIds));

        $sql = $this->pdo->prepare($q);
        $sql->bindParam(':notificationId', $notificationId);
        return $sql->execute();
    }

    function updateArtistById($artistId, $name, $description){
        $sql = $this->pdo->prepare("UPDATE artist SET name=:name, description=:description WHERE id=:artistId");
        $sql->bindParam(':artistId',$artistId);
        $sql->bindParam(':name',$name);
        $sql->bindParam(':description',$description);
        return $sql->execute();
    }

    function getCustomerByEvent($eventId) {
        $sql = $this->pdo->prepare("SELECT DISTINCT customer_id FROM `order` 
                            JOIN cart ON cart.id = `order`.cart_id 
                            JOIN cart_item ON cart_item.cart_id = cart.id 
                            JOIN ticket_type ON ticket_type.id = cart_item.ticket_type_id 
                        WHERE show_id = :show_id");
        $sql->bindParam(':show_id',$eventId);
        $result = $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    function updateVenueById($venueId, $name, $description, $address, $city_id){
        $sql = $this->pdo->prepare("UPDATE venue SET name=:name, address=:address, description=:description, city_id=:city_id  WHERE id=:venueId");
        $sql->bindParam(':venueId',$venueId);
        $sql->bindParam(':name',$name);
        $sql->bindParam(':description',$description);
        $sql->bindParam(':address',$address);
        $sql->bindParam(':city_id',$city_id);
        return $sql->execute();
    }

    function updateEventById($eventId, $title, $date, $description, $show_category_id, $max_tickets_per_order, $artist_id, $venue_id){
        $sql = $this->pdo->prepare("UPDATE `show` 
                SET title=:title, date=:date, description=:description, 
                show_category_id=:show_category_id, max_tickets_per_order=:max_tickets_per_order,
                 artist_id=:artist_id, venue_id=:venue_id WHERE id=:eventId");
        $sql->bindParam(':eventId',$eventId);
        $sql->bindParam(':title',$title);
        $sql->bindParam(':date',$date);
        $sql->bindParam(':description',$description);
        $sql->bindParam(':show_category_id',$show_category_id);
        $sql->bindParam(':max_tickets_per_order', $max_tickets_per_order);
        $sql->bindParam(':artist_id',$artist_id);
        $sql->bindParam(':venue_id',$venue_id);
        return $sql->execute();
    }

    function updateTicketTypeById($ticketId, $name, $description, $price, $max_tickets){
        $sql = $this->pdo->prepare("UPDATE ticket_type SET name=:name, description=:description, price=:price, max_tickets=:max_tickets WHERE id=:ticketId");
        $sql->bindParam(':ticketId',$ticketId);
        $sql->bindParam(':name',$name);
        $sql->bindParam(':description',$description);
        $sql->bindParam(':price',$price);
        $sql->bindParam(':max_tickets',$max_tickets);
        return $sql->execute();
    }

    function deleteArtistById($artistId) {
        $sql = $this->pdo->prepare("DELETE FROM artist WHERE id=:artistId ");
        $sql->bindParam(':artistId', $artistId);
        return $sql->execute();
    }

    function deleteVenueById($venueId) {
        $sql = $this->pdo->prepare("DELETE FROM venue WHERE id=:venueId ");
        $sql->bindParam(':venueId', $venueId);
        return $sql->execute();
    }

    function deleteEventById($eventId) {
        $sql = $this->pdo->prepare("DELETE FROM `show` WHERE id=:eventId ");
        $sql->bindParam(':eventId', $eventId);
        return $sql->execute();
    }

    function deleteTicketTypeById($ticketId) {
        $sql = $this->pdo->prepare("DELETE FROM ticket_type WHERE id=:ticketId");
        $sql->bindParam(':ticketId', $ticketId);
        return $sql->execute();
    }

    function getQtyTicketSoldByEventId($eventId){
        $sql=$this->pdo->prepare("SELECT sum(cart_item.quantity) AS totalQty FROM `order`
                    JOIN cart ON cart.id = order.cart_id
                    JOIN cart_item ON cart_item.cart_id = cart.id
                    JOIN ticket_type ON ticket_type.id = cart_item.ticket_type_id
                    WHERE ticket_type.show_id = :eventId");
        $sql->bindParam(':eventId', $eventId);
        $sql->execute();
        return $sql->fetch();
    }
    
    function getQtyTicketSoldByCategory($eventId) {
        $sql = $this->pdo->prepare("SELECT ticket_type.name,
                SUM(cart_item.quantity) AS total_sold,
                ticket_type.max_tickets,
                ROUND(SUM(cart_item.quantity) * ticket_type.price, 2) AS profit
            FROM `show`
            JOIN ticket_type ON ticket_type.show_id = `show`.id
            JOIN cart_item ON cart_item.ticket_type_id = ticket_type.id
            WHERE `show`.id = :eventId
            GROUP BY cart_item.ticket_type_id");
        $sql->bindParam(':eventId', $eventId);
        $result = $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function getCountries() {
        $sql = "SELECT id, name FROM country";
        $result = $this->pdo->query($sql);
        return $result->fetchAll();
    }

    function getStates($country_id) {
        $sql = $this->pdo->prepare("SELECT id, name FROM geo_state WHERE country_id=:countryid");
        $sql->bindParam(':countryid', $country_id, PDO::PARAM_INT);
        $result = $sql->execute();
        return $sql->fetchAll();
    }

    function getCities($state_id) {
        $sql = $this->pdo->prepare("SELECT id, name FROM city WHERE state_id=:stateid");
        $sql->bindParam(':stateid', $state_id, PDO::PARAM_INT);
        $result = $sql->execute();
        return $sql->fetchAll();
    }

    function getStateByCityId($city_id) {
        $sql = $this->pdo->prepare("SELECT state_id FROM city WHERE id=:city_id");
        $sql->bindParam(':city_id', $city_id, PDO::PARAM_INT);
        $result = $sql->execute();
        return $sql->fetch();
    }

    function getCountryByStateId($state_id) {
        $sql = $this->pdo->prepare("SELECT country_id FROM geo_state WHERE id=:state_id");
        $sql->bindParam(':state_id', $state_id, PDO::PARAM_INT);
        $result = $sql->execute();
        return $sql->fetch();
    }

    function updateImage($subject_id,$subject, $name, $type, $alt) {
        $sql = $this->pdo->prepare("insert into image values (:subject_id, :subject, :name, :type, :alt)
                on duplicate key update name = :name, altText = :alt");
        $sql->bindParam(':subject_id', $subject_id);
        $sql->bindParam(':subject', $subject);
        $sql->bindParam(':name', $name);
        $sql->bindParam(':type', $type);
        $sql->bindParam(':alt', $alt);
        $result = $sql->execute();
    }

    function insertUser($id, $email, $password, $salt) {
        $sql = $this->pdo->prepare("INSERT INTO user (id, email, password, salt) values (:id, :email, :password, :salt)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':password', $password);
        $sql->bindParam(':salt', $salt);
        $result = $sql->execute();
    }

    
    
}

try {
    // stringa di connessione al DBMS
    $pdo = new PDO("mysql:host=localhost;dbname=soldout", 'root', '');
    /*
    Avremmo potuto anche omettere dbname in questo modo:
    $connessione = new PDO("mysql:host=$host", $user, $password);
    */
  }
  catch(PDOException $e)
  {
    // notifica in caso di errore nel tentativo di connessione
    echo $e->getMessage();
  }

$db = new Db($pdo);
