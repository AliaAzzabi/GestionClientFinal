<?php
// Connexion à la base de données 
$user = 'root';
$pass = '';
$dsn = 'mysql:host=localhost;dbname=gestiondeboutique';

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Vérifiez si le formulaire a été soumis
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Récupérez les données du formulaire
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $numero = $_POST['numero'];
        $email = $_POST['email'];
        $adresse = $_POST['adresse'];
        $remarque = $_POST['remarque']; // Champ "Remarque"
        // Ajoutez les autres champs ici

        // Gérez le téléchargement de l'image
        $image_defaut = ''; // Définissez une valeur par défaut
        if (isset($_FILES['image_defaut']) && $_FILES['image_defaut']['error'] == 0) {
            $image_defaut = file_get_contents($_FILES['image_defaut']['tmp_name']);
        }

        // Insérez les données dans la table clients
        $query = "INSERT INTO client (nom, prenom, numero, email, adresse, image_defaut, remarque) 
                  VALUES (:nom, :prenom, :numero, :email, :adresse, :image_defaut, :remarque)";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':numero', $numero);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':image_defaut', $image_defaut, PDO::PARAM_LOB); // Utilisation de PDO::PARAM_LOB pour les données binaires (image)
        $stmt->bindParam(':remarque', $remarque);


        $stmt->execute();


        header("Location: client_list.php");
        exit();
    }
} catch (PDOException $e) {
    echo 'Erreur de connexion à la base de données : ' . $e->getMessage();
}
?>


<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">

    <title>Ajouter Client</title>
</head>

<body>


    <div class="content">

        <div class="container">
            <div class="row align-items-stretch no-gutters contact-wrap">
                <div class="col-md-12">
                    <div class="form h-100">
                        <h3>Ajouter Client</h3>
                        <form class="mb-5" method="post" id="contactForm" name="contactForm"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">Nom </label>
                                    <input type="text" class="form-control" name="nom" id="nom" placeholder="Votre nom">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">Prenom </label>
                                    <input type="text" class="form-control" name="prenom" id="prenom"
                                        placeholder="Votre prenom">
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">Numero </label>
                                    <input type="text" class="form-control" name="numero" id="numero"
                                        placeholder="Votre numero">
                                </div>
                                <div class="col-md-6 form-group mb-3">
                                    <label for="" class="col-form-label">E-mail </label>
                                    <input type="text" class="form-control" name="email" id="email"
                                        placeholder="Votre email">
                                </div>
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="" class="col-form-label">Adresse </label>
                                <input type="text" class="form-control" name="adresse" id="adresse"
                                    placeholder="Votre adresse">
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="image_defaut" class="col-form-label">Image Défaut</label>
                                <input type="file" class="form-control-file" name="image_defaut" id="image_defaut">
                            </div>
                            <div class="col-md-12 form-group mb-3">
                                <label for="remarque" class="col-form-label">Remarque </label>
                                <textarea class="form-control" name="remarque" id="remarque"
                                    placeholder="Votre remarque" rows="5"></textarea>
                            </div>


                            <div class="row">
                                <div class="col-md-12 form-group">
                                    <input type="submit" value="Enregistrer"
                                        class="btn btn-primary rounded-0 py-2 px-4">
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