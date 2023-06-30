<?php
require('../vendor/autoload.php');
require('../room/room.php');
require('../database/database.php');
require('../aux_functions/functions.php');

use eftec\bladeone\BladeOne;

$views = '../views';
$cache = '../cache';

$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

$error = "";
$posted = false;

$id = $_GET['id'];
$database = new Database();
$result = $database->executeQuery("SELECT * FROM rooms WHERE id = '$id'");
$room = $result->fetch_array();
$room['photos'] = json_decode($room['photos']);
$roomDetailObject = new Room($room);

$type = $room["type"];
$result = $database->executeQuery("SELECT * FROM rooms WHERE `type` = '$type' AND id != '$id' ORDER BY `number` ASC LIMIT 2");
$rooms_related = [];

while($row = $result->fetch_assoc()){
    $row['photos'] = json_decode($row['photos']);
    $roomObject = new Room($row);
    $rooms_related[] = $roomObject;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ((!empty($_POST['name'])) and
       (!empty($_POST['contact']) and filter_var($_POST['contact'], FILTER_VALIDATE_INT)) and 
       (!empty($_POST['email']))) {
            
            $today =  (new DateTime())->format('Y-m-d H:i:s');
            $check_in = date('Y-m-d H:i:s', (strtotime($_POST["check-in"]))); 
            $check_out = date('Y-m-d H:i:s', (strtotime($_POST["check-out"]))); 

            if (strtotime($check_in) < strtotime($today) or  strtotime($check_in) > strtotime($check_out)) {
                $error = "Invalid Date inputs";
            } else {
                global $id;
                global $database;
                $booking_id = uniqidReal(24);
                $name = trim(htmlspecialchars($_POST["name"]));
                $contact = trim(htmlspecialchars($_POST["contact"]));
                $email = trim(htmlspecialchars($_POST["email"]));
                $request = trim(htmlspecialchars($_POST["special-request"]));
                $room_id = $id;
                $status = "Check In";

                $database->executeQuery("INSERT INTO bookings (id, guest_name, guest_email, guest_contact, order_date, check_in, check_out, special_request, room_id, `status`)
                                        VALUES ('$booking_id', '$name', '$email', '$contact', '$today', '$check_in', '$check_out', '$request', '$room_id', '$status')");
                $posted = true;
            }
    } else {
        $error = "Invalid inputs";
    }
}

echo $blade->run("room-details", ["room" => $roomDetailObject, "rooms_related" => $rooms_related, "error" => $error, "posted" => $posted]);