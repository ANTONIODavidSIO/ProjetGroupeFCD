<!doctype html>
<html lang="fr">

<body style="background-color: black; color:white;">


    <div class="Accueil">
        <div class="container-fluid">
            <div class="row">
                <?php
                include("head.php");
                $_SESSION["visible"] = 0;
                if (!empty($_SESSION["now_id"])) {
                    if (!empty($_GET["modifmdp"])) {
                        $modifprotect = htmlspecialchars($_GET["modifmdp"], ENT_QUOTES);
                        if ($modifprotect === "true") {
                            $_SESSION["visible"] = 11;
                            ?>
                            <div class="UserInfo">
                                <form action="" method="post">
                                    <label for="">ancien mot de passe:</label><br>
                                    <input type="text" name="oldmdp"><br>

                                    <label for="">nouveau mot de passe:</label><br>
                                    <input type="password" name="newmdp"><br>

                                    <input type='submit' value='Confirmer' />
                                    <a href='viewprofile.php'><input type='button' value='Retour' /></a><br>
                                </form>
                            </div>
                            <?php
                            if (!empty($_POST["oldmdp"]) && !empty($_POST["newmdp"])) {
                                $oldmdp = htmlspecialchars($_POST["oldmdp"], ENT_QUOTES);
                                $newmdp = htmlspecialchars($_POST["newmdp"], ENT_QUOTES);
                                $state = $database->prepare("UPDATE Utilisateur set mot_de_passe= SHA2(?, 256) WHERE mot_de_passe= SHA2(?, 256) AND idUtilisateur=?");
                                $state->execute(array($newmdp, $oldmdp, $_SESSION["now_id"]));
                                $result = $state->fetch();
                                echo "Nouveau mot de passe changé";
                                header("Location: viewprofile.php");
                                exit();
                            }

                        }
                    } elseif (!empty($_GET["modifimg"])) {
                        $modifimg = htmlspecialchars($_GET["modifimg"], ENT_QUOTES);
                        if ($modifimg === "true") {
                            $targetpath = $_SESSION["src_img"] . $_FILES["fileToUpload"]["name"];
                            move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetpath);
                            $state = $database->prepare("UPDATE Utilisateur SET avatar = ? WHERE idUtilisateur = ?");
                            $state->execute(array($targetpath, $_SESSION["now_id"]));
                            header("Location: viewprofile.php");
                            exit();
                        }

                    }


                    if (empty($_SESSION["visible"])) {
                        $state = $database->prepare("SELECT * FROM utilisateur WHERE idUtilisateur= (?)");
                        $state->execute(array($_SESSION["now_id"]));
                        $resultats = $state->fetchall();
                        foreach ($resultats as $key) {
                            ?>
                            <div class="UserInfo">

                                <div class="Info">
                                    <a id="button-return" href='index.php'><input type='button' value='Retour' /></a>
                                    <label for="text">Vos informations</label><br>
                                    <label for='name'>nom: </label>
                                    <?php echo $key["nom"]; ?><br>

                                    <label for='prenom'>prenom: </label>
                                    <?php echo $key["prenom"]; ?><br>

                                    <label for='email'>email: </label>
                                    <?php echo $key["email"]; ?><br>

                                    <label for='date'>inscrit depuis le: </label>
                                    <?php echo $key["date_inscription"] . " à " . $key["heure_inscription"]; ?><br>

                                    <label for='date'>mot de passe: </label>
                                    | <a href="?modifmdp=true"><input type="button" value="modifier"></a><br>

                                </div>

                                <div class="User">
                                    <?php $state = $database->prepare("SELECT avatar FROM utilisateur WHERE idUtilisateur= (?)");
                                    $state->execute(array($_SESSION["now_id"]));
                                    $result = $state->fetch(); ?>
                                    <img src="<?php echo $result["avatar"] ?>" alt>
                                    <form action="?modifimg=true" method="POST" enctype="multipart/form-data">
                                        <input type="file" name="fileToUpload"><br>
                                        <input type="submit" value="modifier">
                                    </form>
                                </div>

                            </div>
                        </div>

                        <?php
                        }
                    }
                } else {
                    session_unset();
                    session_destroy();
                    header('Location: index.php');
                    exit();
                }

                ?>
        </div>
    </div>


</body>

</html>