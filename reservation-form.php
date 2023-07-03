<?php
// ...

// Vérifier si l'utilisateur est connecté
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit;
}

// Vérifier si l'ID de la salle est spécifié dans le paramètre GET
if (!isset($_GET['id'])) {
    // Rediriger vers la page de planning si l'ID de la salle n'est pas spécifié
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

$salleId = $_GET['id'];

// Récupérer les détails de la salle à partir de la base de données
$sql = "SELECT * FROM reservations WHERE id = $salleId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $salle = $result->fetch_assoc();

    // Vérifier si la salle est disponible pour la réservation
    $currentDate = date('Y-m-d');
    $currentTime = date('H:i:s');
    if ($salle['date'] < $currentDate || ($salle['date'] == $currentDate && $salle['debut'] <= $currentTime)) {
        // Rediriger vers la page de planning si la salle n'est pas disponible
        header("Location: planning.php");
        exit;
    }

    // Afficher le formulaire de réservation
    $salleId = $salle['id'];
    $salleNom = $salle['nom'];
    $salleDate = $salle['date'];
    $salleDebut = $salle['debut'];
    $salleFin = $salle['fin'];
} else {
    // Rediriger vers la page de planning si la salle n'existe pas
    header("Location: planning.php");
    exit;
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Réserver la salle <?php echo $salleNom; ?></title>
</head>
<body>
    <h1>Réserver la salle <?php echo $salleNom; ?></h1>
    <form action="reservation-process.php" method="POST">
        <input type="hidden" name="salleId" value="<?php echo $salleId; ?>">
        <p>
            <label for="titre">Titre :</label>
            <input type="text" name="titre" id="titre" required>
        </p>
        <p>
            <label for="description">Description :</label>
            <textarea name="description" id="description" required></textarea>
        </p>
        <p>
            <label for="debut">Heure de début :</label>
            <input type="time" name="debut" id="debut" min="<?php echo $salleDebut; ?>" max="<?php echo $salleFin; ?>" required>
        </p>
        <p>
            <label for="fin">Heure de fin :</label>
            <input type="time" name="fin" id="fin" min="<?php echo $salleDebut; ?>" max="<?php echo $salleFin; ?>" required>
        </p>
        <p>
            <input type="submit" value="Réserver">
        </p>
    </form>
</body>
</html>
