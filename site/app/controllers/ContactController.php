<?php

require_once './app/core/Controller.php';
require_once './app/trait/FormTrait.php';
require_once './vendor/autoload.php';
require_once './app/services/AuthService.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends Controller
{
    use FormTrait;

    public function create() {
        $log = $this->isLoggedIn();
        if($this->isLoggedIn())
        {
            $authService = new AuthService();
            $user = $authService->getUser();
        }
  

        $data = $this->getAllPostParams(); 
        $errors = [];

        if (!empty($data)) {
            try {
                // Validation des champs

                if(!$user){
                    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                        $errors[] = 'Un email valide est requis.';
                    }
                }else
                {
                    $data['email']=$user->getEmail();
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
                $mail->SMTPDebug = 0; 
                
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'bde.informatiquelh@gmail.com';
                $mail->Password = 'cbse vsyc dbea vlgc';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($data['email'], 'Formulaire Contact');
                $mail->addAddress('bde.informatiquelh@gmail.com');

                $mail->isHTML(true);
                $mail->Subject = $data['objet'];
                $mail->Body    = '<h2>Nouveau message de contact</h2>' .
                               '<p><strong>Email:</strong> ' . htmlspecialchars($data['email']) . '</p>' .
                               '<p><strong>Message:</strong> ' . nl2br(htmlspecialchars($data['msg'])) . '</p>';
                $mail->AltBody = "Nouveau message de contact\n" .
                               "Email: " . $data['email'] . "\n" .
                               "Message: " . $data['msg'];

                $mail->send();
                
                $success = 'Votre message a été envoyé avec succès !';

            } catch (Exception $e) {
                $errors = explode(', ', $e->getMessage());
                if (empty($errors[0])) {
                    $errors[] = "Erreur lors de l'envoi du mail: " . $mail->ErrorInfo;
                }
            }
        }

        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/contact/contact.html.twig',  [
                'data' => $data,
                'errors' => $errors,
                'success' => $success ?? null,
                'log' => $log,
                'admin' => null
            ]);

        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            
            $this->view('/contact/contact.html.twig',  [
                'data' => $data,
                'errors' => $errors,
                'success' => $success ?? null,
                'log' => $log,
                'admin' => $perm
            ]);
        }
    }

    public function isLoggedIn():bool
    {
        $authService = new AuthService();

        return $authService-> isLoggedIn();
    }
}