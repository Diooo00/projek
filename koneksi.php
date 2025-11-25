<?php 
$host = "localhost";
$port = "3306";
$user = "root";
$pass = ""; 
$database = "barber";

$connection = mysqli_connect($host, $user, $pass, $database, $port);

try{
    $connection = new mysqli($host, $user, $pass, $database, $port);
}catch(Exception $e){
    echo "Koneksi Gagal: " . $e->getMessage();
}
?>