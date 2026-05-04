<?php

require "stranky.php";
session_start();

$chyba = null;
if (array_key_exists("prihlasit", $_POST)) {
    $login = filter_input(INPUT_POST, 'jmeno', FILTER_SANITIZE_SPECIAL_CHARS);
    $heslo = filter_input(INPUT_POST, 'heslo', FILTER_SANITIZE_SPECIAL_CHARS);
    if ($login == "" || $heslo == "") {
        $chyba = "Login nebo heslo musi byt vyplneno";
    } else {
        if (overitprihlaseni($login, $heslo)) { 
            $_SESSION['prihlasen'] = $_POST['jmeno'];
            header("Location: ?"); // aby pri refresovani znovu forma se neodesila
        } else {
            $chyba = "Nespávné přihlašovací údaje";
        }
    }
}
if (array_key_exists("odhlasit", $_POST)) {
    // session_unset(); // так краще не робити
    unset($_SESSION['prihlasen']);
    header("Location: ?");
}
// zpracovani akce v administraci je pouze pro prihlasene uzivatele
if (array_key_exists('prihlasen', $_SESSION)) {
    // promenna predtavujici stranku kteru zrovna editujeme
    $instanceAktualniStranky = null;
    if (array_key_exists("stranka", $_GET)) {
        $idStranky = $_GET['stranka'];
        $instanceAktualniStranky = $seznamStranek[$idStranky];
    }
    // zpracovani tlacitka "Pridat"
    if(array_key_exists('pridat', $_GET)) {
        $instanceAktualniStranky = new Stranka("", "", "");
    }
    // zpracovani mazani 
    if(array_key_exists('smazat', $_GET)) {
        $instanceAktualniStranky->smazat();
        // po smazani stranky musime se presmerovat "domu"
        header("Location: ?");
    }

    // zpracovani formulare pro ulozeni
    if (array_key_exists('ulozit', $_POST)) {
        // poznamename si puvodni id nez si ho prepiseme
        $puvodniId = $instanceAktualniStranky->id;
    
        // ukladani nove id, titulka a menu
        $instanceAktualniStranky->id = $_POST['id'];
        $instanceAktualniStranky->titulek = $_POST['titulek'];
        $instanceAktualniStranky->menu = $_POST['menu'];
        //zavolame funkce pro ulozeni zmenenych hodnot 
        $instanceAktualniStranky->ulozit($puvodniId);
        // ukladani obsahu stranky
        $obsah = $_POST['obsah'];
        $instanceAktualniStranky->setObsah($obsah);
    
        // presmerujeme na URL stranky s novym id pri refresovani 
        // protoze kdyby se id zmenilo tak nesmime zustat na puvodni url (bude error)
        
        header("Location: ?stranka=".urlencode($instanceAktualniStranky->id)); 
        // urlencode je treba v pripade kdyz format noveho id bude neplatny pro url(napriklad - mezera, tak ono to opravi)
    }
    // zpracovani pozadavku zmeny poradi stranek z javascriptu  (ajaxem)
    if (array_key_exists("poradi", $_GET)) {
        $poradi = $_GET['poradi'];
        // zavolani funkce pro nastaveni poradi a ulozeni do db
        // Stranka::nastavitPoradi tak volame static funkce pres scope resolution operator(::)
        Stranka::nastavitPoradi($poradi);
        echo 'OK'; // na zkousku se vrati v Network-Preview 'ok
        exit; // ukonci generovani stranky (na zkousku)
    }
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrace</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
</head>
<body>
<?php
    if (array_key_exists('prihlasen', $_SESSION) == false) 
    {
        ?>
        <main class="form-signin position-absolute top-50 start-50 translate-middle">
            <form method="post">
                <h1 class="h3 mb-3 fw-normal">Přihlašte se prosím</h1>
                <?php if ($chyba != null) {
                    echo "<div class='vzkaz alert alert-danger' role='alert'>$chyba</div>";
                } 
                ?>
                <div class="form-floating">
                    <input type="text" name="jmeno" class="form-control" placeholder="jméno" id="floatingInput"><br>
                    <label for="floatingInput" >Prihl. jméno: </label>
                </div>
                <div class="form-floating">
                    <input type="password" name="heslo" class="form-control" placeholder="heslo" id="floatingPassword"><br>
                    <label for="floatingPassword">Heslo: </label>
                </div>               
                <button class="btn btn-primary w-100 py-2" name="prihlasit">Přihlásit</button>
            </form>
        </main>
    <?php
    } else { 
        ?>
        <main class="d-flex flex-column bg-body-tertiary">
            <div class="d-flex justify-content-between">
                <h2 class="p-2">Přihlášen uživatel: <?php echo $_SESSION['prihlasen'];?> </h2>
                <form class="p-2" method="post">
                    <button name="odhlasit" class="btn btn-outline-primary">Odhlásit</button>
                </form>
            </div>
            <!-- vypiseme seznam stranek , ktere lze editovat -->
            <?php
            echo "<ul id='stranky' class='list-group'>";
            foreach ($seznamStranek as $idStranky => $instanceStranky)
            {
                $active = '';
                $buttonClass = 'btn-outline-primary';
                if ($instanceStranky == $instanceAktualniStranky)
                {
                    $active = 'active';
                    $buttonClass = 'btn-secondary';
                }
                echo "<li class='list-group-item $active' id='$instanceStranky->id'>
                    <a class='btn $buttonClass' href='?stranka=$instanceStranky->id'><i class='fa-solid fa-pen-to-square'></i></a>

                    <a class='btn $buttonClass' href='$instanceStranky->id' target='_blank'><i class='fa-solid fa-eye'></i></a>

                    <a class='btn $buttonClass smazat' href='?stranka=$instanceStranky->id&smazat'><i class='fa-solid fa-trash-can delete-icon'></i></a>

                    <span>$instanceStranky->id</span>
                    </li>";
            }
            echo "</ul>";

            // formular s tlacitkem pro pridani stranky -->
            ?>
            <form><button name="pridat" class="btn btn-outline-primary m-3">Přidat</button></form>
        </main>
         <!-- editacni formular -->
        <!-- cheme ho zobrazit pokud je nejaka stranka vybrana k editace nebo stisknuto tlacitko pridat -->
        <?php 
        if ($instanceAktualniStranky != null) {
            echo "<h2 class='p-2 align-self-center'>";
            if ($instanceAktualniStranky->id == '') {
                echo "Přidávání stránky";
            } else {
                echo "Editace stranky: ".ucfirst($instanceAktualniStranky->id);
            }
            echo "</h2>";
            ?>
            <form method="post">
                <div class="form-floating">
                    <input type="text" class="form-control" placeholder="Id" name="id" id="id" value="<?php echo htmlspecialchars($instanceAktualniStranky->id) ?>">
                    <label for="id">Id</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" placeholder="Titulek" name="titulek" id="titulek" value="<?php echo htmlspecialchars($instanceAktualniStranky->titulek) ?>">
                    <label for="titulek">Titulek</label>
                </div>
                <div class="form-floating">
                    <input type="text" class="form-control" placeholder="Menu" name="menu" id="menu" value="<?php echo htmlspecialchars($instanceAktualniStranky->menu) ?>">
                    <label for="menu">Menu</label>
                </div>
                <textarea name="obsah" cols='80' rows='15' id="obsah"><?php
                echo htmlspecialchars($instanceAktualniStranky->getObsah()); // pro ingorovani specialni znaky
                ?></textarea>
                <br>
                <button name="ulozit" class="m-2 btn btn-primary btn-lg text-white">Uložit</button>
            </form>
            <script src="vendor/tinymce/tinymce/tinymce.min.js"></script>
            <script type="text/javascript">
                tinymce.init({
                selector: '#obsah',
                language: 'cs',
                language_url: '<?php echo dirname($_SERVER['PHP_SELF']);  ?>/vendor/tweeb/tinymce-i18n/langs/cs.js',  //nastavili cestinu
                height: '90vh',
                entity_encoding: "raw",
                verify_html: false,
                content_css: [
                    "fontawesome/css/all.min.css",
                    "css/fonts.css",
                    "css/reset.css",
                    "css/section.css",
                    "css/style.css"
                ],
                plugins: "advlist anchor autolink charmap code colorpicker contextmenu directionality emoticons fullscreen hr imageimagetools insertdatetime link lists nonbreaking noneditable pagebreak paste preview save searchplace tabfocus table textcolor textpattern visualchars",
                toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor",
                toolbar2: "link unlink anchor | fontawesome | image media | responsivefilemanager | preview code",
                external_plugins: {
                    'responsivefilemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/responsivefilemanager/plugin.min.js',
                    'filemanager': '<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/tinymce/plugins/filemanager/plugin.min.js',
                },
                external_filemanager_path: "<?php echo dirname($_SERVER['PHP_SELF']); ?>/vendor/primakurzy/responsivefilemanager/filemanager/",
                filemanager_title: "File manager",
                });
            </script>
            <?php
        }
        ?>
        <?php
    }
    ?>
    <script src="js/admin.js"></script>
</body>
</html>