
<div class="imageBackBis">
    <div class="historique">
        <div class="container-fluid">
            <?php
            include("head.php");
            if (!empty($_GET["getmodifs"])) {
                $getmodifart = htmlspecialchars($_GET["getmodifs"], ENT_QUOTES);
                $state = $database->prepare("SELECT * FROM modifications where num_article= ? order by date_modif desc");
                $state->execute(array($getmodifart));
                $rls = $state->fetchall();

                $state2 = $database->prepare("SELECT * FROM Utilisateur where idUtilisateur= ?");
                echo "voici l'historique des modifications pour l'article actuel<br>";
                if (count($rls) == 0) {
                    echo "Cet Article n'a jamais subi de modification<br>";
                }
                foreach ($rls as $key) {
                    $state2->execute(array($key["id_user"]));
                    $resultat = $state2->fetch();
                    echo "<br><strong>" . $key["titre"] . "</strong><br>";
                    echo "Classe: " . $key["classe"] . "<br>";
                    echo $key["contenu"] . "<br>";
                    echo "modifier le " . $key["date_modif"] . " par " . $resultat["nom"] . " " . $resultat["prenom"] . " <br>";
                }
                print "<a href='index.php'><input type='button' value='Retour'></a>";
            } elseif (!empty($_GET["setmodifs"])) {
                print "<form action='?upmodif=" . $_GET["setmodifs"] . "' method='post'>";
                $state = $database->prepare("SELECT * from Article where idArticle= ?");
                $state->execute(array($_GET["setmodifs"]));
                $affart = $state->fetch();
                ?>

                <label for="titre">Nouveau Titre: </label><br>
                <input type="text" name="titre" id="titre" value="<?php echo $affart["titre"]; ?>"><br>
                <label for="classe">Nouvelle Classe: </label><br>

                <select class="select" id="classe" name="classe">
                    <option value="Inconnu">Inconnu</option>
                    <option value="Safe">Safe</option>
                    <option value="Euclid">Euclid</option>
                    <option value="Ketter">Ketter</option>
                    <option value="Thaumiel">Thaumiel</option>
                    <option value="Neutralisé">Neutralisé</option>
                    <option value="Expliqué">Expliqué</option>
                    <option value="Esotériques">Esotériques</option>
                </select><br>

                <label for="content">Nouveau contenu: </label><br>
                <input typr="textarea" name="contenu" value="<?php echo $affart["contenu"]; ?>"><br>
                <a href='index.php'><input type='button' value='Retour'></a>
                <input type="submit" value="modifier">
                </form>

                <?php
            } elseif (!empty($_GET["upmodif"])) {
                $state = $database->prepare("SELECT * from Article where idArticle= ?");
                $state->execute(array($_GET["upmodif"]));
                $result = $state->fetch();

                if (!empty($_POST["titre"])) {
                    $gettitre = htmlspecialchars($_POST["titre"], ENT_QUOTES);
                } else {
                    $gettitre = $result["titre"];
                }
                if (!empty($_POST["contenu"])) {
                    $getcontenu = htmlspecialchars($_POST["contenu"], ENT_QUOTES);
                } else {
                    $getcontenu = $result["contenu"];
                }
                if ($gettitre !== $result["titre"] || $getcontenu !== $result["contenu"] || $_POST["classe"] !== $result["classe"]) {
                    $state1 = $database->prepare("INSERT INTO Modifications (id_user, num_article, titre, classe, contenu) values (?, ?, ?, ?, ?)");
                    $state1->execute(array($_SESSION["now_id"], $_GET["upmodif"], $result["titre"], $result["classe"], $result["contenu"]));

                    $state2 = $database->prepare("UPDATE Article set titre= ?, classe= ?, contenu= ? where idArticle= ?");
                    $state2->execute(array($gettitre, $_POST["classe"], $getcontenu, $_GET["upmodif"]));
                }
                header("Location: modifs.php?getmodifs=" . $_GET["upmodif"]);
                exit();
            } else {
                header("Location: index.php");
                exit();
            }

            ?>
            
            </div>
        </div>
    </div>
</div>