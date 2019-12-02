<?php
/*
  Page     : inscription.php
  Auteur   : Carluccio Dylan
  Fonction : Page d'inscription.
 */
session_start();
include('./mysql.php');
include('./fonction.php');
//variable erreur
if (!isset($erreurNom)) {
    $erreurNom = "";
}
if (!isset($erreurPrenom)) {
    $erreurPrenom = "";
}
if (!isset($erreurEmail)) {
    $erreurEmail = "";
}
if (!isset($erreurDateNaissance)) {
    $erreurDateNaissance = "";
}
if (!isset($erreurPassword)) {
    $erreurPassword = "";
}
if (!isset($erreurPassword2)) {
    $erreurPassword2 = "";
}
if (!isset($resultatsText)) {
    $resultatsText = "";
}
//les membres connectes ne peuvent pas s'inscrire
if (empty($_SESSION['User']['Email'])) {
//si le formulaire est bien ete envoyé
    if (!empty($_POST)) {
//verification formulaire
        //verification du nom
        if (empty($_POST["nom"])) {
            $erreurNom = "le nom est vide.";
        } else {
            $nom = $_POST['nom'];
            //verifie des lettres, espaces et des traits d'unions
            if (!preg_match("/^[a-zA-Z\s-]*$/", $nom)) {
                $erreurNom = "Que des lettres, espaces et des traits d'unions(-) sont acceptés";
            }
            if (strlen($nom) > 25) {
                $erreurNom = "Il faut maximum 25 caractères.";
            }
        }
        //verification du prenom
        if (empty($_POST["prenom"])) {
            $erreurPrenom = "le prenom est vide.";
        } else {
            $prenom = $_POST['prenom'];
            //verifie des lettres, espaces et des traits d'unions
            if (!preg_match("/^[a-zA-Z\s-]*$/", $prenom)) {
                $erreurPrenom = "Que des lettres, espaces et des traits d'unions(-) sont acceptés";
            }
            if (strlen($prenom) > 25) {
                $erreurPrenom = "Il faut maximum 25 caractères.";
            }
        }
        //verification de l'email
        if (empty($_POST["email"])) {
            $erreurEmail = "l'email est vide.";
        } else {
            $email = $_POST['email'];
            //verification du format de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $erreurEmail = "L'email est invalide. (example@mail.ch)";
            }
            if (strlen($email) > 50) {
                $erreurEmail = "Il faut maximum 50 caractères.";
            }
        }
        //verification de la date de naissance
        if (empty($_POST["dateNaissance"])) {
            $erreurDateNaissance = "La date de naissance est vide.";
        } else {
            $dateNaissance = $_POST["dateNaissance"];
            $dateNaissanceExplode = explode('-', $dateNaissance);
            $Annee = $dateNaissanceExplode[0];
            if ($Annee > 2003) {
                $erreurDateNaissance = "il faut être maximum de 2003";
            }
            if ($Annee < 1930) {
                $erreurDateNaissance = "il faut être minimum de 1930";
            }
        }
        //verification du mot de passe
        if (empty($_POST["password"])) {
            $erreurPassword = "le mot de passe est vide.";
        } else {
            $password = $_POST["password"];
            if (strlen($password) < 4) {
                $erreurPassword = "Il faut minimum 4 caractères.";
            }
        }
        //verification du deuxième mot de passe
        if (empty($_POST["password2"])) {
            $erreurPassword2 = "le mot de passe est vide.";
        } else {
            $password2 = $_POST["password2"];
            if (strlen($password2) < 4) {
                $erreurPassword2 = "Il faut minimum 4 caractères.";
            }
        }
        //verification si les mot de passe sont identiques
        if ($_POST["password"] !== $_POST["password2"]) {
            $erreurPassword2 = "Les mots de passes ne correspondent pas";
        }
        if ($erreurNom == '' && $erreurPrenom == '' && $erreurDateNaissance == '' && $erreurPassword == '' && $erreurPassword2 == '') {
            // on verifie si un membre ne possede pas deja le meme email
            dbConnect();
            if (!VerifierEmail($email)) {
                /* on crypte le mot de passe */
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                //variable non déclaré
                $pays = $_POST['pays'];
                $statut = 'Utilisateur';
                $dateCreation = date("Y/m/d");
                $active = "0";
                if (InscriptionUser($nom, $prenom, $email, $pays, $password, $dateNaissance, $statut, $dateCreation, $active)) {
                    $resultatsText = " Merci de votre inscription";
                    include('emailConfirmationInscription.php');

                } else {
                    $resultatsText = "Une erreur s'est produite dans l'insertion des données utilisateurs";
                }

            } else {
                $erreurEmail = "L'email est déjà existant";
                //pour bien afficher les erreurs
                if ($erreurNom === "") {
                    $erreurNom = "&nbsp";
                }
                if ($erreurPrenom === "") {
                    $erreurPrenom = "&nbsp";
                }
                if ($erreurPassword === "") {
                    $erreurPassword = "&nbsp";
                }
                if ($erreurPassword2 === "") {
                    $erreurPassword2 = "&nbsp";
                }
                if ($erreurDateNaissance === "") {
                    $erreurDateNaissance = "&nbsp";
                }
            }
        } else {
            //pour bien afficher les erreurs
            if ($erreurNom === "") {
                $erreurNom = "&nbsp";
            }
            if ($erreurPrenom === "") {
                $erreurPrenom = "&nbsp";
            }
            if ($erreurEmail === "") {
                $erreurEmail = "&nbsp";
            }
            if ($erreurPassword === "") {
                $erreurPassword = "&nbsp";
            }
            if ($erreurPassword2 === "") {
                $erreurPassword2 = "&nbsp";
            }
            if ($erreurDateNaissance === "") {
                $erreurDateNaissance = "&nbsp";
            }
        }
    }//post
} else {
    /* on renvoie sur la page d'accueil */
    header('Location: ../index.php');
}
include '../main/header.php';
include '../main/menu.php';
?>
</nav>
<div class="coverCadre">
</div>
<div id="topBody"></div>
<div class="uk-container uk-margin">
    <section class="centerFlex">
        <h1>Inscription</h1>
        <div id="cadreInscription" class="uk-card uk-card-default uk-width-1-2@m">
            <div class="uk-card-header">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                    </div>
                </div>
            </div>
            <div class="uk-card-body uk-column-1-2">
                <form method="post" action="inscription.php">
                    <div class="uk-margin">
                        <label>Nom</label>
                        <input tabindex="1" required class="uk-input" type="text" name="nom"
                               value="<?php if (isset($_POST['nom'])) {
                                   echo $_POST['nom'];
                               } ?>">
                        <div class="erreur"><?php echo $erreurNom ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Email</label>
                        <input tabindex="2" required class="uk-input" type="text" name="email"
                               value="<?php if (isset($_POST['email'])) {
                                   echo $_POST['email'];
                               } ?>">
                        <div class="erreur"><?php echo $erreurEmail ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Mot de passe</label>
                        <input tabindex="4" required class="uk-input" type="password" name="password">
                        <div class="erreur"><?php echo $erreurPassword ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Pays</label>
                        <select tabindex="6" required class="uk-input" name="pays">
                            <?php
                            if (isset($_POST['pays'])) {
                                echo '<option value="' . $_POST['pays'] . '" selected="selected" >' . $_POST['pays'] . '</option>';
                            } else {
                                echo ' <option value="Suisse" >Suisse </option>';
                            }
                            ?>
                            <option value="Afghanistan">Afghanistan</option>
                            <option value="Afrique_Centrale">Afrique_Centrale</option>
                            <option value="Afrique_du_sud">Afrique_du_Sud</option>
                            <option value="Albanie">Albanie</option>
                            <option value="Algerie">Algerie</option>
                            <option value="Allemagne">Allemagne</option>
                            <option value="Andorre">Andorre</option>
                            <option value="Angola">Angola</option>
                            <option value="Anguilla">Anguilla</option>
                            <option value="Arabie_Saoudite">Arabie_Saoudite</option>
                            <option value="Argentine">Argentine</option>
                            <option value="Armenie">Armenie</option>
                            <option value="Australie">Australie</option>
                            <option value="Autriche">Autriche</option>
                            <option value="Azerbaidjan">Azerbaidjan</option>
                            <option value="Bahamas">Bahamas</option>
                            <option value="Bangladesh">Bangladesh</option>
                            <option value="Barbade">Barbade</option>
                            <option value="Bahrein">Bahrein</option>
                            <option value="Belgique">Belgique</option>
                            <option value="Belize">Belize</option>
                            <option value="Benin">Benin</option>
                            <option value="Bermudes">Bermudes</option>
                            <option value="Bielorussie">Bielorussie</option>
                            <option value="Bolivie">Bolivie</option>
                            <option value="Botswana">Botswana</option>
                            <option value="Bhoutan">Bhoutan</option>
                            <option value="Boznie_Herzegovine">Boznie_Herzegovine</option>
                            <option value="Bresil">Bresil</option>
                            <option value="Brunei">Brunei</option>
                            <option value="Bulgarie">Bulgarie</option>
                            <option value="Burkina_Faso">Burkina_Faso</option>
                            <option value="Burundi">Burundi</option>
                            <option value="Caiman">Caiman</option>
                            <option value="Cambodge">Cambodge</option>
                            <option value="Cameroun">Cameroun</option>
                            <option value="Canada">Canada</option>
                            <option value="Canaries">Canaries</option>
                            <option value="Cap_vert">Cap_Vert</option>
                            <option value="Chili">Chili</option>
                            <option value="Chine">Chine</option>
                            <option value="Chypre">Chypre</option>
                            <option value="Colombie">Colombie</option>
                            <option value="Comores">Colombie</option>
                            <option value="Congo">Congo</option>
                            <option value="Congo_democratique">Congo_democratique</option>
                            <option value="Cook">Cook</option>
                            <option value="Coree_du_Nord">Coree_du_Nord</option>
                            <option value="Coree_du_Sud">Coree_du_Sud</option>
                            <option value="Costa_Rica">Costa_Rica</option>
                            <option value="Cote_d_Ivoire">Côte_d_Ivoire</option>
                            <option value="Croatie">Croatie</option>
                            <option value="Cuba">Cuba</option>
                            <option value="Danemark">Danemark</option>
                            <option value="Djibouti">Djibouti</option>
                            <option value="Dominique">Dominique</option>
                            <option value="Egypte">Egypte</option>
                            <option value="Emirats_Arabes_Unis">Emirats_Arabes_Unis</option>
                            <option value="Equateur">Equateur</option>
                            <option value="Erythree">Erythree</option>
                            <option value="Espagne">Espagne</option>
                            <option value="Estonie">Estonie</option>
                            <option value="Etats_Unis">Etats_Unis</option>
                            <option value="Ethiopie">Ethiopie</option>
                            <option value="Falkland">Falkland</option>
                            <option value="Feroe">Feroe</option>
                            <option value="Fidji">Fidji</option>
                            <option value="Finlande">Finlande</option>
                            <option value="France">France</option>
                            <option value="Gabon">Gabon</option>
                            <option value="Gambie">Gambie</option>
                            <option value="Georgie">Georgie</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Gibraltar">Gibraltar</option>
                            <option value="Grece">Grece</option>
                            <option value="Grenade">Grenade</option>
                            <option value="Groenland">Groenland</option>
                            <option value="Guadeloupe">Guadeloupe</option>
                            <option value="Guam">Guam</option>
                            <option value="Guatemala">Guatemala</option>
                            <option value="Guernesey">Guernesey</option>
                            <option value="Guinee">Guinee</option>
                            <option value="Guinee_Bissau">Guinee_Bissau</option>
                            <option value="Guinee equatoriale">Guinee_Equatoriale</option>
                            <option value="Guyana">Guyana</option>
                            <option value="Guyane_Francaise ">Guyane_Francaise</option>
                            <option value="Haiti">Haiti</option>
                            <option value="Hawaii">Hawaii</option>
                            <option value="Honduras">Honduras</option>
                            <option value="Hong_Kong">Hong_Kong</option>
                            <option value="Hongrie">Hongrie</option>
                            <option value="Inde">Inde</option>
                            <option value="Indonesie">Indonesie</option>
                            <option value="Iran">Iran</option>
                            <option value="Iraq">Iraq</option>
                            <option value="Irlande">Irlande</option>
                            <option value="Islande">Islande</option>
                            <option value="Israel">Israel</option>
                            <option value="Italie">italie</option>
                            <option value="Jamaique">Jamaique</option>
                            <option value="Jan Mayen">Jan Mayen</option>
                            <option value="Japon">Japon</option>
                            <option value="Jersey">Jersey</option>
                            <option value="Jordanie">Jordanie</option>
                            <option value="Kazakhstan">Kazakhstan</option>
                            <option value="Kenya">Kenya</option>
                            <option value="Kirghizstan">Kirghizistan</option>
                            <option value="Kiribati">Kiribati</option>
                            <option value="Koweit">Koweit</option>
                            <option value="Laos">Laos</option>
                            <option value="Lesotho">Lesotho</option>
                            <option value="Lettonie">Lettonie</option>
                            <option value="Liban">Liban</option>
                            <option value="Liberia">Liberia</option>
                            <option value="Liechtenstein">Liechtenstein</option>
                            <option value="Lituanie">Lituanie</option>
                            <option value="Luxembourg">Luxembourg</option>
                            <option value="Lybie">Lybie</option>
                            <option value="Macao">Macao</option>
                            <option value="Macedoine">Macedoine</option>
                            <option value="Madagascar">Madagascar</option>
                            <option value="Madère">Madère</option>
                            <option value="Malaisie">Malaisie</option>
                            <option value="Malawi">Malawi</option>
                            <option value="Maldives">Maldives</option>
                            <option value="Mali">Mali</option>
                            <option value="Malte">Malte</option>
                            <option value="Man">Man</option>
                            <option value="Mariannes du Nord">Mariannes du Nord</option>
                            <option value="Maroc">Maroc</option>
                            <option value="Marshall">Marshall</option>
                            <option value="Martinique">Martinique</option>
                            <option value="Maurice">Maurice</option>
                            <option value="Mauritanie">Mauritanie</option>
                            <option value="Mayotte">Mayotte</option>
                            <option value="Mexique">Mexique</option>
                            <option value="Micronesie">Micronesie</option>
                            <option value="Midway">Midway</option>
                            <option value="Moldavie">Moldavie</option>
                            <option value="Monaco">Monaco</option>
                            <option value="Mongolie">Mongolie</option>
                            <option value="Montserrat">Montserrat</option>
                            <option value="Mozambique">Mozambique</option>
                            <option value="Namibie">Namibie</option>
                            <option value="Nauru">Nauru</option>
                            <option value="Nepal">Nepal</option>
                            <option value="Nicaragua">Nicaragua</option>
                            <option value="Niger">Niger</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Niue">Niue</option>
                            <option value="Norfolk">Norfolk</option>
                            <option value="Norvege">Norvege</option>
                            <option value="Nouvelle_Caledonie">Nouvelle_Caledonie</option>
                            <option value="Nouvelle_Zelande">Nouvelle_Zelande</option>
                            <option value="Oman">Oman</option>
                            <option value="Ouganda">Ouganda</option>
                            <option value="Ouzbekistan">Ouzbekistan</option>
                            <option value="Pakistan">Pakistan</option>
                            <option value="Palau">Palau</option>
                            <option value="Palestine">Palestine</option>
                            <option value="Panama">Panama</option>
                            <option value="Papouasie_Nouvelle_Guinee">Papouasie_Nouvelle_Guinee</option>
                            <option value="Paraguay">Paraguay</option>
                            <option value="Pays_Bas">Pays_Bas</option>
                            <option value="Perou">Perou</option>
                            <option value="Philippines">Philippines</option>
                            <option value="Pologne">Pologne</option>
                            <option value="Polynesie">Polynesie</option>
                            <option value="Porto_Rico">Porto_Rico</option>
                            <option value="Portugal">Portugal</option>
                            <option value="Qatar">Qatar</option>
                            <option value="Republique_Dominicaine">Republique_Dominicaine</option>
                            <option value="Republique_Tcheque">Republique_Tcheque</option>
                            <option value="Reunion">Reunion</option>
                            <option value="Roumanie">Roumanie</option>
                            <option value="Royaume_Uni">Royaume_Uni</option>
                            <option value="Russie">Russie</option>
                            <option value="Rwanda">Rwanda</option>
                            <option value="Sahara Occidental">Sahara Occidental</option>
                            <option value="Sainte_Lucie">Sainte_Lucie</option>
                            <option value="Saint_Marin">Saint_Marin</option>
                            <option value="Salomon">Salomon</option>
                            <option value="Salvador">Salvador</option>
                            <option value="Samoa_Occidentales">Samoa_Occidentales</option>
                            <option value="Samoa_Americaine">Samoa_Americaine</option>
                            <option value="Sao_Tome_et_Principe">Sao_Tome_et_Principe</option>
                            <option value="Senegal">Senegal</option>
                            <option value="Seychelles">Seychelles</option>
                            <option value="Sierra Leone">Sierra Leone</option>
                            <option value="Singapour">Singapour</option>
                            <option value="Slovaquie">Slovaquie</option>
                            <option value="Slovenie">Slovenie</option>
                            <option value="Somalie">Somalie</option>
                            <option value="Soudan">Soudan</option>
                            <option value="Sri_Lanka">Sri_Lanka</option>
                            <option value="Suede">Suede</option>
                            <option value="Suisse">Suisse</option>
                            <option value="Surinam">Surinam</option>
                            <option value="Swaziland">Swaziland</option>
                            <option value="Syrie">Syrie</option>
                            <option value="Tadjikistan">Tadjikistan</option>
                            <option value="Taiwan">Taiwan</option>
                            <option value="Tonga">Tonga</option>
                            <option value="Tanzanie">Tanzanie</option>
                            <option value="Tchad">Tchad</option>
                            <option value="Thailande">Thailande</option>
                            <option value="Tibet">Tibet</option>
                            <option value="Timor_Oriental">Timor_Oriental</option>
                            <option value="Togo">Togo</option>
                            <option value="Trinite_et_Tobago">Trinite_et_Tobago</option>
                            <option value="Tristan da cunha">Tristan de cuncha</option>
                            <option value="Tunisie">Tunisie</option>
                            <option value="Turkmenistan">Turmenistan</option>
                            <option value="Turquie">Turquie</option>
                            <option value="Ukraine">Ukraine</option>
                            <option value="Uruguay">Uruguay</option>
                            <option value="Vanuatu">Vanuatu</option>
                            <option value="Vatican">Vatican</option>
                            <option value="Venezuela">Venezuela</option>
                            <option value="Vierges_Americaines">Vierges_Americaines</option>
                            <option value="Vierges_Britanniques">Vierges_Britanniques</option>
                            <option value="Vietnam">Vietnam</option>
                            <option value="Wake">Wake</option>
                            <option value="Wallis et Futuma">Wallis et Futuma</option>
                            <option value="Yemen">Yemen</option>
                            <option value="Yougoslavie">Yougoslavie</option>
                            <option value="Zambie">Zambie</option>
                            <option value="Zimbabwe">Zimbabwe</option>
                        </select>
                        <div class="bon"><?php echo $resultatsText ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Prénom</label>
                        <input tabindex="1" required class="uk-input" type="text" name="prenom"
                               value="<?php if (isset($_POST['prenom'])) {
                                   echo $_POST['prenom'];
                               } ?>">
                        <div class="erreur"><?php echo $erreurPrenom ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Date de naissance</label>
                        <input tabindex="3" required class="uk-input" type="date" name="dateNaissance"
                               value="<?php if (isset($_POST['dateNaissance'])) {
                                   echo $_POST['dateNaissance'];
                               } ?>">
                        <div class="erreur"><?php echo $erreurDateNaissance ?></div>
                    </div>
                    <div class="uk-margin">
                        <label>Répeter le mot de passe</label>
                        <input tabindex="5" required class="uk-input" type="password" name="password2">
                        <div class="erreur"><?php echo $erreurPassword2 ?></div>
                    </div>
            </div>
            <div class="uk-card-footer">
                <button name="buttonInscription" class="uk-button uk-button-default styleButton">S'inscrire</button>
            </div>
        </div>
        </form>
    </section>
</div>
<?php
include '../main/footer.php';
?>
</html>
