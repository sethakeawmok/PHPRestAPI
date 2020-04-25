<?php 

require_once __DIR__. "/app/autoload.php";

use Genius\Connectdb; 

$dbconn = new Connectdb();
$conn = $dbconn->getConnect();

$sql = "SELECT * FROM users ORDER BY id DESC";
$stmt = $conn->prepare($sql); 

$stmt->execute();

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
//print_r($result); 
echo json_encode($result); 