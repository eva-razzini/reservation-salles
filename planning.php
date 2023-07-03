<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$dbname = "reservationsalles";
$username = "pma";
$password = "plomkiplomki";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion à la base de données : " . $conn->connect_error);
}

// Récupération des réservations de la semaine en cours
$today = date("Y-m-d");
$endOfWeek = date("Y-m-d", strtotime("+7 days"));

$sql = "SELECT * FROM reservations WHERE debut >= '$today' AND debut < '$endOfWeek'";
$result = $conn->query($sql);

// Tableau des jours de la semaine
$daysOfWeek = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi');

// Tableau des horaires de la journée
$hoursOfDay = array('8h', '9h', '10h', '11h', '12h', '13h', '14h', '15h', '16h', '17h', '18h');

// Création du tableau du planning
$planning = array();
foreach ($daysOfWeek as $day) {
    foreach ($hoursOfDay as $hour) {
        $planning[$day][$hour] = '';
    }
}

// Remplissage du planning avec les réservations
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $startDateTime = new DateTime($row['debut']);
        $endDateTime = new DateTime($row['fin']);

        $dayOfWeek = $startDateTime->format('l');
        $startHour = $startDateTime->format('G') . 'h';
        $endHour = $endDateTime->format('G') . 'h';

        $reservationInfo = $row['titre'] . ' (' . $row['id_utilisateur'] . ')';

        for ($i = $startHour; $i < $endHour; $i++) {
            $planning[$dayOfWeek][$i] = $reservationInfo;
        }
    }
}

// Fermeture de la connexion à la base de données
$conn->close();
?>


<!DOCTYPE html>
<html>
<head>
<!-- <link rel="stylesheet" href="style6.css"> -->
    <title>Planning de la salle</title>
    <style>
        table {
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 5px;
        }
    </style>
</head>
<body>
    <h1>Planning de la salle</h1>

    <?php if (isset($_SESSION["login"])) { ?>
        <form action="reservation-form.php" method="GET">
            <input type="submit" value="Réserver">
        </form>
    <?php } ?>


    <table>
        <tr>
            <th>Heure</th>
            <?php foreach ($daysOfWeek as $day) { ?>
                <th><?php echo $day; ?></th>
            <?php } ?>
        </tr>
        <?php foreach ($hoursOfDay as $hour) { ?>
            <tr>
                <td><?php echo $hour; ?></td>
                <?php foreach ($daysOfWeek as $day) { ?>
                    <?php
                    $reservation = $planning[$day][$hour];
                    $reservationId = ''; // ID de la réservation
                    if (!empty($reservation)) {
                        $reservationParts = explode('(', $reservation);
                        $reservationInfo = trim($reservationParts[0]);
                        $reservationId = rtrim($reservationParts[1], ')');
                    }
                    ?>
                    <td>
                        <?php if (!empty($reservationId)) { ?>
                            <a href="reservation.php?id=<?php echo $reservationId; ?>">
                                <?php echo $reservationInfo; ?>
                            </a>
                        <?php } else { ?>
                            <?php echo $reservation; ?>
                        <?php } ?>
                    </td>
                <?php } ?>
            </tr>
        <?php } ?>
    </table>
    <?php
    // Afficher le message de réservation réussie s'il est présent
    if (isset($_SESSION['reservation_success'])) {
        echo "<p>" . $_SESSION['reservation_success'] . "</p>";
        unset($_SESSION['reservation_success']); // Supprimer le message de la variable de session
    }
    ?>

</body>
</html>