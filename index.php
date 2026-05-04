<?php
require "vendor/autoload.php";
require "stranky.php";
if (array_key_exists("stranka", $_GET)) { // upravili jsme to pomoci .htaccess
    if (array_key_exists($_GET['stranka'], $seznamStranek)) {
        $stranka = $_GET['stranka'];
    } else {
        $stranka = "error";
        http_response_code(404);
        // another way to handle 404 error
        // readfile('error.html');    // Output the content of 404.html
        // exit;  
    }
} else {
    // zjistime prvni stranku z pole seznamStranek
    $stranka = array_key_first($seznamStranek);
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $seznamStranek[$stranka]->titulek; ?></title>
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/section.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="shortcut icon" href="img/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="vendor\photoswipe\dist\photoswipe.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <menu>
            <div class="container" id="nav_container">
                <a class="header-logo" href="./" aria-label="Odkaz na hlavní stránku">
                    <img src="img/logo.png" alt="" class="header-logo__img" width="142" height="80">
                </a>
                <nav class="navigation">
                    <ul class="navigation-list">
                        <?php
                        foreach($seznamStranek as $idStranky => $objStranky){
                            if ($objStranky->menu != "") {
                                echo 
                                "<li class='navigation-list__item'>
                                    <a href='$idStranky#$objStranky->id' class='navigation-list__link'>$objStranky->menu</a>
                                </li>";
                            }
                        }
                        ?>
                    </ul>
                </nav>
                <button class="menu__btn" >
                    <img src="fontawesome/svgs/solid/bars.svg" alt="" aria-label="otevřít menu" width="30" height="30">
                </button>
            </div>
        </menu>
        <?php if ($stranka != 'error') 
        { ?>
        <section class="header-section">
            <h2 class="header-section__h2">PrimaKavárna</h2>
            <h3 class="header-section__h3">Jsme tu pro vás již od roku 2002</h3>
            <ul class="social-links-list">
                <li class="social-links-list__item">
                    <a href="./" class="social-link" target="_blank" aria-label="Náš Facebook">
                        <i class="fa-brands fa-facebook social-icon" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="social-links-list__item" >
                    <a href="./" class="social-link" target="_blank" aria-label="Náš Instagram">
                        <i class="fa-brands fa-instagram social-icon" aria-hidden="true"></i>
                    </a>
                </li>
                <li class="social-links-list__item">
                    <a href="./" class="social-link" target="_blank" aria-label="Náš Youtube">
                        <i class="fa-brands fa-youtube social-icon" aria-hidden="true"></i>
                    </a>
                </li>
            </ul>
        </section>
        <?php } ?>
    </header>
    <main>
        <?php
        $obsah = $seznamStranek[$stranka]->getObsah();
        // call library function
        echo primakurzy\Shortcode\Processor::process('shortcodes', $obsah); 
        ?>
    </main>
    <footer>
        <div class="container">
            <nav class="footer-navigation">
                <h3 class="footer-navigation__heading">Menu</h3>
                <ul class="footer-navigation-list">
                    <?php
                        foreach($seznamStranek as $idStranky => $objStranky){
                            if ($objStranky->menu != "") {
                                echo 
                                "<li class='footer-navigation-list__item'>
                                    <a href='$idStranky#$objStranky->id' class='footer-navigation-list__link'>$objStranky->menu</a>
                                </li>";
                            }
                        }
                    ?>
                </ul>
            </nav>
            <div class="footer-contact">
                <h3 class="footer-contact__heading">Kontakt</h3>
                <address>
                    <a href="https://uk.mapy.cz/s/cecafepacu" target="_blank">
                        PrimaKavárna<br>
                        Jablonského 2<br>
                        Praha, Holešovice
                    </a>
                </address>
            </div>
            <div class="opening-time">
                <h3>Otevřeno</h3>
                <table>
                    <tr>
                        <th>Po - Pá:</th>
                        <td>8h - 20h</td>
                    </tr>
                    <tr>
                        <th>So:</th>
                        <td>10h - 22h</td>
                    </tr>
                    <tr>
                        <th>Ne:</th>
                        <td>12h - 20h</td>
                    </tr>
                </table>
            </div>
        </div>
    </footer>
    <div id="nahoru">
        <i class="fa-solid fa-arrow-up"></i>
    </div>
    <script src="js/index.js"></script>
</body>
</html>