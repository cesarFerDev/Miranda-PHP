<?php
require('../vendor/autoload.php');
require('../database/database.php');
require('../room/room.php');

use eftec\bladeone\BladeOne;

$views = '../views';
$cache = '../cache';

$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

$database = new Database();
$result = $database->executeQuery('SELECT * FROM rooms ORDER BY `number` ASC');
$rooms = [];

while($row = $result->fetch_assoc()) {
    $row['photos'] = json_decode($row['photos']);
    $row['amenities'] = json_decode($row['amenities']);
    $room = new Room($row);
    $rooms[] = $room;
}

echo $blade->run("index", ["rooms" => $rooms]);