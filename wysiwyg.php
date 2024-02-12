<!DOCTYPE html>
<html>

<head>

    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="./css/wys.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js" integrity="sha384-qKXV1j0HvMUeCBQ+QVp7JcfGl760yU08IQ+GpUo5hlbpg51QRiuqHAJz8+BrxE/N" crossorigin="anonymous"></script>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="js/tinymce.min.js" type="text/javascript"></script>
    <link rel="icon" type="image/x-icon" href="image/icon.png">

</head>

<body>
    <?php
    include("head.php");
    ?>

    <br>
    <div class="container">
        <div class="row text-center" id="centre">
            <div class="col-12" id="titre">
                <a href="index.php" class="retour">&laquo; Retour</a>
            </div>
        </div>

        <br>

        <div class="row text-center" id="centre">
            <div class="col-12" id="titre">
                <p>Titre de votre article :</p>
            </div>
        </div>
        <form method="POST" action="wysiwyg.php">

            <div class="row text-center">
                <div class="col-12">
                    <input type="text" id="titre2" name="titre">
                </div>
            </div>


            <br>

            <div class="row text-center" id="centre">
                <div class="col-12" id="titre">
                    <label for="classe">Classe de votre article :</label>
                </div>
            </div>
            <br>
            <div class="row text-center">
                <div class="col-12">
                    <select class="select" id="classe" name="classe">
                        <option value="Inconnu">Inconnu</option>
                        <option value="Safe">Safe</option>
                        <option value="Euclid">Euclid</option>
                        <option value="Ketter">Ketter</option>
                        <option value="Thaumiel">Thaumiel</option>
                        <option value="Neutralisé">Neutralisé</option>
                        <option value="Expliqué">Expliqué</option>
                        <option value="Esotériques">Esotériques</option>
                    </select>
                </div>
            </div>

            <br>
            <br>

            <div class="row">
                <div class="col-12" id="wysiwyg">
                    <!---<div style="width:800px;margin:auto;">--->
                    <textarea id="contenu" name="contenu" placeholder="Ecrivez votre article ici..."> </textarea><br>
                </div>
            </div>
            <div class="row text-center">
                <div class="col-12" id="but">
                    <input id="button" type="submit" name="Envoyer" value="Envoyer"></a>
                </div>
            </div>
        </form>
        <script type="text/javascript" language="javascript">
            tinymce.init({
                selector: "textarea",
                menubar: false,
                statusbar: false,
            });
        </script>
        <br>
        <?php


        //Récupérer les données
        if (isset($_POST["titre"]) && isset($_POST["contenu"])) {
            if ($_POST["titre"] != null && $_POST["contenu"] != null) {
                $contenu = htmlspecialchars($_POST["contenu"], ENT_QUOTES);
                $titre = htmlspecialchars($_POST["titre"], ENT_QUOTES);
                $classe = htmlspecialchars($_POST["classe"], ENT_QUOTES);

                $state2 = $database->prepare('INSERT INTO article (idCreateur, titre, classe, contenu) VALUES (:idCreateur, :titre, :classe, :contenu)');

                $createur = $database->prepare("SELECT * FROM Utilisateur");
                $createur->execute();
                $resultats = $createur->fetchAll();

                foreach ($resultats as $act) {
                    $_SESSION["user_id"] = $act["idUtilisateur"];
                }

                $state2->bindParam(':idCreateur', $_SESSION['user_id']);
                $state2->bindParam(':titre', $titre);
                $state2->bindParam(':classe', $classe);
                $state2->bindParam(':contenu', $contenu);
                $state2->execute();

        ?>
                <script language="Javascript">
                    alert("Ta fiche à bien été envoyé !");
                    location.href = "index.php";
                </script>

            <?php

            } else {
            ?>
                <div class="champs">
                    <p>Les champs ne sont pas tous remplis !</p>
                </div>
        <?php
            }
        }

        ?>
    </div>
    </div>

</body>

</html>