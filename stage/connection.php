<?php
$user = 'root';
$pass = '';
$dsn = 'mysql:host=localhost;dbname=gestiondeboutique';
try {
$dbh = new PDO($dsn, $user, $pass);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
print "Connexion réussie.";
} 
catch (PDOException $e) { 
print "Erreur ! : " . $e->getMessage() . "<br/>"; die();
}

?>
