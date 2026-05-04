<?php


$slozka = $shortcode->getParameter("slozka");

$slozka = "upload/source/$slozka";

$soubory = scandir($slozka);

echo "<div class='fotogalerie'>";
foreach($soubory as $soubor) {
    // preskocit soubory ("." a "..")
    if ($soubor[0] == ".") {
        continue;
    }
    $celaCesta = "$slozka/$soubor";
    $info = pathinfo($celaCesta);
    if ($info['extension'] == 'jpg') {
        $rozmery = getimagesize($celaCesta);
        $sirka = $rozmery[0];
        $vyska = $rozmery[1];
        $nazevAlt = ucfirst(str_replace("-", " ", $info['filename']));
        echo    "<a href='$celaCesta' data-pswp-width=$sirka data-pswp-height=$vyska>
                    <img src='$celaCesta' height='300' alt='$nazevAlt'>
                </a>";
    }
}
echo "</div>";
?>
<script type="module">
import PhotoSwipeLightbox from './vendor/photoswipe/dist/photoswipe-lightbox.esm.js';
const lightbox = new PhotoSwipeLightbox({
  gallery: '.fotogalerie',
  children: 'a',
  pswpModule: () => import('./vendor/photoswipe/dist/photoswipe.esm.js')
});
lightbox.init();
</script>