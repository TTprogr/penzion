<?php
    //napojime vsechny composer knihovny skrze autoload
    require_once "./vendor/autoload.php";

    //pripojeni $poleStranek z dat
    require_once "./data.php";

    //zde nastavime id homepage
    //nastavili jsme id stranky na prvni polozku v poli
    $idStranky = array_keys($poleStranek)[0];

    //index.php?stranka=galerie
    if (array_key_exists("stranka", $_GET)) {
    $idStranky = $_GET["stranka"];

    //zjistime zda id existuje v poli povolenych stranek
    if(!array_key_exists($idStranky, $poleStranek)) {
        //pokud id v poli neni, tak automaticky nastavime id na 404
        $idStranky = "404";
    }

    
    
}
?>
<!DOCTYPE html>
<html lang="cs">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $poleStranek[$idStranky]->getTitulek(); ?></title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/all.min.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">

    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>

<body>

    <header>

        <div class="container">

            <div class="headerTop">
                <a class="mob" href="tel:+420606123456">+420 / 606 123 456</a>
                <div class="socIkony">
                    <a href="#" target="_blank">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="#" target="_blank">
                        <i class="fa-brands fa-x-twitter"></i>
                    </a>
                    <a href="#" target="_blank">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>

            </div>

            <a href="?stranka=domu" class="logo">Prima<br>Penzion</a>

            <!-- zde pripojuju menu stranky -->
            <?php require "./menu.php" ?>

        </div>
        <img src="./img/<?php echo $poleStranek[$idStranky]->getObrazek(); ?>" alt="PrimaPenzion" widht="1920" height="530">
    </header>

    <section>

        <!-- zde byl původně obsah stránky -->

        <?php
            $surovyObsah = $poleStranek[$idStranky] ->getObsah();
            //tato funkce nam zkontroluje text, najde [] a vymeni je za require
            //bere to 2 parametry
            //1) slozka kde jsou ulozene php soubory
            //2) text, ktery ma zprocesovat
            echo primakurzy\Shortcode\Processor::process("./moje-shortcody", $surovyObsah);
            //echo file_get_contents("./$idStranky.html");
        ?>
    </section>
    <footer>
        <div class="pata">


            <!-- zde pripojuju menu stranky -->
            <?php require "./menu.php" ?>

            <a href="index.html" class="logo">Prima<br>Penzion</a>

            <div class="pataInfo">
                <p>
                    <i class="fa-solid fa-location-pin"></i>
                    <a href="https://maps.app.goo.gl/AFZaSqT9zxBbqGqcA" target="_blank">
                        <b>PrimaPenzion</b>, Jablonského 2, Praha 7
                </p>
                </a>
                <p>
                    <i class="fa-solid fa-phone"></i>
                    <a href="tel:+420606123456"> +420 / 606 123 456</a>
                </p>
                <p>
                    <i class="fa-solid fa-envelope"></i>
                    <span>info@primapenzion.cz</span>
                </p>
            </div>

            <div class="socIkony">
                <a href="#" target="_blank">
                    <i class="fa-brands fa-facebook"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fa-brands fa-x-twitter"></i>
                </a>
                <a href="#" target="_blank">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            </div>
        </div>
        <div class="copy">
            &copy; <b>PrimaPenzion</b> 2023
        </div>
    </footer>
        
</body>

</html>