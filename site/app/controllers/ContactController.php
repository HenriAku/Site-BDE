<?php

require_once './app/core/Controller.php';
require_once './app/trait/FormTrait.php';
require_once './vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends Controller
{
    use FormTrait;
    
    public function index()
    {
        $this->view('/contact/contact.html.twig');
    }

    public function create() {
        $data = $this->getAllPostParams(); 
        $errors = [];

        if (!empty($data)) {
            try {
                // Validation des champs
                if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Un email valide est requis.';
                }

                if (empty($data['objet'])) {
                    $errors[] = 'Un objet est requis.';
                }

                if (empty($data['msg'])) {
                    $errors[] = 'Un message est requis.';
                }

                if (strlen($data['msg']) < 10) {
                    $errors[] = 'Entrez un message plus long > 10';
                }

                if (!empty($errors)) {
                    throw new Exception(implode(', ', $errors));
                }

                // Configuration de PHPMailer
                $mail = new PHPMailer(true);
                $mail->SMTPDebug = 2; // Active le débogage pour voir les erreurs détaillées
                
                // Paramètres du serveur SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'bde.informatiquelh@gmail.com';
                $mail->Password = 'cbse vsyc dbea vlgc'; // Mot de passe corrigé sans espaces, remplacez par le vôtre si différent
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Destinataire et expéditeur
                $mail->setFrom($data['email'], 'Formulaire Contact');
                $mail->addAddress('bde.informatiquelh@gmail.com');

                // Contenu de l'email
                $mail->isHTML(true);
                $mail->Subject = $data['objet'];
                $mail->Body    = '<h2>Nouveau message de contact</h2>' .
                               '<p><strong>Email:</strong> ' . htmlspecialchars($data['email']) . '</p>' .
                               '<p><strong>Message:</strong> ' . nl2br(htmlspecialchars($data['msg'])) . '</p>';
                $mail->AltBody = "Nouveau message de contact\n" .
                               "Email: " . $data['email'] . "\n" .
                               "Message: " . $data['msg'];

                // Envoi de l'email
                $mail->send();
                
                $success = 'Votre message a été envoyé avec succès !';

            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage());
                if (empty($errors[0])) {
                    $errors[] = "Erreur lors de l'envoi du mail: " . $mail->ErrorInfo;
                }
            }
        }

        $this->view('/contact/contact.html.twig',  [
            'data' => $data,
            'errors' => $errors,
            'success' => $success ?? null
        ]);
    }
}