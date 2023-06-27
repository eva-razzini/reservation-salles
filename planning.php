<!DOCTYPE html>
<html>
<head>
    <title>Planning de la salle</title>
    <style>
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Planning de la salle</h1>

    <?php
    // Obtenir la date du lundi de la semaine en cours
    $dateLundi = strtotime('last Monday', time());

    // Créer un tableau pour les jours de la semaine
    $joursSemaine = array();
    for ($i = 0; $i < 5; $i++) {
        $joursSemaine[] = date('Y-m-d', strtotime("+$i day", $dateLundi));
    }

    // Créer un tableau pour les horaires
    $heures = array();
    for ($heure = 8; $heure < 20; $heure++) {
        $heures[] = sprintf('%02d:00', $heure);
    }

    // Exemple de réservations
    $reservations = array(
        array('2023-06-24 10:00:00', 'John Doe', 'Réunion'),
        array('2023-06-25 15:00:00', 'Jane Smith', 'Présentation'),
        array('2023-06-27 14:00:00', 'Alice Johnson', 'Formation')
    );

    // Afficher le planning sous forme de tableau
    echo '<table>';
    echo '<tr><th></th>';
    foreach ($joursSemaine as $jour) {
        echo '<th>' . date('D d/m', strtotime($jour)) . '</th>';
    }
    echo '</tr>';

    foreach ($heures as $heure) {
        echo '<tr>';
        echo '<td>' . $heure . '</td>';
        foreach ($joursSemaine as $jour) {
            echo '<td>';
            foreach ($reservations as $reservation) {
                $heureDebut = strtotime($reservation[0]);
                $heureFin = strtotime('+1 hour', $heureDebut);
                if ($jour == date('Y-m-d', $heureDebut) && $heure == date('H:00', $heureDebut)) {
                    echo '<a href="reservation.php?id=' . urlencode($reservation[0]) . '">';
                    echo $reservation[1] . '<br>';
                    echo $reservation[2];
                    echo '</a>';
                } elseif ($jour == date('Y-m-d', $heureDebut) && $heure > date('H:00', $heureDebut) && $heure < date('H:00', $heureFin)) {
                    echo '<span style="color: red;">Réservé</span>';
                }
            }
            echo '</td>';
        }
        echo '</tr>';
    }

    echo '</table>';
    ?>

</body>
</html>
