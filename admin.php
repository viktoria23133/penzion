<?php
session_start();

//pripojime seznam stranek
require_once "data.php";

//defaultni stav
$chyba = "";
//$idAktualniStranky = null;
$instanceAktualniStranky = null;

//---blok zachytavani udalosti---//

//uzivatel se chce prihlasit
if (array_key_exists("login-do-adminu", $_POST)) {
    //hontrola spravnosti hesla a jmena
    if ($_POST['username'] == 'admin' && $_POST['password'] == 'heslo123') {
        //spravne, vytvorit session
        $_SESSION['prihlasenyUzivatel'] = $_POST['username'];
    } else {
        //zpatne zadaneho prihlasovaci udaje
        //vypsat chybovou hlasku
        $chyba = "Chybne udaje";
    }
}

//uzivatel se chce odhlasit
if (array_key_exists("logout", $_GET)) {
    //smaze uzivatele ze Session
    unset($_SESSION['prihlasenyUzivatel']);
    //refeshne stranku
    header('Location: ?');
    //zastavi nacitani zbytku kodu
    exit;
}

//uzivatel chce editovat
if (array_key_exists("edit", $_GET)) {
    //$idAktualniStranky = $_GET["edit"];

    //z GETu si vytahneme id stranky kterou chce uzivatel editovat
    $idStrankyKEditaci = $_GET["edit"];
    //vytahneme si podle toho id instanci stranky ze $seznamStranek
    $instanceAktualniStranky = $seznamStranek[$idStrankyKEditaci];
}

//uzivatel chce pridat novou stranku
if (array_key_exists("pridat", $_GET)) {
    //vytvori se nova instance
    $instanceAktualniStranky = new Stranka("", "", "");
}

if (array_key_exists("uloz-stranku", $_POST)) {
    //vytahneme si z inputu data
    $noveId = $_POST["id-stranky"];
    $novyTitulek = $_POST["titulek-stranky"];
    $noveMenu = $_POST["menu-stranky"];
    
    //zmenime vnitrni stav instance
    $instanceAktualniStranky->setId($noveId);
    $instanceAktualniStranky->setTitulek($novyTitulek);
    $instanceAktualniStranky->setMenu($noveMenu);

    //ulozime stav instance do databaze
    $instanceAktualniStranky->ulozDoDatabaze();

    //update obsahu
    $novyObsah = $_POST["obsah"];
    $instanceAktualniStranky->setObsah($novyObsah);

    //refreshneme stranku
    header("Location: ?edit={$noveId}");
    exit;
}

if (array_key_exists("smazat", $_GET)) {
    $idStrankyKeSmazani = $_GET["smazat"];
    $seznamStranek[$idStrankyKeSmazani]->smazSe();
    header("Location: ?");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <?php
        if (array_key_exists('prihlasenyUzivatel', $_SESSION)) {
            ?>
                <a href="?logout=true">Odhlasit se</a>
                <a href="?pridat">Nova stranka</a>
            <?php
                echo "<ul>";
                foreach ($seznamStranek as $stranka) {
                    echo "<li><a href='?edit={$stranka->getId()}'>{$stranka->getId()}</a> <a href='?smazat={$stranka->getId()}'>[smazat]</a></li>";
                }
                echo "</ul>";

                //zjististi jestli v $instanceAktualniStranky neco je
                if ($instanceAktualniStranky) {
                    ?>

                    <form action="" method="post">
                        <label for="">ID: </label>
                        <input type="text" name="id-stranky" value="<?php echo $instanceAktualniStranky->getId() ?>">
                        <label for="">Titulek: </label>
                        <input type="text" name="titulek-stranky" value="<?php echo $instanceAktualniStranky->getTitulek() ?>">
                        <label for="">Menu: </label>
                        <input type="text" name="menu-stranky" value="<?php echo $instanceAktualniStranky->getMenu() ?>">

                        <!-- htmlspecialchars slousi k escapovani hmtl znaku -->
                        <textarea name="obsah" id="obsah-textarea" cols="30" rows="30">
                            <?php echo htmlspecialchars($instanceAktualniStranky->getObsah()); ?>
                        </textarea>
                        <input type="submit" name="uloz-stranku" value="Aktualizovat">
                    </form>

                    <script src="vendor/tinymce/jquery.tinymce.min.js"></script>
                    <script src="vendor/tinymce/tinymce.min.js"></script>
                    <script>
                        //selector: #idtextareay
                        tinymce.init({
                                selector: "#obsah-textarea",
                                plugins: [
                                        "advlist autolink link image lists charmap print preview hr anchor pagebreak",
                                        "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
                                        "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
                                ],
                                toolbar1: "undo redo | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect",
                                toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code ",
                                image_advtab: true ,
                                external_filemanager_path:"filemanager/",
                                external_plugins: { "filemanager" : "filemanager/plugin.min.js"},
                                filemanager_title:"Responsive Filemanager",
                                entity_encoding:'raw',
                                verify_html: false,
                            });
                    </script>

                    <?php
                }
        }else {
            ?>

                <div class=error><?php echo $chyba;  ?></div>
                <form method="post">
                    <label for="username-input">Prihlasovai jmeno:</label><input type="text" name="username" id="username-input">
                    <label for="password-input">Heslo:</label><input type="password" name="password" id="password-input">
                    <input type="submit" name="login-do-adminu" value="Prihlasit se">
                </form>

            <?php
        }
    
    ?>  
</body>
</html>