<?php
session_start();
if (!isset($_SESSION['bureau'])) {
    header('Location: ../connecter/loginbureau.php');
    exit;
}

require '../conf/connecter.php'; 
$bureau = $_SESSION['bureau'];

// Récupérer l'ID du destinataire correspondant au bureau connecté
$stmt_id = $conn->prepare("SELECT id FROM destinataires WHERE nom = ?");
$stmt_id->bind_param("s", $bureau);
$stmt_id->execute();
$res_id = $stmt_id->get_result();
if ($res_id->num_rows == 0) { 
    die("Bureau non trouvé dans la table destinataires");
}
$destinataire_id = $res_id->fetch_assoc()['id'];
$stmt_id->close();

// --- Valider un document si bouton cliqué ---
if (isset($_POST['valider_id'])) {
    $id_doc = intval($_POST['valider_id']);
    $stmt_val = $conn->prepare("UPDATE documents SET lu=1 WHERE id=? AND destinataire_id=?");
    $stmt_val->bind_param("ii", $id_doc, $destinataire_id);
    $stmt_val->execute();
    $stmt_val->close();
}

// Récupérer tous les documents reçus par ce bureau
$sql = "SELECT d.id, d.num_sequence, d.date_saisir, d.objet_saisir, d.envoyeur, d.nom_fichier, d.lu
        FROM documents d
        WHERE d.destinataire_id = ?
        ORDER BY d.date_saisir DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $destinataire_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Documents reçus</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<style>
/* --- Global Styles --- */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Inter', sans-serif;
}

body {
    background-color: #f0f2f5;
    color: #1a1a1a;
    padding-top: 64px; /* Espace pour le header fixe */
}

/* --- Header & Navigation --- */
header {
    background-color: #1a1a1a;
    color: #ffffff;
    padding: 1rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    position: fixed; /* Rendu fixe */
    width: 100%; /* S'étend sur toute la largeur */
    top: 0;
    left: 0;
    z-index: 1000;
    transition: transform 0.3s ease-in-out;
}

header.hide {
    transform: translateY(-100%);
}

.header-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-container {
    background-color: #ffffff;
    padding: 0.5rem;
    border-radius: 10%;
    width: 136px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.logo-container img {
    width: 136px;
    height: 55px;
}

.header-brand h2 {
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    color: #ffffff;
    font-size: 1.5rem;
    margin: 0;
}

nav a, header a {
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

nav a:hover, header a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* --- Main Layout --- */
.container {
    max-width: 1300px;
    margin: 90px auto 60px;
    background-color: #ffffff;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}

h4 {
    color: #2f4f2f;
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    text-align: center;
}

/* --- Tables --- */
table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
}

th, td {
    padding: 1rem;
    text-align: left;
    vertical-align: middle;
}

thead th {
    background-color: #e8f5e9;
    color: #2f4f2f;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-radius: 6px;
    white-space: nowrap;
}

tbody tr {
    background-color: #ffffff;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border-radius: 8px;
}

tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}
tbody tr:last-of-type {
    margin-bottom: 0;
}

/* --- Status & Download --- */
.status {
    font-weight: 600;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    text-align: center;
}
.status.validated {
    color: #2f4f2f;
    background-color: #e8f5e9;
}
.status.not-read {
    color: #b30000;
    background-color: #ffe8e8;
}

.download-link {
    color: #008080;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s;
}
.download-link:hover {
    color: #006666;
    text-decoration: underline;
}

.valider-btn {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    background-color: #2f4f2f;
    color: #ffffff;
}

.valider-btn:hover {
    background-color: #3c673c;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* --- Responsive Design --- */
@media(max-width:768px) {
    header {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
        text-align: center;
    }
    .header-brand {
        justify-content: center;
        width: 100%;
    }
    .header-brand h2 {
        font-size: 1.2rem;
    }
    nav {
        flex-wrap: wrap;
        justify-content: center;
        margin-bottom: 1.5rem;
    }
    nav a {
        text-align: center;
    }
    .container {
        padding: 1.5rem;
        margin: 30px 1rem 60px;
    }
    thead {
        display: none; /* Hide table headers on mobile for a simpler layout */
    }
    tr, td {
        display: block;
        width: 100%;
        text-align: right;
        padding: 0.75rem 1rem;
    }
    td::before {
        content: attr(data-label);
        float: left;
        font-weight: 600;
        color: #555;
    }
}
</style>
</head>
<body>

<header class="fixed-header">
    <div class="header-brand">
        <div class="logo-container">
            <img src="../img/logo.png" alt="Logo de l'entreprise">
        </div>
        <div style="flex-grow: 1; text-align: center;">
            <h2>Documents Reçus</h2>
        </div>
    </div>
    <nav>
        <a href="bureau.php">Saisir</a>
        <a href="envoyer.php">Envoyer</a>
        <a href="recus.php">Reçus</a>
        <a href="../logout.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <h4>Documents reçus</h4>
    <?php if($result->num_rows > 0): ?>
    <table>
    <thead>
    <tr>
    <th>#</th>
    <th>Numéro</th>
    <th>Date</th>
    <th>Objet</th>
    <th>Expéditeur</th>
    <th>Fichier</th>
    <th>Statut</th>
    </tr>
    </thead>
    <tbody>
    <?php while($doc = $result->fetch_assoc()): ?>
    <tr>
    <td data-label="#"><?= $doc['id']; ?></td>
    <td data-label="Numéro"><?= htmlspecialchars($doc['num_sequence']); ?></td>
    <td data-label="Date"><?= htmlspecialchars($doc['date_saisir']); ?></td>
    <td data-label="Objet"><?= htmlspecialchars($doc['objet_saisir']); ?></td>
    <td data-label="Expéditeur"><?= htmlspecialchars($doc['envoyeur']); ?></td>
    <td data-label="Fichier">
        <a class="download-link" href="download.php?file=<?= urlencode($doc['nom_fichier']); ?>">Télécharger</a>
    </td>
    <td data-label="Statut">
    <?php if(!$doc['lu']): ?>
        <form method="post">
            <input type="hidden" name="valider_id" value="<?= $doc['id']; ?>">
            <button type="submit" class="valider-btn">Valider</button>
        </form>
    <?php else: ?>
        <span class="status validated">Validé</span>
    <?php endif; ?>
    </td>
    </tr>
    <?php endwhile; ?>
    </tbody>
    </table>
    <?php else: ?>
    <p>Aucun document reçu.</p>
    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.querySelector('.fixed-header');
        let lastScrollY = window.scrollY;

        const isMobile = () => window.innerWidth <= 768;

        window.addEventListener('scroll', () => {
            if (isMobile()) {
                if (window.scrollY > lastScrollY && window.scrollY > 100) {
                    // Scrolling down
                    header.classList.add('hide');
                } else {
                    // Scrolling up
                    header.classList.remove('hide');
                }
                lastScrollY = window.scrollY;
            } else {
                // Ensure header is always visible on desktop
                header.classList.remove('hide');
            }
        });
    });
</script>

</body>
</html>
