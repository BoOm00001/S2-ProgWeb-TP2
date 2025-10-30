<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

function obtenirConnexion()
{
    $hote = 'localhost'; 
    $port = '3306';
    $utilisateur = 'root'; 
    $nomBD = 'tp2'; 
    $motDePasse =''; 

    try {
       $connexion = new PDO(
           'mysql:host=' . $hote . ';port=' . $port .
           ';dbname=' . $nomBD, 
           $utilisateur, $motDePasse);
       $connexion->exec("SET CHARACTER SET utf8");
       $mode = PDO::FETCH_ASSOC; // Collection d'association
       //$mode = PDO::FETCH_OBJ;   // Objet PHP
       $connexion->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $mode); 

       return $connexion;
    } catch(Exception $exception) {
        echo 'Erreur : ' . $exception->getMessage() . '<br>';
        echo 'N° : ' . $exception->getCode();
        die();
    }    
}

Route::get('/', function () {
    session_start();
    
    if(!isset($_SESSION['cours'])) {
        $connexion = obtenirConnexion();
        $requeteSQL = $connexion->query('SELECT * FROM TCours ORDER BY session, numeroCours');
        $cours = $requeteSQL->fetchAll();
        $requeteSQL->closeCursor();        
        $connexion = null;
        
        $_SESSION['cours'] = $cours;
    }
    
    return redirect('/simulerConnection');
});

Route::get('/simulerConnection', function() {
    $connexion = obtenirConnexion();
    $requeteSQL = $connexion->query('SELECT * FROM TEleve');
    $eleves = $requeteSQL->fetchAll();
    $requeteSQL->closeCursor();
    $connexion = null;
    
    return view('listeConnections', ['eleves' => $eleves]);
});

Route::get('/simulerConnection/{matricule}', function($matricule) {
    session_start();
    
    $connexion = obtenirConnexion();
    $requeteSQL = $connexion->prepare('SELECT * FROM TEleve WHERE matricule = :matricule');
    $requeteSQL->execute(['matricule' => $matricule]);
    $eleve = $requeteSQL->fetch();
    $requeteSQL->closeCursor(); 
    $connexion = null;
    
    $_SESSION['eleve'] = $eleve;
    
    return redirect('/inscrireDemande');
});

Route::get('/inscrireDemande', function() {
    session_start();
    
    $cours = $_SESSION['cours'];
    $eleve = $_SESSION['eleve'];
    
    return view('formulaireDemande', ['cours' => $cours,
                                        'eleve' => $eleve]);
});

Route::post('/inscrireDemande', function(Request $requeteHTTP) {
    session_start();
    // valider si demande en cours...
    $connexion = obtenirConnexion();
    
    $numeroCours = $requeteHTTP->input('numeroCours');
    $matriculeTutore = $_SESSION['eleve']['matricule'];
    
    $requeteSQL = $connexion->prepare('SELECT COUNT(*) as demandeActive FROM TDemande WHERE matriculeTutore = :matriculeTutore AND numeroCours = :numeroCours AND commentaireTuteur IS NULL');
    $requeteSQL->execute(['matriculeTutore' => $matriculeTutore,
                       'numeroCours' => $numeroCours]);
    $demandeActive = $requeteSQL->fetch()['demandeActive'];
    $requeteSQL->closeCursor();
    
    if($demandeActive == 1) {
        $statut = 'echec';
    }
    else {
        $description = $requeteHTTP->input('description');
        
        $requeteSQL = $connexion->prepare('INSERT INTO TDemande (matriculeTutore, numeroCours,descriptionDemande) VALUES (:matriculeTutore, :numeroCours, :description)');
        $requeteSQL->execute(array('matriculeTutore' => $matriculeTutore,
                            'numeroCours' => $numeroCours,
                            'description' => $description));
        
        $statut = 'succes';
    }
    
    $connexion = null;
    
    return view('bilanInscription', ['statut' => $statut]);
});

Route::get('/accrediter', function() {
    // lister les élèves qui ne sont pas tuteur
    $connexion = obtenirConnexion();
    
    $requeteSQL = $connexion->query('SELECT matricule, nom, prenom FROM TEleve WHERE estTuteur IS FALSE ORDER BY matricule');
    $eleves = $requeteSQL->fetchAll();
    $requeteSQL->closeCursor();
    $connexion = null;
    
    return view('futursTuteurs', ['eleves' => $eleves]);
});

Route::get('/accrediter/{matricule}', function($matricule) {
    $connexion = obtenirConnexion();
    
    $requeteSQL = $connexion->prepare('SELECT * FROM TEleve WHERE matricule = :matricule');
    $requeteSQL->execute(['matricule' => $matricule]);
    $eleve = $requeteSQL->fetch();
    $requeteSQL->closeCursor();
    
    $requeteSQL = $connexion->prepare('UPDATE TEleve SET estTuteur = true WHERE matricule = :matricule');
    $resultats = $requeteSQL->execute(['matricule' => $matricule]);
    $connexion = null;
    
    return view('confirmationAccreditation', ['eleve' => $eleve]);
});

Route::get('/demandesTuteur', function(){
    session_start();
    $connexion = obtenirConnexion();
    $reponse = null;
    $matriculeTuteur = null;
    $requetteSQL = null;
    $demandesEnCours=null;
    $eleve= null;
    $demandesComplete = null;

    if(!isset($_SESSION['eleve']))
    {
        $reponse = redirect('/simulerConnection');
    } 
    else
    {
        if($_SESSION['eleve']['estTuteur'] != 1)
        {
            $reponse = view('/elevePasTuteur');
        } 
        else
        {
            $tuteur = $_SESSION['eleve'];
            $matriculeTuteur = $tuteur['matricule'];
            $requetteSQL =  $connexion ->prepare(
                'SELECT TDemande.*, prenom, nom FROM TDemande ' .
                'INNER JOIN TEleve ON TDemande.matriculeTutore = TEleve.matricule' .
                ' INNER JOIN TCours ON TCours.numeroCours = TDemande.numeroCours ' .
                'WHERE matriculeTuteur = :matriculeTuteur ' .
                'AND commentaireTuteur IS NULL ' .
                'ORDER BY session, numeroCours, matriculeTutore'         
             );

             $requetteSQL->execute(['matriculeTuteur' => $matriculeTuteur]);
             $demandesEnCours = $requetteSQL ->fetchAll();
             $requetteSQL -> closeCursor();
         
             $requetteSQL = $connexion ->query(
              'SELECT TDemande.*, prenom, nom FROM TDemande '.
              'INNER JOIN TEleve ON TDemande.matriculeTutore = TEleve.matricule  '.
              'INNER JOIN TCours ON TCours.numeroCours = TDemande.numeroCours '.
              'WHERE commentaireTuteur IS NOT NULL  '.
              'ORDER BY session, numeroCours, matriculeTutore'
             );
    
             $demandesComplete = $requetteSQL ->fetchAll();
             $requetteSQL -> closeCursor(); 

             $eleve = $_SESSION['eleve'];
         
             $tuteur =  [
                 'prenom'   => $tuteur['prenom'],
                 'nom'      => $tuteur['nom'],
                 'matricule' => $tuteur['matricule'], 
             ];
        
            $reponse =  view('/demandesTuteur' , ['actives' => $demandesEnCours,
            'completees' => $demandesComplete,
            'tuteur' =>  $tuteur]);
        }
    }
     return $reponse;
});


Route::GET('/completerRapport/{numeroDemande}',function($numeroDemande){
session_start();

$eleve = null;
$connection = null;
$requetteSQL = null;
$demande = null;
$tutore = null;
$reponse = null;
$matriculeTutore = null;

if(!isset($_SESSION['eleve']))
{
    $reponse = redirect('simulerConnection');
}
else
{
    $eleve = $_SESSION['eleve'];
    $connection = obtenirConnexion();

    $requetteSQL = $connection ->prepare(
    'SELECT * FROM Tdemande ' .
    'WHERE numeroDemande = :numeroDemande'
    );

   $requetteSQL -> execute(['numeroDemande' => $numeroDemande]);
   $demande = $requetteSQL -> fetch();
   $requetteSQL ->closeCursor();
   $_SESSION['demande'] = $demande;

   $matriculeTuteur = $demande['matriculeTuteur'];
   $matriculeTutore = $demande['matriculeTutore'];
    if($eleve['matricule'] != $matriculeTuteur )
    {
        $reponse = view('demandeNonAssigneTuteur');
    }
    else
    {
       $requetteSQL = $connection->prepare(
        'SELECT * FROM TEleve ' . 
        'WHERE matricule = :matriculeTutore'
        );

        $requetteSQL -> execute(['matriculeTutore' =>   $matriculeTutore]);
        $tutore = $requetteSQL ->fetch();
        $requetteSQL -> closeCursor();
        
        $reponse = view('completerRapport',['demande' => $demande, 'tutore' => $tutore]);
    }
}
    return $reponse;
});

Route::POST('/completerRapport', function(Request $donnes){
    session_start();

    $demande = null;
    $reponse = null;
    $requetteSQL = null;
    $commentaireTuteur = null;
    $numeroDemande = null;

    $connection = obtenirConnexion();
    if(!isset($_SESSION['demande']))
    {
        $reponse = view('/simulerConnection') ;
    } 
    else
    {
        $demande = $_SESSION['demande'];
        $requetteSQL = $connection ->prepare(
            'UPDATE TDemande '.
            'SET commentaireTuteur = :commentaireTuteur '.
            'WHERE numeroDemande = :numeroDemande'
        );
    
        $commentaireTuteur = $donnes->input('commentaireTuteur');
        $numeroDemande = $demande['numeroDemande'];
        $requetteSQL->execute(['commentaireTuteur' => $commentaireTuteur, 'numeroDemande' => $numeroDemande]);
        $reponse = redirect('confirmationCompleterDemande');
    }
    return $reponse;
});

Route::get('/confirmationCompleterDemande', function(){
    return view('/confirmationCompleterDemande');
});

Route::get('/demandesDisponibles', function() {
    session_start();
    
    $vue = '';  
    
    if (!$_SESSION['eleve']['estTuteur']) {
        $vue = view('erreur', [
            'message' => 'Vous devez être accrédité comme tuteur pour consulter les demandes'
        ]);
    } else {
        $connexion = obtenirConnexion();
        
        $requeteSQL = $connexion->query(
            'SELECT TDemande.*, session 
            FROM TDemande 
            INNER JOIN TCours ON TDemande.numeroCours = TCours.numeroCours 
            WHERE matriculeTuteur IS NULL 
            ORDER BY session, numeroCours'
        );
        $demandes = $requeteSQL->fetchAll();
        $requeteSQL->closeCursor();
        
        $connexion = null;
        
        $vue = view('demandesDisponibles', [
            'demandes' => $demandes
        ]);
    }
    
    return $vue;
});

// Pour gérer assignerDemande sans paramètre
Route::get('/assignerDemande', function() {
    return view('erreur', [
        'message' => 'Le numéro de la demande est invalide',
        'redirection' => '/demandesDisponibles'
    ]);
});

// Avec paramètre
Route::get('/assignerDemande/{numeroDemande}', function($numeroDemande) {
    session_start();
    $reponse = null;
    
    if (!isset($_SESSION['eleve'])) {
        $reponse = redirect('/simulerConnection');
    }
    elseif (!$_SESSION['eleve']['estTuteur']) {
        $reponse = view('erreur', [
            'message' => 'Vous devez être accrédité comme tuteur pour assigner une demande',
            'redirection' => '/demandesDisponibles'
        ]);
    } 
    else {
        $connexion = obtenirConnexion();
        
        if (!is_numeric($numeroDemande)) {
            $reponse = view('erreur', [
                'message' => 'Le numéro de la demande est invalide',
                'redirection' => '/demandesDisponibles'
            ]);
        } else {
            $requeteSQL = $connexion->prepare(
                'SELECT COUNT(*) as existe 
                FROM TDemande 
                WHERE numeroDemande = :numeroDemande'
            );
            $requeteSQL->execute(['numeroDemande' => $numeroDemande]);
            $resultat = $requeteSQL->fetch();
            
            if ($resultat['existe'] == 0) {
                $reponse = view('erreur', [
                    'message' => 'Le numéro de la demande est invalide',
                    'redirection' => '/demandesDisponibles'
                ]);
            } 
            else {
                $requeteSQL = $connexion->prepare(
                    'UPDATE TDemande 
                    SET matriculeTuteur = :matriculeTuteur 
                    WHERE numeroDemande = :numeroDemande'
                );
                $requeteSQL->execute([
                    'matriculeTuteur' => $_SESSION['eleve']['matricule'],
                    'numeroDemande' => $numeroDemande
                ]);
                
                $reponse = redirect('/demandesTuteur');
            }
        }
        $connexion = null;
    }
    return $reponse;
});
