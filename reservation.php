<?php
// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: connexion.php");
    exit();
}

// Vérifie si l'ID de réservation est spécifié dans l'URL
if (isset($_GET['id'])) {
    $reservationId = $_GET['id'];
    
    // Connexion à la base de données
    $servername = "localhost";
    $dbname = "reservationsalles";
    $username = "pma";
    $password = "plomkiplomki";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }
    
    // Récupère les détails de la réservation en fonction de l'ID
    $query = "SELECT * FROM reservations WHERE id = :reservationId";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':reservationId', $reservationId);
    $stmt->execute();
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Vérifie si la réservation existe
    if (!$reservation) {
        echo "La réservation n'existe pas.";
        exit();
    }
} else {
    echo "ID de réservation non spécifié.";
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
    <title>Détails de la réservation</title>
</head>
<body>
    <h1>Détails de la réservation</h1>
    
    <p><strong>Créateur :</strong> <?php echo $reservation['createur']; ?></p>
    <p><strong>Titre :</strong> <?php echo $reservation['titre']; ?></p>
    <p><strong>Description :</strong> <?php echo $reservation['description']; ?></p>
    <p><strong>Heure de début :</strong> <?php echo $reservation['debut']; ?></p>
    <p><strong>Heure de fin :</strong> <?php echo $reservation['fin']; ?></p>
</body>
</html>