<?php
require('../vendor/autoload.php');
require('../database/database.php');
require('../aux_functions/functions.php');

use eftec\bladeone\BladeOne;

$views = '../views';
$cache = '../cache';

$blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);

$error = "";
$posted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ((!empty($_POST['name'])) and
       (!empty($_POST['contact']) and filter_var($_POST['contact'], FILTER_VALIDATE_INT)) and 
       (!empty($_POST['email'])) and 
       (!empty($_POST['title'])) and 
       (!empty($_POST['content']))) {
            $name = trim(htmlspecialchars($_POST["name"]));
            $contact = trim(htmlspecialchars($_POST["contact"]));
            $email = trim(htmlspecialchars($_POST["email"]));
            $title = trim(htmlspecialchars($_POST["title"]));
            $content = trim(htmlspecialchars($_POST["content"]));
            $id = uniqidReal(24);

            $today =  (new DateTime())->format('Y-m-d H:i:s');
            $database = new Database();
            $database->executeQuery("INSERT INTO contacts (id, contact_date, guest_name, guest_email, guest_contact, content_title, content_text)
                                    VALUES ('$id', '$today', '$name', '$email', '$contact', '$title', '$content')");

            $posted = true;
        
    } else {
        $error = "Invalid inputs";
    }
}

echo $blade->run("contact", ["error" => $error, "posted" => $posted]);