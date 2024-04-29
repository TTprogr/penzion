<?php

    session_start();

    require_once "./data.php";

    //zpracujeme form pro prihlaseni
    if (array_key_exists("login-submit", $_POST)) {
        $jmeno = $_POST["jmeno"];
        $heslo = $_POST["heslo"];

        //kontrola spravnosti prihlasovacih udaju
        if ($jmeno == "kocka" && $heslo == "cici123") {
            //vytvorime klice overeno do sessiony
            //podle existence tohoto klice pozname zda prihlaseny je
            $_SESSION["overeno"] = true;
        }
    }

    //zpracujem odhlasovaci formular
    if (array_key_exists("logout-submit", $_GET)) {
        //smazeme "overeno" ze sessiony a tim ho odhlasime
        unset($_SESSION["overeno"]);
        header("Location: ?");
        exit;
    }
    //pro upravu stranky musi byt uzivatel prihlasen
    if (array_key_exists("overeno", $_SESSION)) {
        //uzivatel chce aktualizovat poradi stranek v DB
        if(array_key_exists("razeniSubmit", $_POST)) {
            $poleId = $_POST["poleSerazenychId"];
            //zavolame statickou metodu classy a predame ji pole id
            Stranka::aktualizujPoradi($poleId);
            exit;
        }

        //uzivatel chce stranku smazat
        if (array_key_exists("delete", $_GET)) {
            $idStranky = $_GET["delete"];
            $aktivniInstance = $poleStranek[$idStranky];
            $aktivniInstance ->smazMe();

            //po smazani stranky persmerujeme uzivatele avycistime URL
            header("Location: ?");
            exit;
        }
        //uzivatel chce vytvorit novou stranku
        if (array_key_exists("new", $_GET)) {
            $aktivniInstance = new Stranka ("","","","");
        }

        //uzivatel chce zacit editovat stranku
        if (array_key_exists("edit", $_GET)) {
            $idStranky = $_GET["edit"];
            //vytahneme si z pole tu nasi spravnou instanci
            $aktivniInstance = $poleStranek[$idStranky];

        }

        //uzivatel chce aktualizovat web
        if (array_key_exists("aktualizovat-submit", $_POST)) {
            //vytahneme data z formulare
            $idStranky = trim ($_POST["id-stranky"]);
            $titulekStranky = $_POST["titulek-stranky"];
            $menuStranky = $_POST["menu-stranky"];
            $obrazekStranky = $_POST["obrazek-stranky"];

            //pokud je id prazdne, tak ho hned presmerujeme zpatky na uvodni stranku
            if($idStranky == "") {header("Loaction: ?");}
            //nastavime data do instance
            $aktivniInstance->setId($idStranky);
            $aktivniInstance->setTitulek($titulekStranky);
            $aktivniInstance->setMenu($menuStranky);
            $aktivniInstance->setObrazek($obrazekStranky);
            //rekneme instanci aby se propsala do DB
            $aktivniInstance->zapisDoDb();


            //vytahneme is text z formulare do promenne
            $novyObsahStranky = $_POST["obsah-stranky"];
            //zavolame metodu pro ulozeni obsahu do souboru
            $aktivniInstance->setObsah($novyObsahStranky);

            //presmerovat uzivatele na spravnou URL, jinak mu v URL zustane stare ID
            header("Location: ?edit=$idStranky");
            exit;

        }

    }//end if overeno

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin sekce</title>
</head>
<body>
    <h1>Admin sekce</h1>

    <?php
     if (array_key_exists("overeno", $_SESSION)) {
        //uzivatel prihlaseny je
        ?>
        <!-- odhlasovaci form -->
        <form action="" method="get">
            <input type="submit" name="logout-submit" value="Odhlasit se">
        </form>
        <!-- odhlasovaci odkaz  -->
        <a href="?logout-submit=true">Odhlasit se</a>
        <?php
        //zde vypisu uzivateli seznam editovatelnych stranek
        echo "<ul id='ul-stranek'>";
        foreach ($poleStranek AS $stranka) {
            echo "<li id='{$stranka->getId()}'>
                <a href='?edit={$stranka->getId()}'>
                    {$stranka->getId()}
                </a>
                <a class='odkaz-mazani' href='?delete={$stranka->getId()}'>
                    SMAZ ME
                </a>
            </li>";
        }
        echo "</ul>";

        //pridame odkaz pro vytvoreni nove stranky
        echo "<a href='?new=true'>Vytvorit novou stranku</a>";

        //zde vypiseme editor stranky, jen pokud existuje promenna $aktivniInstance
        if (isset($aktivniInstance)) {
            ?>
            <form action="" method="post">
                <label for="koala">ID: </label>
                <input type="text" name="id-stranky" id="koala" value="<?php echo $aktivniInstance-> getId();?>">
                <br>
                <label for="velbloud">Titulek: </label>
                <input type="text" name="titulek-stranky" id="velbloud" value="<?php echo $aktivniInstance-> getTitulek();?>">
                <br>
                <label for="tulen">Menu: </label>
                <input type="text" name="menu-stranky" id="tulen" value="<?php echo $aktivniInstance-> getMenu();?>">
                <br>
                <label for="motyl">Obrazek: </label>
                <input type="text" name="obrazek-stranky" id="motyl" value="<?php echo $aktivniInstance-> getObrazek();?>">
                <br>
                <label for="mravenec">Obsah stranky: </label>
                <textarea name="obsah-stranky" id="mravenec" cols="40" rows="40"><?php echo htmlspecialchars($aktivniInstance->getObsah()); ?></textarea>
                <input type="submit" name="aktualizovat-submit" value="Aktualizovat web">
                </form>
            <!-- musime pripojit knihovnu tinymce -->
            <script src="./vendor/tinymce/tinymce/tinymce.js"></script>
            <script>
                tinymce.init({
                    selector: "#mravenec",
                    content_css: ["./css/style.css", "./css/all.min.css"],
                    entity_encoding: 'raw',
                    cleanup: false,
                    verify_html: false,
                    plugins: ["code", "responsivefilemanager", "image", "anchor", "autolink", "autoresize", "link", "media", "lists"],
                    toolbar1: 'formatselect | bold italic strikethrough | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
                    toolbar2: "| responsivefilemanager | link | image media | forecolor backcolor  | print preview code ",

                    external_plugins: {
			        'responsivefilemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
		            },
		            external_filemanager_path: "<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/filemanager/",
		            filemanager_title: "File manager",
                });
            </script>
            <?php
        }
    }else{
        //uzivatel prihlaseny neni
        ?>
        <form action="" method="post">
        <label for="tukan">Jmeno</label>
        <input type="text" name="jmeno" id="tukan">
        <label for="slon">Heslo</label>
        <input type="password" name="heslo" id="slon">
        <input type="submit" name="login-submit" value="Prihlasit se">
    </form>
    <?php
    }

    ?>


    <script src="./vendor/components/jquery/jquery.js"></script>
    <script src="./vendor/components/jqueryui/jquery-ui.js"></script>
    <script src="./js/admin.js"></script>   
</body>
</html>