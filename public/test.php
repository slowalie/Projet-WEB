<?php
    namespace App\Models;
    require_once __DIR__ . '/../src/models/Database.php';
    
    
    $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
    $bddPDO = $database->getConnection();
    $bddPDO->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    echo "<script>alert('Connexion réussie à la base de données !');</script>";
        
        

        

    $nom = "test";
    $prenom = "test";
    $email = "test";
    $password = "test";
    
    $insert = $bddPDO->prepare('INSERT INTO Utilisateurs (nom_user, prenom_user, mail_user, mdp_user) VALUES (:nom_user, :prenom_user, :mail_user, :mdp_user)');
    $insert->bindvalue(':nom_user', $nom);
    $insert->bindvalue(':prenom_user', $prenom);
    $insert->bindvalue(':mail_user', $email);
    $insert->bindvalue(':mdp_user', $password);
    $result = $insert->execute();
    if ($result) {
        echo "<script>alert('Insertion réussie !');</script>";
    } else {
        echo "<script>alert('Erreur lors de l'insertion.');</script>";
    }
        