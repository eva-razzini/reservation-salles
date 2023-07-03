<?php
// ...

// Vérifier si l'utilisateur est connecté
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit;
}

// Vérifier si l'ID de la réservation est spécifié dans le paramètre GET
if (!isset($_GET['id'])) {
    // Rediriger vers la page de planning si l'ID de la réservation n'est pas spécifié
    header("Location: planning.php");
    exit;
}

// Connexion à la base de données
$servername = "localhost";
$dbname = "reservationsalles";
$username = "pma";
$password = "plomkiplomki";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

$reservationId = $_GET['id'];

// Récupérer les détails de la réservation à partir de la base de données
$sql = "SELECT * FROM reservations WHERE id = $reservationId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $reservation = $result->fetch_assoc();

    // Vérifier si l'utilisateur connecté est le créateur de la réservation
    if ($reservation['id_utilisateur'] != $_SESSION['id_utilisateur']) {
        // Rediriger vers la page de planning si l'utilisateur n'est pas autorisé à accéder à cette réservation
        header("Location: planning.php");
        exit;
    }

    // Afficher les détails de la réservation
    $creator = $reservation['id_utilisateur']; // Changer cela avec le nom du créateur récupéré à partir de la base de données
    $title = $reservation['titre'];
    $description = $reservation['description'];
    $start = $reservation['debut'];
    $end = $reservation['fin'];
} else {
    // Rediriger vers la page de planning si la réservation n'existe pas
    header("Location: planning.php");
    exit;
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Détails de la réservation</title>
</head>
<body>
    <h1>Détails de la réservation</h1>
    <p><strong>Créateur :</strong> <?php echo $creator; ?></p>
    <p><strong>Titre :</strong> <?php echo $title; ?></p>
    <p><strong>Description :</strong> <?php echo $description; ?></p>
    <p><strong>Heure de début :</strong> <?php echo $start; ?></p>
    <p><strong>Heure de fin :</strong> <?php echo $end; ?></p>
</body>
</html>

