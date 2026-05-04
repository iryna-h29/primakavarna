<?php

use Thunder\Shortcode\Shortcode\Shortcode;

$chyby = [];
$jmeno = "";
$email = "";
$telefon = "";
$zprava = "";
$odeslano = false;
$formularOdeslan = false;
if (array_key_exists("odeslat", $_POST)) {

    $formularOdeslan = true;
    $jmeno = $_POST['jmeno'];
    $telefon = $_POST['telefon'];
    $email = $_POST['email'];
    $zprava = $_POST['zprava'];

    // validace hodnot 
    if (mb_strlen($jmeno) < 5) {
        $chyby['jmeno'] = "Jméno musí být zadáno";
    }
    if (mb_strlen($telefon) < 9) {
        $chyby['telefon'] = "Telefon musí být zadán";
    }
    if (!preg_match("/.+@.+\\.+/", $email)) {
        $chyby['email'] = "Neplatný email";
    }
    if (mb_strlen($zprava) < 5) {
        $chyby['zprava'] = "Zpráva musí být zadána";
    }
// zkontrolujeme zdali pole chyby je prazdne
    if (count($chyby) == 0) {
        // vse ok
        $odeslano = true;
        // odesleme zpravci email ze mu nekdo napsal pres kontaktni formular
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->CharSet = "utf-8"; // podpora cz 
        $mail->setFrom('info@primakavarna.cz', 'PrimaKavarna');
        $adresaEmailu = $shortcode->getParameter("email"); // vytahnout email v parametru kam odeslat
        $mail->addAddress($adresaEmailu); // kam odeslat
        $mail->isHTML(true);  // podpora html kodu v body emailu
        $mail->Subject = 'Kontaktní formulář Primakavárna'; // predmet zpravy 
        $mail->Body    = "
        <h1>Kontaktní formulář Primakavárna</h1>
        <div><b>Jméno:</b> $jmeno </div>
        <div><b>Telefon:</b> $telefon </div>
        <div><b>Email:</b> $email </div>
        <div><b>Zpráva:</b> $zprava </div>
        ";
        $mail->send(); 
    }
}
?>
    <div class="container" id="kontaktni-formular">
        <?php if (!$odeslano) { ?>
        <section class="contact-formular">
            <div class="formular">
                <!-- action znamena ze kdyz tiskneme odeslat tak ono nas presmeruje na to id(kontaktni-formular) -->
                <form method="post" action="#kontaktni-formular">
                    <fieldset>
                        <legend>Napište nám</legend>
                        <div class="line-box">
                            <input class="input" type="text" name="jmeno" id="jmeno" placeholder=" " value="<?php echo htmlspecialchars($jmeno) ?>" /> 
                            <label for="jmeno">Jméno</label>
                            <?php
                            $status = "";
                            if ($formularOdeslan) {
                                $status = "ok";
                                if (array_key_exists("jmeno", $chyby)) {
                                    $status = "chyba";
                                    echo "<div class='chybaMessage'>{$chyby['jmeno']}</div>";
                                }
                            }
                            ?>
                            <div class="status <?php echo $status ?>">
                                <i class="spravne fa-solid fa-check"></i>
                                <i class="spatne fa-solid fa-xmark"></i>
                            </div>
                        </div>
                        <div class="line-box">
                            <input class="input" type="text" name="telefon" id="telefon" placeholder=" " value="<?php echo htmlspecialchars($telefon) ?>" /> 
                            <label for="telefon">Telefon</label>
                            <?php
                            $status = "";
                            if ($formularOdeslan) {
                                $status = "ok";
                                if (array_key_exists("telefon", $chyby)) {
                                    $status = "chyba";
                                    echo "<div class='chybaMessage'>{$chyby['telefon']}</div>";
                                }
                            }
                            ?>
                            <div class="status <?php echo $status; ?>">
                                <i class="spravne fa-solid fa-check"></i>
                                <i class="spatne fa-solid fa-xmark"></i>
                            </div>
                        </div>
                        <div class="line-box">
                            <input class="input" type="text" name="email" id="email" placeholder=" " value="<?php echo htmlspecialchars($email) ?>"/> 
                            <label for="email">E-mail</label>
                            <?php
                            $status = "";
                            if ($formularOdeslan) {
                                $status = "ok";
                                if (array_key_exists("email", $chyby)) {
                                    $status = "chyba";
                                    echo "<div class='chybaMessage'>{$chyby['email']}</div>";
                                }
                            }
                            ?>
                            <div class="status <?php echo $status; ?>">
                                <i class="spravne fa-solid fa-check"></i>
                                <i class="spatne fa-solid fa-xmark"></i>
                            </div>
                        </div>
                        <div class="line-box">
                            <textarea class="input" name="zprava" id="zprava" placeholder=" " rows="3"><?php echo htmlspecialchars($zprava) ?></textarea>
                            <label for="zprava">Zpráva</label>
                            <?php
                            $status = "";
                            if ($formularOdeslan) {
                                $status = "ok";
                                if (array_key_exists("zprava", $chyby)) {
                                    $status = "chyba";
                                    echo "<div class='chybaMessage'>{$chyby['zprava']}</div>";
                                } 
                            }
                            ?>
                            <div class="status <?php echo $status ?>">
                                <i class="spravne fa-solid fa-check"></i>
                                <i class="spatne fa-solid fa-xmark"></i>
                            </div>
                        </div>
                        <div class="line-box">
                            <button name="odeslat">Odeslat</button>
                        </div>
                    </fieldset>
                </form>
            </div>
        </section>
        <?php } else { ?>
            <h1>Kontaktní formulář býl úspěšně odeslán</h1>
        <?php } ?>
    </div>

<script>
    // alert(jQuery); // overir jquery
    $("#kontaktni-formular [name]").on("input", (event) => {
        const input = event.currentTarget;
        const nazevInputu = input.getAttribute("name");
        const hodnotaInputu = input.value;
        // конкретно знайти той chybaMessage
        

        let ok = true;
        if (nazevInputu === 'jmeno') {
            // validace jmena
            if (hodnotaInputu.length < 5) {
                ok = false;
            }
        } else if (nazevInputu === "telefon") {
            if (hodnotaInputu.length < 9) {
                ok = false;
            }
        } else if (nazevInputu === "email") {
            if (hodnotaInputu.match(/.+@.+\..+/) === null) {
                ok = false;
            }
        } else if (nazevInputu === "zprava") {
            if (hodnotaInputu.length < 5) {
                ok = false;
            }
        }

        //zvizualizujeme vysledek visualizace
        const statusElement = document.querySelector(`#kontaktni-formular [name=${nazevInputu}]~.status`);
        if (ok) {
            statusElement.className = 'status ok'
        } else {
            statusElement.className = 'status chyba'
        }

        // const chybaElement = document.querySelector(`#kontaktni-formular [name=${nazevInputu}]~.chybaMessage`);
        // if (ok) {
        //     chybaElement.textContent = "";
        // } else {
        //     chybaElement.textContent = `{$chyby[${nazevInputu}]}`;
        // }
    })
</script>