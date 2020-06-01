<?php

require_once "../api/idgen.php";

$imgPath = "/i/";

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
            LEFT JOIN cart_item ON cart_item.ticket_type_id = ticket_type.id
            GROUP BY `show`.id");
        $result = $this->pdo->query($sql);
        return $result->fetchAll();
    }

    function getEventListByManagerId($manager_id) {
        $sql = $this->pdo->prepare("SELECT `show`.id, `show`.title, `show`.date, `show`.enabled,
                sum(ticket_type.max_tickets) AS tickets_total, 
                sum(cart_item.quantity) AS tickets_sold, 
                sum(cart_item.quantity * ticket_type.price) AS total_profit
            FROM `show`
            LEFT JOIN ticket_type ON ticket_type.show_id = `show`.id
            LEFT JOIN cart_item ON cart_item.ticket_type_id = ticket_type.id
            WHERE `show`.manager_id=:manager_id
            GROUP BY `show`.id");
            $sql->bindParam(':manager_id', $manager_id);
            $result=$sql->execute();
            if($result) {
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
    }

    function getDontEnabledEventList($enabled) {
        $sql = $this->pdo->prepare("SELECT `show`.id, `show`.title, `show`.date, `show`.enabled,
                sum(ticket_type.max_tickets) AS tickets_total, 
                sum(cart_item.quantity) AS tickets_sold, 
                sum(cart_item.quantity * ticket_type.price) AS total_profit
            FROM `show`
            LEFT JOIN ticket_type ON ticket_type.show_id = `show`.id
            LEFT JOIN cart_item ON cart_item.ticket_type_id = ticket_type.id
            WHERE `show`.enabled=:enabled
            GROUP BY `show`.id");
        $sql->bindParam(':enabled', $enabled, PDO::PARAM_INT);
        $result=$sql->execute();
        if($result) {
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function getDontEnabledManagerList($enabled) {
        $sql = $this->pdo->prepare("SELECT user.id, user.email, manager.is_admin FROM user
            JOIN manager ON user.id=manager.user_id
            WHERE manager.enabled=:enabled");
            $sql->bindParam(':enabled', $enabled, PDO::PARAM_INT);
            $result=$sql->execute();
            if($result) {
                return $sql->fetchAll(PDO::FETCH_ASSOC);
            }
    }

    function countArtistWithShow() {
        $sql = "SELECT artist.id, artist.name, artist.description, count(show.id) AS show_count FROM artist
            LEFT JOIN `show` ON `show`.artist_id = artist.id
            GROUP BY artist.id";
            $result = $this->pdo->query($sql);
            return $result->fetchAll();
    }

    function countArtistWithShowById($id) {
        $sql = $this->pdo->prepare("SELECT artist.id, artist.name, artist.description, count(show.id) AS show_count FROM artist
            LEFT JOIN `show` ON `show`.artist_id = artist.id
            WHERE artist.id = :id
            GROUP BY artist.id");
            $sql->bindParam(':id', $id);
            $result=$sql->execute();
            if($result) {
                return $sql->fetch(PDO::FETCH_ASSOC);
            }
    }

    function countVenueWithShow() {
        $sql = "SELECT venue.id, venue.name, venue.description, venue.address, count(show.id) AS show_count FROM venue
            LEFT JOIN `show` ON `show`.venue_id = venue.id
            GROUP BY venue.id";
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
        return $result->fetchAll(PDO::FETCH_ASSOC);
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

    function insertNewShow($id, $title, $date, $description, $manager_id, $show_category, $max_tickets_per_order, $enabled) {
        $sql = $this->pdo->prepare("INSERT INTO `show` (id, title, date, description, manager_id, show_category_id, max_tickets_per_order, enabled) VALUES (:id, :title, :date, :description, :manager_id, :show_category_id, :max_tickets_per_order, :enabled)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':title', $title);
        $sql->bindParam(':date', $date);
        $sql->bindParam(':description', $description);
        $sql->bindParam(':manager_id', $manager_id);
        $sql->bindParam(':show_category_id', $show_category);
        $sql->bindParam(':max_tickets_per_order', $max_tickets_per_order, PDO::PARAM_INT);
        $sql->bindParam(':enabled', $enabled, PDO::PARAM_INT);
        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
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

    function updateEventById($id, $title, $date, $description, $manager_id, $show_category, $max_tickets_per_order, $enabled){
        $sql = $this->pdo->prepare("UPDATE `show` 
                SET title=:title, date=:date, description=:description,
                manager_id =:manager_id, enabled=:enabled,
                show_category_id=:show_category_id, max_tickets_per_order=:max_tickets_per_order
                WHERE id=:eventId");
        $sql->bindParam(':eventId',$id);
        $sql->bindParam(':title',$title);
        $sql->bindParam(':date',$date);
        $sql->bindParam(':description',$description);
        $sql->bindParam(':show_category_id',$show_category);
        $sql->bindParam(':enabled', $enabled, PDO::PARAM_INT);
        $sql->bindParam(':manager_id',$manager_id);
        $sql->bindParam(':max_tickets_per_order', $max_tickets_per_order, PDO::PARAM_INT);
        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
    }

    function enabledEventById($id, $enabled) {
        $sql = $this->pdo->prepare("UPDATE `show` SET `enabled`= :enabled WHERE id=:id");
        $sql->bindParam(':id',$id);
        $sql->bindParam(':enabled', $enabled, PDO::PARAM_INT);
        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
    }

    

    function enabledManagerById($id, $enabled) {
        $sql = $this->pdo->prepare("UPDATE `manager` SET `enabled`= :enabled WHERE user_id=:id");
        $sql->bindParam(':id',$id);
        $sql->bindParam(':enabled', $enabled, PDO::PARAM_INT);
        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
    }

    function enabledAdminById($id, $enabled) {
        $sql = $this->pdo->prepare("UPDATE `manager` SET `is_admin`= if(is_admin = 0, 1, 0) WHERE user_id=:id");
        $sql->bindParam(':id',$id);
        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
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
        $sql = $this->pdo->prepare("DELETE FROM `show` WHERE id=:eventId");
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
        $sql = $this->pdo->prepare("insert into image values (:subject_id, :subject, :name, :type, :altText)
                on duplicate key update name = :name, altText = :altText");
        $sql->bindParam(':subject_id', $subject_id);
        $sql->bindParam(':subject', $subject);
        $sql->bindParam(':name', $name);
        $sql->bindParam(':type', $type);
        $sql->bindParam(':altText', $alt);

        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
    }

    function insertUser($id, $email, $password, $salt) {
        $sql = $this->pdo->prepare("INSERT INTO user (id, email, password, salt) values (:id, :email, :password, :salt)");
        $sql->bindParam(':id', $id);
        $sql->bindParam(':email', $email);
        $sql->bindParam(':password', $password);
        $sql->bindParam(':salt', $salt);
        $result = $sql->execute();
    }

    function getManager($userId) {
        $sql = $this->pdo->prepare("SELECT user_id, enabled FROM manager 
        JOIN manager ON manager.user_id = `user`.id
        WHERE user_id = :user_id");
        $sql->bindParam(':user_id', $userId);
        $result = $sql->execute();
        return $sql->fetchAll();
    }

    function getImageById($id) {
        $sql = $this->pdo->prepare("SELECT name FROM `image` WHERE subject_id=:id");
        $sql->bindParam(':id', $id);
        $result = $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    function checkEmail($email) {
        $sql = $this->pdo->prepare("SELECT user.*, manager.is_admin FROM manager
        JOIN user ON user.id = manager.user_id
        WHERE email = :email");
         $sql->bindParam(':email', $email);
         $result = $sql->execute();
         return $sql->fetch();
    }

    function searchVenue($name) {
        global $imgPath;
        $sql = $this->pdo->prepare("SELECT venue.*, CONCAT('$imgPath', `venue`.id, '/', image.type, '/', image.name) AS imageUrl FROM venue
        LEFT JOIN image ON image.subject_id = `venue`.id AND image.subject = 'venue' AND image.type = 'horizontal'
        WHERE venue.name like :name");
        $sql->bindValue(':name', "%" . $name . "%");
        $result = $sql->execute();
         return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function searchArtist($name) {
        global $imgPath;
        $sql = $this->pdo->prepare("SELECT artist.*, CONCAT('$imgPath', `artist`.id, '/', image.type, '/', image.name) AS imageUrl FROM artist
        LEFT JOIN image ON image.subject_id = `artist`.id AND image.subject = 'artist' AND image.type = 'horizontal'
        WHERE artist.name like :name");
        $sql->bindValue(':name', "%" . $name . "%");
        $result = $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    function updateVenue($venue_id, $eventId) {
        $sql = $this->pdo->prepare("UPDATE `show` SET venue_id=:venue_id WHERE id=:id");
        $sql->bindParam(':venue_id', $venue_id);
        $sql->bindParam(':id', $eventId);
        
        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
    }

    function updateArtist($artist_id, $eventId) {
        $sql = $this->pdo->prepare("UPDATE `show` SET artist_id=:artist_id WHERE id=:id");
        $sql->bindParam(':artist_id', $artist_id);
        $sql->bindParam(':id', $eventId);

        if (!$sql->execute()) {
            var_dump($sql->errorInfo());
        }
    }
}

function saveImg($img, $subjectId ,$type) {
    if ($img['error']) {
        return null;
    }

    $imgName = urlencode($img["name"]);
    $destPath = str_replace("/", DIRECTORY_SEPARATOR, "/soldout/data/images/" . $subjectId . "/" . $type . "/");
    
    if (!file_exists($destPath)) {
        mkdir($destPath, 0644, true);
    }

    move_uploaded_file($img["tmp_name"], $destPath . $imgName);

    return $imgName;
}


try {
    // stringa di connessione al DBMS
    //$pdo = new PDO("mysql:host=localhost;dbname=soldout", 'root', '');
    $pdo = new PDO("mysql:host=mattiamari.me;port=13306;dbname=soldout", 'soldout', 'IiseH73LQTyzTknBS03GYw');
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
