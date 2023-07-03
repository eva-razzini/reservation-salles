<?php
session_start();

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

// Récupère la date de début de la semaine en cours
$today = date('Y-m-d');
$startDate = date('Y-m-d', strtotime('last Monday', strtotime($today)));

// Récupère la date de fin de la semaine en cours
$endDate = date('Y-m-d', strtotime('next Friday', strtotime($today)));

// Récupère les réservations pour la semaine en cours
$query = "SELECT * FROM reservations WHERE debut >= :startDate AND fin <= :endDate";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':startDate', $startDate);
$stmt->bindParam(':endDate', $endDate);
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction pour vérifier si une réservation existe pour une date et heure spécifiques
function reservationExists($reservations, $date, $hour) {
    foreach ($reservations as $reservation) {
        $reservationStart = strtotime($reservation['debut']);
        $reservationEnd = strtotime($reservation['fin']);
        $checkDate = strtotime($date . ' ' . $hour);

        if ($checkDate >= $reservationStart && $checkDate < $reservationEnd) {
            return true;
        }
    }
    return false;
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
    <title>Planning des réservations</title>
    <style>
        table {
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 10px;
        }
        .reserved {
            background-color: #ffc0cb;
        }
    </style>
</head>
<body>
    <h1>Planning des réservations</h1>
    <?php if (isset($_SESSION["login"])) { ?>
        <form action="reservation-form.php" method="GET" id="plan">
            <input type="submit" value="Réserver" >
        </form>
    <?php } ?>
    <table>
        <tr>
            <th>Heures</th>
            <th>Lundi</th>
            <th>Mardi</th>
            <th>Mercredi</th>
            <th>Jeudi</th>
            <th>Vendredi</th>
        </tr>
        <?php
        // Boucle pour afficher les heures et les jours de la semaine
        for ($hour = 8; $hour <= 18; $hour++) {
            echo '<tr>';
            echo '<td>' . $hour . 'h</td>';

            for ($day = 1; $day <= 5; $day++) {
                $currentDate = date('Y-m-d', strtotime($startDate . ' +' . ($day - 1) . ' days'));
                $reservationClass = reservationExists($reservations, $currentDate, $hour . ':00') ? 'reserved' : '';

                echo '<td class="' . $reservationClass . '"></td>';
            }

            echo '</tr>';
        }
        ?>
    </table>
</body>
</html>