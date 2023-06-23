<?php
require('../vendor/autoload.php');
require('../database/database.php');
require('../room/room.php');

use eftec\bladeone\BladeOne;

$views = __DIR__ . '/../views';
$cache = __DIR__ . '/../cache';

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $today =  strtotime((new DateTime())->format('Y-m-d H:i:s'));
    $startDate = $_POST["start"]; 
    $endDate = $_POST["end"];
    
    if (strtotime($startDate) < $today or  strtotime($startDate) > strtotime($endDate)) {
        $rooms_available = [];
    } else {
        $result_bookings = $database->executeQuery('SELECT * FROM bookings ORDER BY `order_date` ASC');
        $bookings = [];
        while($row = $result_bookings->fetch_assoc()){
            $bookings[] = $row;
        }

        foreach($rooms as $index => $room) {
            $room->setRoomBookings($bookings);
        }
        $rooms_available = Room::availableRooms($rooms, $startDate, $endDate);
    }

    echo $blade->run("rooms", ["rooms" => $rooms_available]);

} else  {
    echo $blade->run("rooms", ["rooms" => $rooms]);
}   