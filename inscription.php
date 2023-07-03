<?php
// Vérification du formulaire d'inscription
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Récupération des données du formulaire
  $login = $_POST["login"];
  $password = $_POST["password"];
  $confirmPassword = $_POST["confirm_password"];
  
    // Vérifier si les mots de passe correspondent
    if ($password === $confirmPassword) {
        // Connexion à la base de données
        $host = "localhost";
        $dbname = "reservationsalles";
        $username = "pma";
        $passwordDB = "plomkiplomki";
        
        try {
            $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $passwordDB);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Insérer les données dans la table utilisateurs
            $query = "INSERT INTO utilisateurs (login, password) VALUES (?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$login, $password]);
            
            // Redirection vers la page de connexion
            header("Location: connexion.php");
            exit;
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        echo "Les mots de passe ne correspondent pas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Tangerine:wght@700&display=swap');
    @import url('https://fonts.googleapis.com/css2?family=Bruno+Ace+SC&display=swap');
  </style>
    <link rel="stylesheet" href="style6.css">
  <title>Inscription</title>
</head>
<body>
  <h1>Inscription</h1>
  <form method="POST" action="inscription.php">
    <label for="login">Login:</label>
    <input type="text" name="login" required><br>
    
    <label for="password">Mot de passe:</label>
    <input type="password" name="password" required><br>
    
    <label for="confirm_password">Confirmez le mot de passe:</label>
    <input type="password" name="confirm_password" required><br>
    
    <input type="submit" value="S'inscrire">
  </form>
</body>
</html>


