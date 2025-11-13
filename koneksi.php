<?php 
try {
    $connection = new mysqli("localhost", "root", "", "barber");
} catch (Exception $e) {
    echo "Gagal Connect";
}

?>