<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'soupe');
 
/* Connexion à la base MySQL */
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Vérification de la connexion
if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>