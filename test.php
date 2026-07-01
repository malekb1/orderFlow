<?php
$conn = mysqli_connect("127.0.0.1", "root", "", "orderflow", 3307);

if (!$conn) {
    die("Erreur connexion : " . mysqli_connect_error());
}

echo "Connexion OK";
?>