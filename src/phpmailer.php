 
 <?php
    require './vendor/autoload.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    $mijnemail = "jospartijtegendeburgers@gmail.com";
    $onderwerp = "Bevestiging van je klacht";

    echo  " <form action='index.php' method='POST'>
                <label for=''>Naam</label>
                <input type='text' name='Naam'>
                <br>
                <label for='Email'>Email</label>
                <input type='text' name='Email'>
                <br>
                <label for='Beschrijvingklacht'>Omschrijf je klacht</label>
                <input type='text' name='Beschrijvingklacht'>
                <br>
                <input type='submit' value='verstuur'>
            </form>";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $naam = $_POST['Naam'];
        $email = $_POST['Email'];
        $beschrijvingklacht = $_POST['Beschrijvingklacht'];
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = $mijnemail;
            $mail->Password = 'qiqv jplw npif xswt';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            //oplossing voor het probleem waar hij het niet wou versturen
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom($mijnemail,  'Klantenservice');
            $mail->addAddress($email, $naam);
            $mail->addCC($mijnemail);

            $mail->isHTML(false);
            $mail->Subject = 'Bevestiging van je klacht';
            $mail->Body = "Beste $naam,\n\nWe hebben je klacht ontvangen en zullen deze zo snel mogelijk behandelen.\n\nMet vriendelijke groet,\nKlantenservice";
            $mail->send();
            echo 'Bevestigingsmail is verzonden.';
        } catch (Exception $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
        }
    }
    ?>