<?php
session_start();

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie si le formulaire de réservation a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $debut = $_POST["debut"];
    $fin = $_POST["fin"];
    $id_utilisateur = $_SESSION["id_utilisateur"];

    // Connexion à la base de données
    $host = 'localhost';
    $dbName = 'reservationsalles';
    $username = 'pma';
    $password = 'plomkiplomki';

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }

    // Requête d'insertion de la réservation
    $query = "INSERT INTO reservations (titre, description, debut, fin, id_utilisateur) VALUES (:titre, :description, :debut, :fin, :id_utilisateur)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':titre', $titre);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':debut', $debut);
    $stmt->bindParam(':fin', $fin);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->execute();

    // Redirection vers la page du planning
    header("Location: planning.php");
    exit();
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
    <title>Réservation de salle</title>
</head>
<body>
    <h1>Réservation de salle</h1>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" required>
        <br>
        <label for="description">Description :</label>
        <textarea name="description"></textarea>
        <br>
        <label for="debut">Date et heure de début :</label>
        <input type="datetime-local" name="debut" required>
        <br>
        <label for="fin">Date et heure de fin :</label>
        <input type="datetime-local" name="fin" required>
        <br>
        <input type="submit" value="Réserver">
    </form>
</body>
</html>
