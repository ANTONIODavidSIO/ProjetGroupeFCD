<?php
include("head.php");
?>
<body style="background-color: grey; color: white;">
<?php
//vérif ban
function canban($iduser)
{
    $database = new PDO('mysql:host=localhost; dbname=wiki_scp; charset=utf8', 'root', '');
    $state = $database->prepare("SELECT idBan FROM Utilisateur WHERE idUtilisateur= ?");
    $state->execute(array($iduser));
    $output = $state->fetch();
    if ($output["idBan"] == 0) {      
        print "<a href='admin.php?ban=".$iduser."'><input type='button' value='Ban'/></a>";
    } else {
        print "<a href='admin.php?unban=".$iduser."'><input type='button' value='unBan'/></a>";
    }
}

//commande SQL
$state = $database->prepare("SELECT admin FROM Utilisateur WHERE idUtilisateur= ?");
$state->execute(array($_SESSION["now_id"]));
$result = $state->fetch();

//verif admin
if ($result["admin"] == 1) {
    ?>
    <div class="imageBackBis">
        
        <div class="decal">
        <?php
        $state = $database->prepare("SELECT * FROM Utilisateur WHERE admin!= 1");
        $state->execute();
        $resultats = $state->fetchall();
        echo "id | nom | prenom | email | idban <br>";
        foreach ($resultats as $lineResultat) {
            print $lineResultat["idUtilisateur"] . " | " . $lineResultat["nom"] . " | " . $lineResultat["prenom"] . " | " . $lineResultat["email"] . " | " . $lineResultat["idBan"] . " | ";
            print canban($lineResultat["idUtilisateur"]) . "<br>";
        }
        ?>
        <br><a href='admin.php?deco=true'><input type='button' value='Deconnexion'/></a> <a href='index.php'><input type='button' value='Retour'/></a>
        </div>

        <?php
        //systeme ban/unban
        if (!empty($_GET["ban"])) {
            $recupban= htmlspecialchars($_GET["ban"], ENT_QUOTES);
            $state = $database->prepare("UPDATE Utilisateur SET idBan = 1 WHERE idUtilisateur = ?");
            $state->execute(array($recupban));
            header('Location: admin.php');
            exit();
        }elseif (!empty($_GET["unban"])) {
            $recupunban= htmlspecialchars($_GET["unban"], ENT_QUOTES);
            $state = $database->prepare("UPDATE Utilisateur SET idBan = 0 WHERE idUtilisateur = ?");
            $state->execute(array($recupunban));
            header('Location: admin.php');
            exit();
        }

        //déconnexion
        if (!empty($_GET["deco"])) {
            $deco = htmlspecialchars($_GET['deco'], ENT_QUOTES);
            if ($deco == true) {
                session_unset();
                session_destroy();
                header('Location: index.php');
                exit();
            }
        }
    //user sans permission
} else {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit();
} ?>
</body>