<div class="foto" id="moje-galerie">mysq
    <?php
    //toto je slozka ve ktere mame fotky do galerie
    $slozka = "./upload/source/fotky-do-galerie";
    //pomoci funkce scandir zjistime jake vsechny fotky tam jsou
    $poleJmen = scandir($slozka);

    //var_dump($poleJmen);
    //priterujeme pole jmen a zaignorujeme "." a ".."
    foreach ($poleJmen AS $jmeno) {
        if ($jmeno == "." || $jmeno == "..") {
            continue;
        }

        //musime zjstit dimenze obrazku
        $poleInformaci = getimagesize("$slozka/$jmeno");
        //var_dump($poleInformaci);
        $sirka = $poleInformaci[0];
        $vyska = $poleInformaci[1];

        //pro kazdou fotku udelame odkaz a img
        //slozime 2 promene a dostaneme cestu k fotce
        echo "<a href='$slozka/$jmeno' data-pswp-width='$sirka' data-pswp-height='$vyska'>
            <img src='$slozka/$jmeno' alt='obrazek'>
        </a>";
    }
    ?>
</div>

<link rel="stylesheet" href="./node_modules/photoswipe/dist/photoswipe.css">

<script type="module">
import Lightbox from './node_modules/photoswipe/dist/photoswipe-lightbox.esm.js';
const lightbox = new Lightbox({
  gallery: '#moje-galerie',
  children: 'a',
  pswpModule: () => import('./node_modules/photoswipe/dist/photoswipe.esm.js'),
  padding: { top: 40, bottom: 40, left: 100, right: 100 }
});
lightbox.init();
</script>