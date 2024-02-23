<?php
$user = 'root';
$pass = '';
$dsn = 'mysql:host=localhost;dbname=gestiondeboutique';

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmed']) && $_POST['confirmed'] == 1) {
        // Si le formulaire de confirmation de suppression est soumis
        $id_client = $_POST['id'];

        // Supprimez le client de la base de données
        $queryDelete = "DELETE FROM client WHERE id_client = :id_client";
        $stmtDelete = $dbh->prepare($queryDelete);
        $stmtDelete->bindParam(':id_client', $id_client);
        $stmtDelete->execute();

        // Redirigez l'utilisateur vers la liste des clients après la suppression
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap.min.css">
    <title>Liste des Clients</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>

<body>
    <h1 class="text-center">Liste des Clients</h1>
    <div>
        <a href="ajouter_client.php">
            <button type="button" class="btn btn-primary">Ajouter Client</button>
        </a>
    </div>
    <table class="table table-light table-hover ">
        <thead class="table-dark">
            <tr>
                <th>ID Client</th>
                <th>Nom</th>
                <th>Prenom</th>
                <th>Numéro</th>
                <th>Email</th>
                <th>Adresse</th>
                <th>Image de défaut</th>
                <th>Remarque</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php
            try {
                $dbh = new PDO($dsn, $user, $pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // Sélection des données de la table clients
                $query = "SELECT id_client, nom, prenom, numero, email, adresse, remarque, image_defaut FROM client";
                $stmt = $dbh->prepare($query);
                $stmt->execute();
                $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Affichage des données dans un tableau
                foreach ($clients as $client) {
                    // Extraction de la première phrase jusqu'au premier point
                    $position = strpos($client['remarque'], '.');
                    $remarque = substr($client['remarque'], 0, $position + 1);

                    echo '<tr >';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . $client['id_client'] . '</td>';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . $client['nom'] . '</td>';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . $client['prenom'] . '</td>';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . $client['numero'] . '</td>';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . $client['email'] . '</td>';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . $client['adresse'] . '</td>';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . '<img src="data:image/jpeg;base64,' . base64_encode($client['image_defaut']) . '" alt="Imagewidth="70" height="70">' . '</td>';
                    echo '<td onclick="window.location=\'detail_client.php?id=' . $client['id_client'] . '\';" style="cursor:pointer;">' . $remarque . '</td>';
                    echo '<td>';
                    echo '<form method="get" action="modifier_client.php">';
                    echo '<input type="hidden" name="id" value="' . $client['id_client'] . '">';
                    echo '<button type="submit" class="btn btn-warning btn-block">Modifier</button>';
                    echo '</form>';
                    // Formulaire de confirmation de suppression
                    echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '" onsubmit="return confirm(\'Voulez-vous vraiment supprimer ce client ?\');">';
                    echo '<input type="hidden" name="confirmed" value="1">';
                    echo '<input type="hidden" name="id" value="' . $client['id_client'] . '">';
                    echo '<button type="submit" class="btn btn-danger btn-block">Supprimer</button>';
                    echo '</form>';


                    echo '</td>';
                    echo '</tr>';

                }
            } catch (PDOException $e) {
                echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
            }
            ?>

        </tbody>
    </table>

</body>

</html>
