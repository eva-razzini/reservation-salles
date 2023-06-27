<?php
session_start();

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['login'])) {
    // L'utilisateur n'est pas connecté, redirection vers la page de connexion
    header("Location: connexion.php");
    exit;
}

// Traitement du formulaire d'ajout de commentaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération du commentaire
    $commentaire = $_POST['commentaire'];

    // Connexion à la base de données
    $conn = new mysqli("localhost", "pma", "plomkiplomki", "livreor");
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // Requête d'insertion du commentaire dans la table "commentaires" avec une requête préparée
    $utilisateur = $_SESSION['login'];
    $sql = "INSERT INTO commentaires (commentaire, id_utilisateur, date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $commentaire, $utilisateur);

    if ($stmt->execute()) {
        // Commentaire ajouté avec succès, rediriger vers la page du livre d'or
        header("Location: livre-or.php");
        exit;
    } else {
        $message = "Erreur lors de l'ajout du commentaire: " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $stmt->close();
    $conn->close();
}
?>

<!-- Formulaire d'ajout de commentaire -->
<h2>Ajouter un commentaire</h2>
<form method="POST" action="commentaire.php">
    <label for="commentaire">Commentaire:</label><br>
    <textarea name="commentaire" rows="4" cols="50" required></textarea><br>

    <input type="submit" value="Ajouter">
</form>
