<?php 

require_once __DIR__. "/app/autoload.php";

use Genius\User;

$user = new User();
$user->first_name = "Setha";
$user->last_name = "Keawmok";
$user->email = "setha@gmail.com";

$user->loginprocess();