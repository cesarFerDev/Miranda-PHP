<?php
require('../vendor/autoload.php');
require('../database/database.php');
require('../room/room.php');

use eftec\bladeone\BladeOne;

$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';

$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

$database = new Database();
$result_offer = $database->executeQuery('SELECT * FROM rooms WHERE discount > 0 ORDER BY `number` ASC');
$rooms_offer = [];

while($row = $result_offer->fetch_assoc()) {
    $row['photos'] = json_decode($row['photos']);
    $row['amenities'] = json_decode($row['amenities']);
    $room = new Room($row);
    $rooms_offer[] = $room;
}

$result_popular = $database->executeQuery('SELECT * FROM rooms WHERE discount = 0 ORDER BY `number` ASC LIMIT 3');
$rooms_popular = [];

while($row = $result_popular->fetch_assoc()) {
    $row['photos'] = json_decode($row['photos']);
    $row['amenities'] = json_decode($row['amenities']);
    $room = new Room($row);
    $rooms_popular[] = $room;
}

echo $blade->run("offers", ["rooms_offer" => $rooms_offer, "rooms_popular" => $rooms_popular]);