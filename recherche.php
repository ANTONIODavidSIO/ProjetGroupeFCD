<html>

<body>
    <br>
    <form class="Searchform" method="get" action="">
        <input type="search" name="recherche" />
        <input type="submit" name="valider" value="Rechercher" />
    </form>
    <br>


</body>

</html>


<?php

$connex = new PDO('mysql:host=localhost; dbname=wiki_scp; charset=utf8', 'root', '');

if (isset($_GET["valider"]) and $_GET["valider"] == "Rechercher") {
    $rcprotect = htmlspecialchars($_GET["recherche"]);
    $recherche = trim($rcprotect);
}
if (isset($recherche)) {
    $recherche = strtolower($recherche);
    $select_recherche = $connex->prepare("SELECT * FROM article WHERE titre LIKE ? OR contenu LIKE ?");
    $select_recherche->execute(array("%" . $recherche . "%", "%" . $recherche . "%"));
}

if (!empty($select_recherche)) {
    while ($recherche_trouve = $select_recherche->fetch()) {
        echo "<div class='result' style='color:white;'><p><strong>" . $recherche_trouve['titre'] . "</strong></p><p> " . $recherche_trouve['contenu'] . "</p></div><br>";
    }
}

?>