<?php
require_once './app/core/Controller.php';
require_once './app/repositories/PanierRepository.php';
require_once './app/repositories/UserRepository.php';
require_once './app/repositories/ProduitRepository.php';
require_once './app/trait/FormTrait.php';
require_once './app/trait/AuthTrait.php';
require_once './app/services/AuthService.php';
require_once './vendor/autoload.php'; 

use PHPMailer\PHPMailer\PHPMailer;

class PanierController extends Controller {

    use FormTrait;

    public function index() 
    {
        $PanierRepo = new PanierRepository();

        $repo = new PanierRepository();
        $paniers = $repo->findAll();

        $authServ = new AuthService();

        $nomProd = $repo->getProduit($paniers);

        $servUser = new AuthService();
        if($servUser->getUser() === null)
        {
            $this->view('/panier/index.html.twig', ['paniers' => $paniers, 'user' => $user, 'admin' => null, 'produits' => $nomProd]);

        }else{
            $user = $servUser->getUser();
            $perm = $user->getAdmin();
            
            $this->view('/panier/index.html.twig', ['paniers' => $paniers, 'user' => $user, 'admin' => $perm, 'produits' => $nomProd]);
        }

    }

    public function achete($userId, $produit_id, $panierQte, $panierId) {
        $repo = new PanierRepository();
        $userRepo = new UserRepository();
        $prodRepo = new ProduitRepository();
        
        $produit = $prodRepo->findById($produit_id);
        $user = $userRepo->findById($userId);
        
        $repo->achete($userId, $produit_id, $panierQte);
        $repo->delete($panierId);
        
        $this->envoyerMailConfirmation($user, $produit, $panierQte);
    }


    public function acheteToutPanier($userId): bool {
        $repo = new PanierRepository();
        $prodRepo = new ProduitRepository();
        $userRepo = new UserRepository();
        $paniers = $repo->findAll();
        $user = $userRepo->findById($userId);
        
        $total = 0;
        $detailsAchats = [];
        
        foreach($paniers as $panier) {
            if($panier->getn_user() == $userId) {
                $produit = $prodRepo->findById($panier->getn_produit());
                $repo->achete($userId, $panier->getn_produit(), $panier->getqte());
                $repo->delete($panier->getn_panier());
                

                $total += $produit->getPrice() * $panier->getqte();
                $detailsAchats[] = [
                    'nom' => $produit->getName(),
                    'quantite' => $panier->getqte(),
                    'prix_unitaire' => $produit->getPrice(),
                    'total' => $produit->getPrice() * $panier->getqte()
                ];
            }
        }
        
        $this->envoyerMailConfirmationGlobal($user, $detailsAchats, $total);
        
        return true;
    }

    private function envoyerMailConfirmation($user, $produit, $quantite) {
        $mail = new PHPMailer(true);
        
        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bde.informatiquelh@gmail.com';
            $mail->Password = 'cbse vsyc dbea vlgc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            // Destinataires
            $mail->setFrom('bde.informatiquelh@gmail.com', 'BDE Informatique');
            $mail->addAddress($user->getEmail(), $user->getFirstname().' '.$user->getLastname());
            
            // Contenu
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de votre achat';
            
            $mail->Body = "
                <h2>Confirmation d'achat</h2>
                <p>Merci pour votre achat sur la boutique du BDE Informatique !</p>
                
                <h3>Détails de votre commande :</h3>
                <table border='1' cellpadding='10'>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                    <tr>
                        <td>{$produit->getName()}</td>
                        <td>{$quantite}</td>
                        <td>{$produit->getPrice()} €</td>
                        <td>".($produit->getPrice() * $quantite)." €</td>
                    </tr>
                </table>
                
                <p>Total payé : <strong>".($produit->getPrice() * $quantite)." €</strong></p>
                
                <p>Cordialement,<br>L'équipe du BDE Informatique</p>
            ";
            
            if ($mail->send()) {
                $this->setNotification('Un reçu a été envoyé à votre adresse email');
            } else {
                $this->setNotification("L'email n'a pas pu être envoyé", 'error');
            }
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email: ".$e->getMessage());
        }
    }
    
    private function envoyerMailConfirmationGlobal($user, $detailsAchats, $total) {
        $mail = new PHPMailer(true);
        
        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'bde.informatiquelh@gmail.com';
            $mail->Password = 'cbse vsyc dbea vlgc';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('bde.informatiquelh@gmail.com', 'BDE Informatique');
            $mail->addAddress($user->getEmail(), $user->getFirstname().' '.$user->getLastname());
            
            $produitsRows = "";
            foreach ($detailsAchats as $achat) {
                $produitsRows .= "
                    <tr>
                        <td>{$achat['nom']}</td>
                        <td>{$achat['quantite']}</td>
                        <td>{$achat['prix_unitaire']} €</td>
                        <td>{$achat['total']} €</td>
                    </tr>
                ";
            }
            
            $mail->isHTML(true);
            $mail->Subject = 'Confirmation de votre commande globale';
            
            $mail->Body = "
                <h2>Confirmation de commande</h2>
                <p>Merci pour votre achat sur la boutique du BDE Informatique !</p>
                <h3>Détails de votre commande :</h3>
                <table border='1' cellpadding='10'>
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Total</th>
                    </tr>
                    {$produitsRows}
                    <tr>
                        <td colspan='3' align='right'><strong>Total général</strong></td>
                        <td><strong>{$total} €</strong></td>
                    </tr>
                </table>
                <p>Cordialement,<br>L'équipe du BDE Informatique</p>
            ";
            
            if ($mail->send()) {
                $this->setNotification('Un reçu a été envoyé à votre adresse email');
            } else {
                $this->setNotification("L'email n'a pas pu être envoyé", 'error');
            }
        } catch (Exception $e) {
            error_log("Erreur d'envoi d'email: " . $e->getMessage());
            echo "Erreur lors de l'envoi : " . $e->getMessage(); // Afficher l'erreur
        }
    }

    public function delete($panier_id)
    {
        $repo = new PanierRepository();
        $repo->delete($panier_id);
    }

    private function setNotification($message, $type = 'success') {
        $_SESSION['notification'] = [
            'message' => $message,
            'type' => $type
        ];
    }
}