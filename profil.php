<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["login"])) {
    // Redirection vers la page de connexion
    header("Location: connexion.php");
    exit;
}

// Vérifier si un message de succès de modification de profil est présent dans la variable de session
$message = isset($_SESSION["profil_message"]) ? $_SESSION["profil_message"] : "";
unset($_SESSION["profil_message"]); // Supprimer le message de la variable de session

// Récupérer les informations de l'utilisateur connecté depuis la base de données
$host = "localhost";
$dbname = "livreor";
$username = "pma";
$passwordDB = "plomkiplomki";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwordDB);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations de l'utilisateur connecté
    $query = "SELECT login FROM utilisateurs WHERE login = ?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$_SESSION["login"]]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si le formulaire de mise à jour a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupérer les nouvelles données du formulaire
        $newLogin = $_POST["new_login"];
        $newPassword = $_POST["new_password"];

        // Mettre à jour les informations de l'utilisateur dans la base de données
        $updateQuery = "UPDATE utilisateurs SET login = ?, password = ? WHERE login = ?";
        $updateStmt = $conn->prepare($updateQuery);
        $updateStmt->execute([$newLogin, $newPassword, $_SESSION["login"]]);

        // Mettre à jour le login de l'utilisateur dans la variable de session
        $_SESSION["login"] = $newLogin;

        // Stocker le message de succès de modification de profil dans la variable de session
        $_SESSION["profil_message"] = "Profil modifié avec succès";

        // Redirection vers la page de profil mise à jour
        header("Location: profil.php");
        exit;
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}

// Traitement de la déconnexion
if (isset($_GET["logout"])) {
    // Supprimer toutes les variables de session
    session_unset();

    // Détruire la session
    session_destroy();

    // Redirection vers la page de connexion
    header("Location: connexion.php");
    exit;
}
?>

<!-- Formulaire de modification de profil -->
<!DOCTYPE html>
<html>
<head>
    <title>Profil</title>
</head>
<body>
    <h1>Modifier le profil</h1>
    <form action="profil_traitement.php" method="post">
        <label for="login">Login actuel :</label>
        <input type="text" id="login" name="login" value="JohnDoe" readonly><br>

        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" id="new_password" name="new_password" required><br>

        <label for="confirm_password">Confirmez le nouveau mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required><br>

        <input type="submit" value="Modifier">
    </form>
</body>
</html>

