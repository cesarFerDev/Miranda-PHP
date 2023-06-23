<?php

class Room {

    public $id;
    public $type;
    public $number;
    public $price;
    public $discount;
    public $cancellation;
    public $description;
    public $amenities;
    public $photos;
    public $is_available;
    public $bookings;

    function __construct($room) {
        $this->id = $room["id"];
        $this->type = $room["type"];
        $this->number = $room["number"];
        $this->price = $room["price"];
        $this->discount = $room["discount"];
        $this->cancellation = $room["cancellation"];
        $this->description = $room["description"];
        $this->amenities = $room["amenities"];
        $this->photos = $room["photos"];
        $this->is_available = $room["is_available"];
        $this->bookings = [];
    }
    
    function setRoomBookings($bookings) {
        $aux = [];
        foreach ($bookings as $index => $booking) {
            if ($booking["room_id"] == $this->id) {
                $aux[] = $booking;
            }
        }
        $this->bookings = $aux;
    }

    function isOccupied($date) {
        $datetime = strtotime($date->format('Y-m-d H:i:s'));
        foreach ($this->bookings as $index => $booking) {
            $room_candidate = $booking["room_id"];
            $check_in = strtotime($booking["check_in"]);
            $check_out = strtotime($booking["check_out"]);
            
            if ($this->id == $room_candidate and ($check_in <= $datetime and $check_out >= $datetime)) {
                return true;
            }
        }
        return false;
    }

    function occupancyPercentage($startDate, $endDate) {
        $auxDate = date_create($startDate);
        $rawInterval = strtotime($endDate) - strtotime($startDate);
        
        if ($rawInterval < 1) {
            return 0;
        }
        $daysInterval = $rawInterval / (60 * 60 * 24);
        $daysOccupied = 0;

        for ($i = 0; $i < $daysInterval; $i++) {
            if ($this->isOccupied($auxDate)) {
                $daysOccupied++;
            }
            $auxDate->modify('+1 day');
        }
        return round(($daysOccupied / $daysInterval) * 100);
    }

    static function availableRooms($rooms, $startDate, $endDate) {
        $roomsAvailable = [];
        foreach($rooms as $index => $room) {
            if ($room->occupancyPercentage($startDate, $endDate) != 0) {
                $roomsAvailable[] = $room;
            }
        }
        return $roomsAvailable;
        }
};