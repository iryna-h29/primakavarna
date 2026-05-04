<?php
$db = new PDO(
    "mysql:host=localhost;dbname=primakavarna;charset=utf8mb4",
    "login",
    "password",
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ),
);


class Stranka {
    public $id;
    public $titulek;
    public $menu;

    function __construct($id, $titulek, $menu) {
        $this->id = $id;
        $this->titulek = $titulek;
        $this->menu = $menu;
    }
    function getObsah() {
        // return file_get_contents("$this->id.html");

        // nacteni obsahu stranky z databaze
        global $db;

        $dotaz = $db->prepare("SELECT obsah from stranka where id = ?");
        $dotaz->execute([$this->id]);
        $vysledek = $dotaz->fetch();

        // pokud se databaze nic nevratila, tak vratime prazdny obsah 
        if ($vysledek == false) {
            return "";
        } else {
            return $vysledek['obsah'];
        }
    }
    function setObsah($obsah) {
        // file_put_contents("$this->id.html", $obsah);
        
        // ukladani obsahu stranky do databaze

        global $db;
        $dotaz = $db->prepare("UPDATE stranka set obsah = ? where id = ?");
        $dotaz->execute([$obsah, $this->id]);
    }
    function ulozit($puvodniId) {
        global $db;
        if ($puvodniId != "") {
            // jde o aktualizaci existujici stranky
            $dotaz = $db->prepare('UPDATE stranka set id = ?, titulek = ?, menu = ? where id = ?');
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $puvodniId]);
        } else {
            // jde p pridavani nove stranky
            
            // zjisteni maximalniho poradi
            $dotaz = $db->prepare("SELECT MAX(poradi) as poradi from stranka");
            $dotaz->execute();
            $vysledek = $dotaz->fetch();
            // vezmeme nejvysi poradi ktere je v tabulce a pridame a navysime o 1
            $poradi = $vysledek['poradi'] + 1;
            $dotaz = $db->prepare('INSERT INTO stranka set id = ?, titulek = ?, menu = ?, poradi = ?');
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $poradi]);
        }
    }
    function smazat() {
        global $db;
        $dotaz = $db->prepare("DELETE from stranka WHERE id = ?");
        $dotaz->execute([$this->id]);
    }
    static function nastavitPoradi($poradi) {
        global $db;
        // projdeme pole s poradim (pole je cislovane)
        foreach($poradi as $cislo => $idStranky) { 
            $dotaz = $db->prepare("UPDATE stranka SET poradi = ? WHERE id = ?");
            $dotaz->execute([$cislo, $idStranky]);
        }
    }
}



// $seznamStranek = [
//     "uvod" => new Stranka("uvod", "PrimaKavarna", "Domů"),
//     "nabidka" => new Stranka("nabidka", "PrimaKavarna - Nabídka", "Nabídka"),
//     "galerie" => new Stranka("galerie", "PrimaKavarna - Galerie", "Galerie"),
//     "rezervace" => new Stranka("rezervace", "PrimaKavarna - Rezervace", "Rezervace"),
//     "kontakt" => new Stranka("kontakt", "PrimaKavarna - Kontakt", "Kontakt"),
//     "error" => new Stranka("error", "PrimaKavarna", "")
// ];

$seznamStranek = [];

// pole se seznamem stranek naplnime dynamicky s databaze

$dotaz = $db->prepare("SELECT id, titulek, menu from stranka order by poradi");
$dotaz->execute();

$stranky = $dotaz->fetchAll();
// var_dump($stranky);

// vezmeme pole radek ktere nam vratila databaze a postupne 
// nakrmime pole seznam stranek jednotlivymi instancemi tridy Stranka

foreach ($stranky as $stranka) {
    $idStranky = $stranka['id'];
    $seznamStranek[$idStranky] = new Stranka($idStranky, $stranka['titulek'], $stranka['menu']);
}
// var_dump($seznamStranek);

function overitprihlaseni($login, $heslo) {
    global $db;
    $dotaz = $db->prepare("SELECT password from admindata where login = ?");
    $dotaz->execute([$login]); 
    $hashed_password = $dotaz->fetch();

    if ($hashed_password == false) {
        return false;
    } else {
        if (password_verify($heslo, $hashed_password[0])) {
            return true;
        } else {
            return false;
        }
    }

}