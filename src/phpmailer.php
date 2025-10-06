 
 <?php
 
require __DIR__ . '../vendor/autoload.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Dotenv\Dotenv;

// .env staat in dezelfde directory als index.php
$dotenv = Dotenv::createImmutable(__DIR__ . '../.env'); // root van project

// echo "Zoekt naar .env in: " . __DIR__ . "<br>"; 
// echo "Bestand bestaat: " . (file_exists(__DIR__ . '/.env') ? 'JA' : 'NEE') . "<br>";

$dotenv->load();

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
            $mail->Username = $_ENV['MIJN_EMAIL'];
            //$_ENV om wachtwoord uit de .env te halen
            $mail->Password = $_ENV['EMAIL_PASSWORD'];
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
            $mail->setFrom($_ENV['MIJN_EMAIL'], 'Klantenservice');
            $mail->addAddress($email, $naam);

            $mail->addCC($_ENV['MIJN_EMAIL']);

            $mail->isHTML(false);
            $mail->Subject = 'Bevestiging van je klacht';
            $mail->Body = "Beste $naam,\n\nWe hebben je klacht ontvangen en zullen deze zo snel mogelijk behandelen.\n\nMet vriendelijke groet,\nKlantenservice";
            $mail->send();

            $log = new Logger('mailer');
            $logFile = '../Log/info.log';

            $log->pushHandler(new StreamHandler($logFile, Level::Info));
            $log->info('E-mail is verzonden naar ' . $email . $beschrijvingklacht);
            $log->debug('debug');

            $log->warning('Dit is een waarschuwings bericht');
            $log->error('Dit is een error');



            echo 'Bevestigingsmail is verzonden.';
        } catch (Exception $e) {
            echo "Er is een fout opgetreden: " . $e->getMessage();
        }
    }
    ?>