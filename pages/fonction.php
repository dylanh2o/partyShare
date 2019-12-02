<?php
/*
  Page     : fonction.php
  Auteur   : Carluccio Dylan
  Fonction : Page qui contient toutes les fonctions nécessaire.
 */

function dbConnect()
{
//fonction de connexion à la base
//--------------------------------------------------------------------------
    global $dbc;
    try {
        $dbc = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
    } catch (Exception $e) {
        echo 'Erreur : ' . $e->getMessage() . '<br />';
        echo 'N° : ' . $e->getCode();
        die('Could not connect to MySQL');
    }
}

//--------------------------------------------------------------------------
function InscriptionUser($nom, $prenom, $email, $pays, $password, $dateNaissance, $statut, $dateCreation, $active)
{
//Inscription des utilisateurs
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('INSERT INTO utilisateurs(Nom,Prenom,Email,Pays,MotDePasse,DateNaissance,Statut,DateCreation,Active) VALUES( :nom,:prenom,:email,:pays,:password,:dateNaissance,:statut,:dateCreation,:active)');
    return $req->execute(array(
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'pays' => $pays,
        'password' => $password,
        'dateNaissance' => $dateNaissance,
        'statut' => $statut,
        'dateCreation' => $dateCreation,
        'active' => $active

    ));
}

//--------------------------------------------------------------------------
function AfficherUtilisateur($idUtilisateur)
{
//Affiche un utilisateur avec son id
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM utilisateurs WHERE IdUtilisateur = :idUtilisateur');
    $req->execute(array('idUtilisateur' => $idUtilisateur));
    return $resultat = $req->fetch();
}

//--------------------------------------------------------------------------
function VerifierEmail($email)
{
//Vérifie si un utilisateur possede deja cet email
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM utilisateurs WHERE Email = :email');
    $req->execute(array('email' => $email));
    return $resultat = $req->fetch();
}

//--------------------------------------------------------------------------
function VerifierConnection()
{
//Vérifie si un utilisateur est connecté
//--------------------------------------------------------------------------

    if (isset($_SESSION['User']['Email'])) {
        if ($_SESSION['User']['Email'] != "") {
            return true;
        }
    } else
        return false;
}

//--------------------------------------------------------------------------

function AfficheInformation($email)
{
//affiche toutes les informations de l'utilisateur
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM utilisateurs u WHERE Email = :email');
    $req->execute(array('email' => $email));
    return $req->fetch(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------

function ModifierUtilisateur($UtilisateurDonnees)
{
////modifie un utilisateur
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('UPDATE utilisateurs set Nom = :Nom, Prenom = :Prenom, Pays = :Pays, DateNaissance = :DateNaissance WHERE IdUtilisateur = :IdUtilisateur;');

    $req->execute($UtilisateurDonnees);

}

//--------------------------------------------------------------------------
function AfficheEvenements()
{
//affiche tous les evenements trier par date de creation ou la date n'est pas passé
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM evenements e JOIN utilisateurs u WHERE u.IdUtilisateur=e.Utilisateurs_idUtilisateur AND e.Date > now() order by e.DateCreation DESC');
    $req->execute();
    return $req->fetchall(PDO::FETCH_ASSOC);

}

//--------------------------------------------------------------------------
function InscriptionEvents($titre, $description, $adresse, $codePostal, $ville, $date, $heureD, $heureF, $type, $dateCreation, $idUtilisateur)
{
//Inscription d'un evenements
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('INSERT INTO evenements (Titre,Description,Adresse,CodePostal,Ville,Date,HeureDebut,HeureFin,Type,DateCreation,Utilisateurs_IdUtilisateur) VALUES ( :titre,:description,:adresse,:codePostal,:ville,:date,:heureDebut,:heureFin,:type,:dateCreation,:idUtilisateur)');
    return $req->execute(array(
        'titre' => $titre,
        'description' => $description,
        'adresse' => $adresse,
        'codePostal' => $codePostal,
        'ville' => $ville,
        'date' => $date,
        'heureDebut' => $heureD,
        'heureFin' => $heureF,
        'type' => $type,
        'dateCreation' => $dateCreation,
        'idUtilisateur' => $idUtilisateur

    ));
}

//--------------------------------------------------------------------------
function AfficherEvenementsCree($idUtilisateur)
{
//Affiche les evenements crée d'un utilisateur
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare("SELECT *  FROM evenements e  join utilisateurs u WHERE e.Utilisateurs_idUtilisateur= :idUtilisateur and u.IdUtilisateur=:idUtilisateur");
    $req->execute(array('idUtilisateur' => $idUtilisateur));
    return $req->fetchall(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function CompterEvenements($idUtilisateur)
{
//compte les evenements d'un utilisateur
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare("SELECT count(*) as Count FROM evenements e  join utilisateurs u WHERE e.Utilisateurs_idUtilisateur= :idUtilisateur and u.IdUtilisateur=:idUtilisateur");
    $req->execute(array('idUtilisateur' => $idUtilisateur));
    return $req->fetch(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function AfficherEvenementsRejoint($idUtilisateur)
{
//Afficher evenements que l'utilisateur à rejoints
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare("SELECT e.* FROM utilisateurs u ,evenements e JOIN participer p WHERE  p.Utilisateurs_idUtilisateur=:idUtilisateur AND p.Utilisateurs_idUtilisateur=u.IdUtilisateur AND u.IdUtilisateur!=e.Utilisateurs_idUtilisateur AND e.IdEvenements=p.Evenements_idEvenements;");
    $req->execute(array('idUtilisateur' => $idUtilisateur));
    return $req->fetchall(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function CompterEvenementsRejoint($idUtilisateur)
{
//compte les evenements rejoint d'un utilisateur
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare("SELECT count(*) as Count FROM utilisateurs u JOIN participer p WHERE  p.Utilisateurs_idUtilisateur=:idUtilisateur AND p.Utilisateurs_idUtilisateur=u.IdUtilisateur;");
    $req->execute(array('idUtilisateur' => $idUtilisateur));
    return $req->fetch(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function VerifierEvenementsRejoint($idUtilisateur, $idEvenements)
{
//Vérifie si un utilisateur a deja rejoint un evenement
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT u.* FROM utilisateurs u ,evenements e JOIN participer p WHERE  p.Utilisateurs_idUtilisateur=:idUtilisateur AND p.Utilisateurs_idUtilisateur=u.IdUtilisateur AND u.IdUtilisateur!=e.Utilisateurs_idUtilisateur AND p.Evenements_idEvenements=:idEvenements');
    $req->execute(array('idUtilisateur' => $idUtilisateur, 'idEvenements' => $idEvenements));
    return $req->fetch();
}

//--------------------------------------------------------------------------
function VerifierEvenementCreateur($idUtilisateur, $idEvenements)
{
//Vérifie si c'est l'evenement d'un membre
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM utilisateurs u JOIN evenements e WHERE e.Utilisateurs_idUtilisateur=:idUtilisateur and e.IdEvenements=:idEvenements AND u.IdUtilisateur=:idUtilisateur;');
    $req->execute(array('idUtilisateur' => $idUtilisateur, 'idEvenements' => $idEvenements));
    return $req->fetch();
}

//--------------------------------------------------------------------------
function RejoindreEvenement($idUtilisateur, $idEvenements)
{
//Inscription d'un utilisateur a un evenement
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('INSERT INTO participer (Utilisateurs_idUtilisateur,Evenements_idEvenements) VALUES ( :idUtilisateur, :idEvenements)');
    return $req->execute(array(
        'idUtilisateur' => $idUtilisateur,
        'idEvenements' => $idEvenements

    ));
}

function SupprimerEvenement($idEvenements)
{
    //Supprimer l'evenement d'un utilisateur
    //--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('delete from evenements where  idEvenements=:idEvenements');
    $req->execute(array('idEvenements' => $idEvenements));
    return $req->fetch(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function AfficherEvenementModifier($idEvenements)
{
//afficher evenement à modifier
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare("SELECT *  FROM evenements e   WHERE IdEvenements=:idEvenements");
    $req->execute(array('idEvenements' => $idEvenements));
    return $req->fetch(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function ModifierEvenement($EvenementDonnees)
{
////met à jours un evenement
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('UPDATE evenements set Titre = :Titre, Description = :Description, Adresse = :Adresse, CodePostal = :CodePostal, Ville = :Ville, Date = :Date, HeureDebut = :HeureDebut, HeureFin = :HeureFin WHERE IdEvenements = :IdEvenements;');

    $req->execute($EvenementDonnees);

}

//--------------------------------------------------------------------------
function SupprimerRejoint($idUtilisateur, $idEvenements)
{
    //Supprime l'utilisateur de l'evenment qu'il à rejoint
    //--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('delete from participer where participer.Utilisateurs_idUtilisateur = :idUtilisateur AND participer.Evenements_idEvenements= :idEvenements');
    $req->execute(array('idUtilisateur' => $idUtilisateur, 'idEvenements' => $idEvenements));
    return $req->fetch(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function InsertionToken($token, $date, $idUtilisateur)
{
//Inscription d'un token pour changer de mot de passe
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('INSERT INTO changemotdepasse (Token,Date,Utilisateurs_IdUtilisateur) VALUES ( :token,:date,:idUtilisateur)');
    return $req->execute(array(
        'token' => $token,
        'date' => $date,
        'idUtilisateur' => $idUtilisateur

    ));
}

//--------------------------------------------------------------------------
function VerifierToken($idUtilisateur, $token)
{
//Vérifie le token pour changer le mot de passe depuis son compte
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM changemotdepasse  WHERE Utilisateurs_idUtilisateur=:idUtilisateur AND Token=:token ;');
    $req->execute(array('idUtilisateur' => $idUtilisateur, 'token' => $token));
    return $req->fetch();
}

//--------------------------------------------------------------------------
function ModifierMotDePasse($UtilisateurDonnees)
{
//modifie un mot de passe
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('UPDATE utilisateurs set MotDePasse = :password WHERE IdUtilisateur = :idUtilisateur;');

    $req->execute($UtilisateurDonnees);

}

//--------------------------------------------------------------------------
function VerifierTokenEmail($email, $token)
{
//Vérifie si le token et email bon pour changer le mot de passe(mot de passe oublié)
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM changemotdepasse c JOIN utilisateurs u WHERE c.Utilisateurs_idUtilisateur=u.IdUtilisateur AND u.Email=:email AND c.Token=:token;');
    $req->execute(array('email' => $email, 'token' => $token));
    return $req->fetch();
}

//--------------------------------------------------------------------------
function RechercherEvenements($recherche)
{
//recherche dans évènements
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare("SELECT * FROM evenements e WHERE e.Titre LIKE CONCAT('%', :recherche, '%') OR e.Description LIKE CONCAT('%', :recherche, '%')");
    $req->execute(array(':recherche' => $recherche));
    return $req->fetchAll(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------
function InsertionEmailToken($token, $date, $idUtilisateur)
{
//Inscription d'un token pour confirmer l'inscription par email
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('INSERT INTO confirmationemail (Token,Date,Utilisateurs_IdUtilisateur) VALUES ( :token, :date, :idUtilisateur)');
    return $req->execute(array(
        'token' => $token,
        'date' => $date,
        'idUtilisateur' => $idUtilisateur

    ));
}

//--------------------------------------------------------------------------
function VerifierConfirmerInscription($idUtilisateur, $token)
{
//Vérifie si le token et l'id correspondent pour confirmer l'utilisateur
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('SELECT * FROM confirmationemail c WHERE Utilisateurs_idUtilisateur=:idUtilisateur AND Token=:token ;');
    $req->execute(array('idUtilisateur' => $idUtilisateur, 'token' => $token));
    return $req->fetch();
}

//--------------------------------------------------------------------------
function ConfirmerUtilisateur($UtilisateurDonnees)
{
//modifie un mot de passe
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare('UPDATE utilisateurs set Active = "1" WHERE IdUtilisateur = :idUtilisateur');
    $req->execute($UtilisateurDonnees);
    print_r($req);
}

//--------------------------------------------------------------------------
function CompteUtilisateursEtEvents()
{
//Compte les utilisateurs et les evenements de mon site
//--------------------------------------------------------------------------
    global $dbc;
    $req = $dbc->prepare("SELECT count(*) as Compte FROM utilisateurs UNION SELECT count(*) as Compte FROM evenements");
    $req->execute();
    return $req->fetchall(PDO::FETCH_ASSOC);
}

//--------------------------------------------------------------------------