<?php
/**
 * Script pour créer 3 comptes de test: Admin, Pilote et Étudiant
 * À exécuter une seule fois
 */

declare(strict_types=1);

require_once __DIR__ . '/src/models/Database.php';
require_once __DIR__ . '/src/models/UserModel.php';

use App\Models\Database;
use App\Models\UserModel;

try {
    // Connexion à la base de données
    $database = new Database('localhost', 'root', 'A2#DevWeb!', 'ideastage_BDD');
    $pdo = $database->getConnection();
    $userModel = new UserModel($database);

    // Définir les comptes de test
    $testAccounts = [
        [
            'email' => 'admin@test.fr',
            'password' => 'Admin123!',
            'nom' => 'Admin',
            'prenom' => 'Test',
            'role' => 'admin'
        ],
        [
            'email' => 'pilot@test.fr',
            'password' => 'Pilot123!',
            'nom' => 'Pilot',
            'prenom' => 'Test',
            'role' => 'pilote'
        ],
        [
            'email' => 'student@test.fr',
            'password' => 'Student123!',
            'nom' => 'Student',
            'prenom' => 'Test',
            'role' => 'etudiant'
        ]
    ];

    $createdAccounts = [];

    foreach ($testAccounts as $account) {
        // Vérifier si l'email existe déjà
        if ($userModel->existsByEmail($account['email'])) {
            echo "❌ L'email {$account['email']} existe déjà.\n";
            continue;
        }

        // Hash le mot de passe
        $passwordHash = password_hash($account['password'], PASSWORD_DEFAULT);

        // Créer l'utilisateur
        if ($userModel->createUser($account['nom'], $account['prenom'], $account['email'], $passwordHash, $account['role'])) {
            // Récupérer l'ID de l'utilisateur créé
            $user = $userModel->findByEmail($account['email']);
            $userId = (int)$user['id_user'];

            // Si c'est un admin, l'ajouter à la table admin
            if ($account['role'] === 'admin') {
                try {
                    $stmt = $pdo->prepare('INSERT INTO admin (id_user) VALUES (:id_user)');
                    $stmt->execute(['id_user' => $userId]);
                    echo "✅ Compte admin créé avec succès!\n";
                } catch (\PDOException $e) {
                    echo "⚠️ Compte admin créé mais erreur lors de l'ajout à la table admin: {$e->getMessage()}\n";
                }
            } elseif ($account['role'] === 'pilote') {
                echo "✅ Compte pilote créé avec succès!\n";
            } else {
                echo "✅ Compte étudiant créé avec succès!\n";
            }

            $createdAccounts[] = [
                'email' => $account['email'],
                'password' => $account['password'],
                'role' => $account['role']
            ];
        } else {
            echo "❌ Erreur lors de la création du compte {$account['email']}.\n";
        }
    }

    // Afficher un résumé
    if (!empty($createdAccounts)) {
        echo "\n========================================\n";
        echo "📋 RÉSUMÉ DES COMPTES CRÉÉS:\n";
        echo "========================================\n";
        foreach ($createdAccounts as $account) {
            echo "\n📊 {$account['role']}:\n";
            echo "   Email: {$account['email']}\n";
            echo "   Mot de passe: {$account['password']}\n";
        }
        echo "\n========================================\n";
    }

} catch (\Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
