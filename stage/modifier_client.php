<?php
$user = 'root';
$pass = '';
$dsn = 'mysql:host=localhost;dbname=gestiondeboutique';

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifiez si le formulaire de modification est soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'modifier') {
        // Récupérez les données du formulaire
        $id_client = $_POST['id_client'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $numero = $_POST['numero'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        $remarque = $_POST['remarque']; // Champ "Remarque"

        // Gérez le téléchargement de la nouvelle image
        $nouvelle_image_defaut = $client_a_modifier['image_defaut']; // Conservez l'image existante par défaut
        if (isset($_FILES['nouvelle_image_defaut']) && $_FILES['nouvelle_image_defaut']['error'] == 0) {
            $nouvelle_image_defaut = file_get_contents($_FILES['nouvelle_image_defaut']['tmp_name']);
        }

        // Mettez à jour les données dans la table clients
        $query = "UPDATE client 
        SET nom = :nom, prenom = :prenom, numero = :numero, email = :email, adresse = :adresse, 
            image_defaut = :nouvelle_image_defaut, 
            remarque = :remarque
        WHERE id_client = :id_client";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':id_client', $id_client);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':nouvelle_image_defaut', $nouvelle_image_defaut, PDO::PARAM_LOB);
        $stmt->bindParam(':remarque', $remarque);
        // Ajoutez les autres bindParam selon vos besoins

        $stmt->execute();

        // Redirigez l'utilisateur vers la liste des clients après la modification
        header("Location: client_list.php");
        exit();
    }

    // Récupérez les informations du client à modifier
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $id_client_a_modifier = $_GET['id'];

        $query_info = "SELECT * FROM client WHERE id_client = :id_client";
        $stmt_info = $dbh->prepare($query_info);
        $stmt_info->bindParam(':id_client', $id_client_a_modifier);
        $stmt_info->execute();
        $client_a_modifier = $stmt_info->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">

    <title>Modifier Client</title>
</head>

<body>

    <div class="content">
        <div class="container">
            <div class="row align-items-stretch no-gutters contact-wrap">
                <div class="col-md-12">
                    <div class="form h-100">
                        <h3>Modifier Client</h3>
                        <form class="mb-5" method="post" id="contactForm" name="contactForm"
                            enctype="multipart/form-data">

                            <input type="hidden" name="id_client" value="<?php echo $id_client_a_modifier; ?>">
                            <input type="hidden" name="action" value="modifier">

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">Nom </label>
                                    <input type="text" class="form-control" name="nom" id="nom"
                                        value="<?php echo $client_a_modifier['nom']; ?>">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">Prenom </label>
                                    <input type="text" class="form-control" name="prenom" id="prenom"
                                        value="<?php echo $client_a_modifier['prenom']; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">Numero </label>
                                    <input type="text" class="form-control" name="numero" id="numero"
                                        value="<?php echo $client_a_modifier['numero']; ?>">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">E-mail </label>
                                    <input type="text" class="form-control" name="email" id="email"
                                        value="<?php echo $client_a_modifier['email']; ?>">
                                </div>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="" class="col-form-label">Adresse </label>
                                <input type="text" class="form-control" name="adresse" id="adresse"
                                    value="<?php echo $client_a_modifier['adresse']; ?>">
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="nouvelle_image_defaut" class="col-form-label">Nouvelle Image Défaut</label>
                                <input type="file" class="form-control-file" name="nouvelle_image_defaut"
                                    id="nouvelle_image_defaut">
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="remarque" class="col-form-label">Remarque</label>
                                <textarea class="form-control" name="remarque" id="remarque"
                                    rows="5"><?php echo $client_a_modifier['remarque']; ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input type="submit" value="Modifier" class="btn btn-primary rounded-0 py-2 px-4">
                                    <span class="submitting"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/main.js"></script>

</body>

</html>
