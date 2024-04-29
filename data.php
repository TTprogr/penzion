<?php
//uvedeme prihlasovaci udaje do databaze penzion
$instanceDb = new PDO(
    "mysql:host=localhost;dbname=penzion;charset=utf8mb4",
    "root",
    "",
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
);

class Stranka {
    //kzda instace bude mit tyto vlastnosti
    private $id;
    private $titulek;
    private $obrazek;
    private $menu;
    private $oldID = "";

    function __construct($argId, $argTitulek, $argObrazek, $argMenu) {
        $this ->id = $argId;
        $this ->titulek = $argTitulek;
        $this ->obrazek = $argObrazek;
        $this ->menu = $argMenu;
    }
    //toto je staticka metoda classy Stranka, ktera v DB aktualizuje poradi vsech stranek
    static function aktualizujPoradi($argPoleId){
        foreach($argPoleId AS $index => $id) {
            $prikaz = $GLOBALS["instanceDb"] ->prepare("UPDATE stranka SET poradi=? WHERE id=?");
            $prikaz ->execute(array($index, $id));
        }
    }

    function getId() {
        return $this->id;
    }

    function setId($argId) {
        //ulozime si pro pozdeji pouziti stare id
        $this -> oldId = $this ->id;
        $this->id = $argId;
    }

    function getTitulek() {
        return $this->titulek;
    }

    function setTitulek($argTitulek) {
        $this->titulek = $argTitulek;
    }

    function getMenu() {
        return $this->menu;
    }

    function setMenu($argMenu) {
        $this->menu = $argMenu;
    }

    function getObrazek () {
        return $this->obrazek;
    }

    function setObrazek($argObrazek) {
        $this->obrazek = $argObrazek;
    }

    function getObsah(){
        $prikaz = $GLOBALS["instanceDb"]->prepare("SELECT*FROM stranka WHERE id=?");
        $prikaz -> execute(array($this->id));
        $poleDatastranky = $prikaz ->fetch();

        
        if($poleDatastranky == null) {
            return "";
        }
        $obsahSouboru = $poleDatastranky ["obsah"];

        //$obsahSouboru = file_get_contents("./$this->id.html");
        return $obsahSouboru;

    }

    function setObsah ($argText) {
        $prikaz = $GLOBALS["instanceDb"]->prepare("UPDATE stranka SET obsah=? WHERE id=?");
        $prikaz -> execute(array($argText, $this->id));

        //file_put_contents("./$this->id.html", $argText);
    }

    function zapisDoDb() {

        if ($this->oldId == "") {
            $prikaz = $GLOBALS["instanceDb"]->prepare("SELECT*FROM stranka ORDER BY poradi DESC LIMIT 1");
            $prikaz -> execute();
            $posledniStranka = $prikaz->fetch();
            if ($posledniStranka == null) {
                $poradi = 0;
            }else{
                $poradi = 1 + $posledniStranka ["poradi"];
            }

            //stranka jeste neexistuje v db, udelame insert
            $prikaz = $GLOBALS["instanceDb"]->prepare("INSERT INTO stranka SET id=?, titulek=?, menu=?, obrazek=?, poradi=?");
            $prikaz->execute(array($this->id, $this->titulek, $this->menu, $this->obrazek, $poradi));    
        }else{
            //stranka uz v db je, udelame update
            $prikaz = $GLOBALS["instanceDb"]->prepare("UPDATE stranka SET id=?, titulek=?, menu=?, obrazek=? WHERE id=?");
            $prikaz->execute(array($this->id, $this->titulek, $this->menu, $this->obrazek, $this->oldId));    
        }
    }

    function smazMe() {
        $prikaz = $GLOBALS["instanceDb"]->prepare("DELETE FROM stranka WHERE id=?");
        $prikaz->execute(array($this->id));
    }
}//end stranka

//vytvorime si instance podle dat v databazi
$poleStranek = array();
$prikaz = $instanceDb -> prepare("SELECT * FROM stranka ORDER BY poradi ASC");
$prikaz->execute();
$poleVysledku = $prikaz->fetchAll();
//proiterujeme vysledky z databaze a pro kazdou stranku vytvorime instanci, kterou vlozime do promenne $poleStranek
foreach ($poleVysledku AS $vysledek) {
    $poleStranek[$vysledek["id"]] = new Stranka ($vysledek ["id"], $vysledek["titulek"], $vysledek["obrazek"], $vysledek["menu"]);
}

//zde nadefinujeme vsechny dostupne stranky
//ted uz to nebude pole poli, ale bude to pole instanci
/*
$poleStranek = array(
    "domu" => new Stranka("domu", "PrimaPenzion | Homepage", "primapenzion-main.jpg", "Home"),
    "galerie" => new Stranka("galerie", "PrimaPenzion | Fotky", "primapenzion-pool-min.jpg", "Fotogalerie"),
    "kontakt" => new Stranka("kontakt", "PrimaPenzion | Napište nám", "primapenzion-room.jpg", "Kontakty"),
    "rezervace" => new Stranka("rezervace", "PrimaPenzion | Chci pokoj", "primapenzion-room2.jpg", "Rezervace"),
    "404" => new Stranka("404", "PrimaPenzion | Chyba 404", "primapenzion-main.jpg", "")
);
*/
/*
    $poleStranek = array (
        "domu"=> array (
            "id" => "domu",
            "titulek" => "PrimaPenzion | Homepage",
            "obrazek" => "primapenzion-main.jpg",
            "menu" => "Home"
        ),
        "galerie"=> array (
            "id" => "galerie",
            "titulek" => "PrimaPenzion | Fotky",
            "obrazek" => "primapenzion-pool-min.jpg",
            "menu" => "Footgalerie"

        ),
        "kontakt"=> array (
            "id" => "kontakt",
            "titulek" => "PrimaPenzion | Napište nám",
            "obrazek" => "primapenzion-room.jpg",
            "menu" => "kontakty"
        ),
        "rezervace"=> array (
            "id" => "rezervace",
            "titulek" => "PrimaPenzion | Chci pokoj",
            "obrazek" => "primalpenzion-room2.jpg",
            "menu" => "Rezervace"
        ),
        "404"=> array (
            "id" => "404",
            "titulek" => "PrimaPenzion | Chyba 404",
            "obrazek" => "primapenzion-main.jpg",
            "menu" => ""
        )

    );
    */
