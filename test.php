<div class="container-fluid">
    <div class="row">
        <?php
        include("head.php");
        function affiche_articles()
        {
            ?>
            <div class="affichage">
                <?php
                $database = new PDO('mysql:host=localhost; dbname=wiki_scp; charset=utf8', 'root', '');
                $state = $database->prepare("SELECT * FROM article ORDER BY creation DESC");
                $state->execute();
                $resultsarts = $state->fetchAll();

                $state2 = $database->prepare("SELECT * FROM Utilisateur WHERE idUtilisateur = ?");


                foreach ($resultsarts as $key) {
                    $state2->execute(array($key["idCreateur"]));
                    $rlcs = $state2->fetch();
                    echo "<div>";
                    if ($key["visible"] === 1) {
                        echo $key["titre"] . "<br>";
                        echo $key["contenu"] . "<br>";
                        echo "cree le " . $key["creation"] . " par " . $rlcs["nom"] . " " . $rlcs["prenom"] . "  <br>";

                        if (!empty($_SESSION["now_id"])) {
                            $state3 = $database->prepare("SELECT admin FROM Utilisateur WHERE idUtilisateur = ?");
                            $state3->execute(array($_SESSION["now_id"]));
                            $resultadmin = $state3->fetch();
                            if ($resultadmin["admin"] === 1) {
                                print "<a href='index.php?suppr=" . $key["idArticle"] . "'><input type='button' value='supprimer'></a><br>";
                            }
                        }
                    }
                    echo "</div> <br>";
                }
                ?>
                <br>
            </div>
            <?php
        }
        ?>

        <body style="background-color: grey;">
            <div class="imageBack">
                <?php
                $_SESSION["user_id"] = null;
                if (!empty($_GET['suppr'])) {
                    $artsprotect = htmlspecialchars($_GET['suppr'], ENT_QUOTES);
                    $state = $database->prepare("UPDATE Article SET visible= 0 WHERE idArticle= ?");
                    $state->execute(array($artsprotect));
                    header("Location: index.php");
                    exit();
                }
                if (!empty($_GET['connex'])) {
                    $userprotect = htmlspecialchars($_GET['connex'], ENT_QUOTES);

                    // inscription
                    if ($userprotect == 1) {
                        ?>
                        <div class="Background">
                            <div class="Border">
                                <div class="LoginBox">

                                    <form action='index.php?connex=1' method='post'>
                                        <label for='name'>nom: </label><br>
                                        <input type='text' name='nom'> <br>

                                        <label for='prenom'>prenom: </label><br>
                                        <input type='text' name='prenom'> <br>

                                        <label for='email'>email: </label><br>
                                        <input type='text' name='email'><br>

                                        <label for='name'>mot de passe: </label><br>
                                        <input type='password' name='mdp'><br>

                                        <input type='submit' value='Inscription' />
                                        <a href='index.php'><input type='button' value='Retour' /></a>
                                    </form>

                                    <?php
                                    if (!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['email']) && !empty($_POST['mdp'])) {
                                        $recupname = htmlspecialchars($_POST['nom'], ENT_QUOTES);
                                        $recupprename = htmlspecialchars($_POST['prenom'], ENT_QUOTES);
                                        $recupmail = htmlspecialchars($_POST['email'], ENT_QUOTES);
                                        $recupmdp = htmlspecialchars($_POST['mdp'], ENT_QUOTES);

                                        $state = $database->prepare("SELECT * FROM utilisateur WHERE email=(?)");
                                        $state->execute(array($recupmail));
                                        $results = $state->fetchall();
                                        if (sizeof($results) === 0) {
                                            $state = $database->prepare("INSERT INTO utilisateur (nom, prenom, email, mot_de_passe) VALUES (?, ?, ?, ?)");
                                            $state->execute(array($recupname, $recupprename, $recupmail, $recupmdp));
                                            echo "Merci pour votre inscription";
                                        } else {
                                            echo "Un compte est déjà utilisé avec ses informations<br>";
                                        }
                                    }
                                    ?>
                                </div>
                            </div>

                            <?php
                        // connexion
                    } elseif ($userprotect == 2) {
                        ?>

                            <div class="Background">
                                <div class="Border">
                                    <div class="LoginBox">

                                        <form action='index.php?connex=2' method='post'>
                                            <label for='name'>email: </label><br>
                                            <input type='email' value='testmail@dev.com' name='email'> <br>

                                            <label for='name'>mot de passe: </label><br>
                                            <input type='password' value='mytest' name='mdp'><br>

                                            <input type='submit' value='Connexion' />
                                            <a href='admin.php?deco=true'><input type='button' value='Retour' /></a>
                                        </form>

                                        <?php
                                        if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
                                            $mail = htmlspecialchars($_POST['email'], ENT_QUOTES);
                                            $mdp = htmlspecialchars($_POST['mdp'], ENT_QUOTES);
                                            $state = $database->prepare("SELECT * FROM Utilisateur WHERE email= (?) and mot_de_passe= (?)");
                                            $state->execute(array($mail, $mdp));
                                            $resultats = $state->fetchAll();
                                            if (sizeof($resultats) === 1) {
                                                //var_dump($resultats);
                                                echo "<br>connexion réussi";
                                                foreach ($resultats as $key) {
                                                    $_SESSION["user_id"] = $key["idUtilisateur"];
                                                    $_SESSION["now_id"] = $key["idUtilisateur"];
                                                    $isadmin = $key["admin"];
                                                    $isban = $key["idBan"];
                                                    echo "<br>" . $_SESSION["user_id"] . " et " . $isadmin;
                                                    if ($isban == 1) {
                                                        $_SESSION["inv"] = 0;
                                                        session_unset();
                                                        session_destroy();
                                                        header('Location: index.php');
                                                        exit();
                                                    }
                                                }
                                                $_SESSION["inv"] = 1;
                                                header('Location: index.php');
                                                exit();
                                            }
                                        }

                    }
                    ?>
                                </div>
                            </div>
                            <?php
                } else {
                    if (empty($_SESSION["inv"])) {
                        include("recherche.php");

                        ?>
                        <div class="flex-container">
                        <div class="Container">
                                    <div class="image">
                                        <img src="image/User.png" alt="Avatar" class="avatar">
                                        <div class='buttons' style="display :flex">
                                            <a href='?connex=2'><input type='button' value='Connexion' /></a>
                                            <a href='?connex=1'><input type='button' value='Inscription' /></a>
                                        </div>
                                    </div>
                                </div>
                            <div class="flex-items"></div>
                            </div>
                               

                                <?php
                                affiche_articles();
                    } else {
                        include("recherche.php");
                        $state = $database->prepare("SELECT avatar FROM utilisateur WHERE idUtilisateur= (?)");
                        $state->execute(array($_SESSION["now_id"]));
                        $resultavatar = $state->fetch();
                        ?>  
                        
                                <div class="Container">
                                    <div class="image">
                                        <img src="<?php echo $resultavatar["avatar"] ?>" alt="Avatar" class="avatar">

                                        <div class='buttons'>
                                            <a href="viewprofile.php"><input type="button" value="Mon Profil"></a>
                                            <a href='admin.php?deco=true'><input type='button' value='Deconnexion' /></a>
                                            <?php
                                            $state = $database->prepare("SELECT admin FROM Utilisateur WHERE idUtilisateur= (?)");
                                            $state->execute(array($_SESSION["now_id"]));
                                            $result = $state->fetch();
                                            if ($result["admin"] == 1) {
                                                ?>
                                                <a href='admin.php'><input type='button' value='adminstration' /></a>
                                                <?php
                                            }

                                            ?>
                                        </div>
                                        <div class="col-8">
                                            <div class="Box">
                                                <div class="Editeur" style="background-color: white;">
                                                    <form method="post">
                                                        <input id="x" type="hidden" name="content" value="" />
                                                        <trix-editor input="x"></trix-editor>
                                                        <input type="submit" name="submit" value="Submit" />
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>



                                <?php
                                affiche_articles();
                    }
                }
                ?>
                    </div>
                </div>
            </div>
        </body>

        </html>