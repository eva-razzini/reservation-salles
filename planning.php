<?php
// Connexion à la base de données
$servername = "localhost";
$username = "votre_nom_utilisateur";
$password = "votre_mot_de_passe";
$dbname = "reservationsalles";

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

// Affichage du tableau du planning
?>


<!DOCTYPE html>
<html>
<head>
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
</body>
</html>

<?php
$conn->close();
?>