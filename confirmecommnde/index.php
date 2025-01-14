<?php 
session_start();
require "../DbConnect/index.php";

if(isset($_POST['confirmer'])){

    // var_dump($_FILES['photo']);
    // $NameFile=$_FILES['photo']['name'];//afficher valeur key name //
    // $type_extention=pathinfo($NameFile,PATHINFO_EXTENSION);//par example png :type//
    // $name_file=md5(rand()).".".$type_extention; //changer nom image//
        
        $res=$db->prepare("INSERT INTO commandes (`nom`, `prenom`,`tlf_num` , `adresse_email`, `ville`, `prix_total`, `status`, `code_commande`,`user_id`) VALUES
                                                 (:nom, :prenom ,:tlf_num , :Email , :ville  ,:prix_totale , :status ,:code_commande,:user_id );");
        $res->execute([
             "nom"=>$_SESSION['nom'],
             "prenom"=>$_SESSION['prenom'],
             "Email"=>$_SESSION['Email'],
             "ville"=>$_SESSION['ville'],
             "tlf_num"=>$_SESSION['tlf_num'],
             "status"=>0,
             "prix_totale"=>0, 
             "code_commande"=>mt_rand(10,9999),
             "user_id"=> $_SESSION['idClient']
        ]);
        
        $id_commande = $db->lastInsertId(); 

        foreach($_SESSION['carte'] as $items){
            $sql=$db->prepare("INSERT INTO lignecommandes (`quantity`, `prix_ligne_commandes`, `IdCommande`, `IdProduit`) VALUES (?,?,?,?)");
            $sql->execute([
                $items[1],
                ($items[2] * $items[1]),
                $id_commande,
                $items[0]
            ]);
        }
        unset($_SESSION['carte']);
        header("location:../felicitations?message= confirme successfully &type=success");
    }





$page_titel = "page de confirme";
$template = "confirmecommnde";
include "../layout.phtml";
?>