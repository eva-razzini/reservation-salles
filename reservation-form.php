<?php
// ...

// Vérifier si l'utilisateur est connecté
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: connexion.php");
    exit;
}

// Traitement du formulaire de réservation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    // Effectuer les validations nécessaires sur les données du formulaire

    // Insérer la réservation dans la base de données
    $userId = $_SESSION['id']; // ID de l'utilisateur connecté
    $sql = "INSERT INTO reservations (titre, description, debut, fin, id_utilisateur) VALUES ('$title', '$description', '$start', '$end', '$userId')";
    $result = $conn->query($sql);

    if ($result === TRUE) {
        // Rediriger vers la page de confirmation de réservation
        header("Location: reservation-success.php");
        exit;
    } else {
        echo "Une erreur s'est produite lors de la réservation.";
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Formulaire de réservation de salle</title>
    <style>
        /* Ajoutez votre CSS pour personnaliser le formulaire */
    </style>
</head>
<body>
    <h1>Formulaire de réservation de salle</h1>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <div>
            <label for="title">Titre :</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="description">Description :</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div>
            <label for="start">Date de début :</label>
            <input type="datetime-local" id="start" name="start" required>
        </div>
        <div>
            <label for="end">Date de fin :</label>
            <input type="datetime-local" id="end" name="end" required>
        </div>
        <div>
            <input type="submit" value="Réserver">
        </div>
    </form>
</body>
</html>