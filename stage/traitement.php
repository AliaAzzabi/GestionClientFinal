<?php
$user = 'root';
$pass = '';
$dsn = 'mysql:host=localhost;dbname=gestiondeboutique';

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       
        $id_client = isset($_POST['id_client']) ? $_POST['id_client'] : null;

        // Récupérez les données du formulaire
        $remarque = isset($_POST['remarque']) ? $_POST['remarque'] : null;
        $solde = isset($_POST['solde']) ? $_POST['solde'] : null;

        // Vérifiez si la clé "nouvelle_image" existe dans $_FILES et si une nouvelle image a été téléchargée
        $nouvelle_image = null;
        if (isset($_FILES['nouvelle_image']) && $_FILES['nouvelle_image']['error'] === 0) {
            $nouvelle_image = file_get_contents($_FILES['nouvelle_image']['tmp_name']);
        }

        // Enregistrez les données dans la base de données
        $query_update = "UPDATE client SET remarque = :remarque, solde = :solde";
        if ($nouvelle_image !== null) {
            $query_update .= ", image_defaut = :nouvelle_image";
        }
        $query_update .= " WHERE id_client = :id_client";

        $stmt_update = $dbh->prepare($query_update);
        $stmt_update->bindParam(':remarque', $remarque);
        $stmt_update->bindParam(':solde', $solde);
        if ($nouvelle_image !== null) {
            $stmt_update->bindParam(':nouvelle_image', $nouvelle_image, PDO::PARAM_LOB);
        }
        $stmt_update->bindParam(':id_client', $id_client);
     
        $stmt_update->execute();

        header("Location: details_client.php?id=$id_client");
        exit();
    }
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}

?>
