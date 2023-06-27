<!DOCTYPE html>
<html>
<head>
    <title>Réservation de salle</title>
</head>
<body>
    <h1>Réservation de salle</h1>
    <form action="reservation-traitement.php" method="post">
        <label for="titre">Titre :</label>
        <input type="text" id="titre" name="titre" required><br>

        <label for="description">Description :</label>
        <textarea id="description" name="description" required></textarea><br>

        <label for="date_debut">Date de début :</label>
        <input type="date" id="date_debut" name="date_debut" required><br>

        <label for="date_fin">Date de fin :</label>
        <input type="date" id="date_fin" name="date_fin" required><br>

        <input type="submit" value="Réserver">
    </form>
</body>
</html>
