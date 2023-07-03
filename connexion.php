<?php
session_start();

// Vérification de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Connexion à la base de données
    $conn = new mysqli("localhost", "pma", "plomkiplomki", "reservationsalles");
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données: " . $conn->connect_error);
    }

    // Requête de recherche de l'utilisateur dans la table "utilisateurs"
    $sql = "SELECT * FROM utilisateurs WHERE login='$login' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // L'utilisateur existe dans la base de données, création de la session utilisateur
        $_SESSION['login'] = $login;

        // Récupérer les données de l'utilisateur depuis le résultat de la requête
        $row = $result->fetch_assoc();
        $_SESSION['id_utilisateur'] = $row['id'];
        $_SESSION['loggedin'] = true; // Ajouter cette ligne pour indiquer que l'utilisateur est connecté
        
        // Redirection vers la page d'accueil (ou autre page)
        header("Location: profil.php");
        exit;
    } else {
        $error = "Login ou mot de passe incorrect.";
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
}
?>

<!-- Formulaire de connexion -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Tangerine:wght@700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Bruno+Ace+SC&display=swap');
  </style>
  <h1>Connexion</h1>
    <link rel="stylesheet" href="style6.css">
<form method="POST" action="connexion.php">
    <label for="login">Login:</label>
    <input type="text" name="login" required><br>

    <label for="password">Mot de passe:</label>
    <input type="password" name="password" required><br>

    <input type="submit" value="Se connecter">

    <?php if (isset($error)) { ?>
        <div style="color: red;"><?php echo $error; ?></div>
    <?php } ?>
</form>