<?php 

require_once __DIR__. "/app/autoload.php";

use Genius\Connectdb;
use Genius\User;

$dbconn = new Connectdb();
$conn = $dbconn->getConnect();

$user = new User();
$user->first_name = "Setha";
$user->last_name = "Keawmok";
$user->email = "setha@gmail.com";

//$user->loginprocess();

//Insert

$username = "setha";
$password = "123456";
$fullname = "setha keawmok";
$email = "samit@gmail.com"; 
$tel = "0885551682";
$status = "1";

$sql = "INSERT INTO users(username,password,fullname,email,tel,status) VALUES (:username,:password,:fullname,:email,:tel,:status)";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':username',$username);
$stmt->bindParam(':password',$password);
$stmt->bindParam(':fullname',$fullname);
$stmt->bindParam(':email',$email);
$stmt->bindParam(':tel',$tel);
$stmt->bindParam(':status',$status);

$stmt->execute();


