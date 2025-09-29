<?php
session_start();
if(!isset($_SESSION['admin'])){
    header('location:../connecter/loginadmin.php');
    exit;
}
require '../conf/connecter.php';

$tab = isset($_GET['tab']) ? $_GET['tab'] : 'bureaux';

// POST handling (inchangé)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Gestion Bureaux
    if(isset($_POST['add_bureau'])) {
        $nom = $_POST['nom_bureau'];
        $pass = $_POST['mot_de_passe'];
        $stmt = $conn->prepare("INSERT INTO users_bureau (nom_bureau, mot_de_passe) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $pass);
        $stmt->execute();
        $stmt->close();
    }
    if(isset($_POST['edit_bureau'])) {
        $id = intval($_POST['bureau_id']);
        $nom = $_POST['nom_bureau'];
        $pass = $_POST['mot_de_passe'];
        $stmt = $conn->prepare("UPDATE users_bureau SET nom_bureau=?, mot_de_passe=? WHERE id=?");
        $stmt->bind_param("ssi", $nom, $pass, $id);
        $stmt->execute();
        $stmt->close();
    }
    if(isset($_POST['delete_bureau'])) {
        $id = intval($_POST['bureau_id']);
        $stmt = $conn->prepare("DELETE FROM users_bureau WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    // Destinataires
    if(isset($_POST['add_dest'])) {
        $nom = $_POST['nom_dest'];
        $stmt = $conn->prepare("INSERT INTO destinataires (nom) VALUES (?)");
        $stmt->bind_param("s", $nom);
        $stmt->execute();
        $stmt->close();
    }
    if(isset($_POST['delete_dest'])) {
        $id = intval($_POST['dest_id']);
        // Supprimer d'abord les documents associés à ce destinataire
        $stmt_docs = $conn->prepare("DELETE FROM documents WHERE destinataire_id=?");
        $stmt_docs->bind_param("i", $id);
        $stmt_docs->execute();
        $stmt_docs->close();
        
        // Ensuite, supprimer le destinataire
        $stmt = $conn->prepare("DELETE FROM destinataires WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
    // Admins
    if(isset($_POST['add_admin'])) {
        $nom = $_POST['username'];
        $pass = $_POST['password'];
        $stmt = $conn->prepare("INSERT INTO administration (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $pass);
        $stmt->execute();
        $stmt->close();
    }
    if(isset($_POST['edit_admin'])) {
        $id = intval($_POST['admin_id']);
        $nom = $_POST['username'];
        $pass = $_POST['password'];
        $stmt = $conn->prepare("UPDATE administration SET username=?, password=? WHERE id=?");
        $stmt->bind_param("ssi", $nom, $pass, $id);
        $stmt->execute();
        $stmt->close();
    }
    if(isset($_POST['delete_admin'])) {
        $id = intval($_POST['admin_id']);
        $stmt = $conn->prepare("DELETE FROM administration WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
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
}

.rounded {
    border-radius: 8px;
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
    position: sticky;
    top: 0;
    z-index: 1000;
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
}

header a {
    color: #ffffff;
    text-decoration: none;
    font-weight: 600;
    padding: 0.5rem 1rem;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

header a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* --- Main Layout --- */
.container {
    display: flex;
    min-height: calc(100vh - 6rem); /* Adjust for header height */
}

.sidebar {
    width: 240px;
    background-color: #2f4f2f;
    padding: 2rem 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1.25rem;
    flex-shrink: 0;
}

.sidebar a {
    color: #e0e0e0;
    text-decoration: none;
    padding: 0.75rem 1.25rem;
    border-radius: 8px;
    transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s;
    font-weight: 500;
}

.sidebar a.active, .sidebar a:hover {
    background-color: #3c673c;
    color: #ffffff;
    transform: translateX(5px);
}

main {
    flex: 1;
    padding: 2.5rem;
}

.card {
    background-color: #ffffff;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    margin-bottom: 2.5rem;
}

.card h3 {
    color: #2f4f2f;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    font-weight: 700;
}

/* --- Forms & Inputs --- */
form {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 2rem;
    align-items: center;
}

form input, form select {
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.95rem;
    flex: 1;
    transition: all 0.3s ease;
}

form input:focus, form select:focus {
    border-color: #3c673c;
    box-shadow: 0 0 0 3px rgba(47, 79, 47, 0.2);
    outline: none;
}

/* --- Buttons --- */
button {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 100px;
}

button.add {
    background-color: #2f4f2f;
    color: #ffffff;
}
button.add:hover {
    background-color: #3c673c;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

button.edit {
    background-color: #008080; /* Teal for a nice contrast */
    color: #ffffff;
}
button.edit:hover {
    background-color: #006666;
    transform: translateY(-1px);
}

button.delete, button.del {
    background-color: #b30000;
    color: #ffffff;
}
button.delete:hover, button.del:hover {
    background-color: #800000;
    transform: translateY(-1px);
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
}

tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

table input[type=text] {
    border: 1px solid #d1d5db;
    border-radius: 4px;
    padding: 0.5rem 0.75rem;
    width: 100%;
    transition: border-color 0.3s ease;
}

table input[type=text]:focus {
    border-color: #3c673c;
    outline: none;
}

.status-badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    white-space: nowrap;
}

.status-badge.validated {
    background-color: #d4edda;
    color: #155724;
}

.status-badge.not-read {
    background-color: #f8d7da;
    color: #721c24;
}

/* --- Responsive Design --- */
@media (max-width: 768px) {
    .container {
        flex-direction: column;
    }
    .sidebar {
        width: 100%;
        flex-direction: row;
        overflow-x: auto;
        padding: 1rem;
        gap: 0.5rem;
    }
    .sidebar a {
        flex: 1;
        text-align: center;
        padding: 0.75rem;
        white-space: nowrap;
    }
    main {
        padding: 1.5rem;
    }
    .card {
        padding: 1.5rem;
    }
    form {
        flex-direction: column;
    }
    form input, form button {
        width: 100%;
    }
    thead {
        display: none;
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
<header>
    <div class="header-brand">
        <div class="logo-container">
            <img src="../img/logo.png" alt="Logo">
        </div>
        <h2>Admin Dashboard</h2>
    </div>
    <a href="../logout.php">Déconnexion</a>
</header>

<div class="container">
    <div class="sidebar">
        <a href="?tab=bureaux" class="<?= $tab=='bureaux'?'active':'' ?>">Bureaux</a>
        <a href="?tab=documents" class="<?= $tab=='documents'?'active':'' ?>">Documents</a>
        <a href="?tab=destinataires" class="<?= $tab=='destinataires'?'active':'' ?>">Destinataires</a>
        <a href="?tab=stats" class="<?= $tab=='stats'?'active':'' ?>">Statistiques</a>
        <a href="?tab=admins" class="<?= $tab=='admins'?'active':'' ?>">Admins</a>
    </div>

    <main>
        <?php if($tab=='bureaux'): ?>
        <div class="card">
            <h3>Gestion des Bureaux</h3>
            <form method="get">
                <input type="hidden" name="tab" value="bureaux">
                <input type="text" name="search_bureau" placeholder="Rechercher un bureau..." value="<?= isset($_GET['search_bureau'])?htmlspecialchars($_GET['search_bureau']):'' ?>">
                <button type="submit" class="add">Rechercher</button>
            </form>

            <form method="post">
                <input type="text" name="nom_bureau" placeholder="Nom Bureau" required>
                <input type="text" name="mot_de_passe" placeholder="Mot de passe" required>
                <button type="submit" name="add_bureau" class="add">Ajouter</button>
            </form>

            <table>
                <thead>
                    <tr><th>ID</th><th>Nom</th><th>Mot de passe</th><th>Action</th></tr>
                </thead>
                <tbody>
                <?php
                $searchB = isset($_GET['search_bureau']) ? $_GET['search_bureau'] : '';
                $sql = "SELECT * FROM users_bureau";
                if(!empty($searchB)) $sql .= " WHERE nom_bureau LIKE '%".$conn->real_escape_string($searchB)."%'";
                $res = $conn->query($sql);
                while($row=$res->fetch_assoc()):
                ?>
                <tr>
                    <form method="post">
                        <td data-label="ID"><?= $row['id'] ?><input type="hidden" name="bureau_id" value="<?= $row['id'] ?>"></td>
                        <td data-label="Nom"><input type="text" name="nom_bureau" value="<?= htmlspecialchars($row['nom_bureau']) ?>"></td>
                        <td data-label="Mot de passe"><input type="text" name="mot_de_passe" value="<?= htmlspecialchars($row['mot_de_passe']) ?>"></td>
                        <td data-label="Action">
                            <button type="submit" name="edit_bureau" class="edit">Modifier</button>
                            <button type="submit" name="delete_bureau" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bureau ?');">Supprimer</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php elseif($tab=='admins'): ?>
        <div class="card">
            <h3>Gestion des Admins</h3>
            <form method="get">
                <input type="hidden" name="tab" value="admins">
                <input type="text" name="search_admin" placeholder="Rechercher un admin..." value="<?= isset($_GET['search_admin'])?htmlspecialchars($_GET['search_admin']):'' ?>">
                <button type="submit" class="add">Rechercher</button>
            </form>

            <form method="post">
                <input type="text" name="username" placeholder="Nom admin" required>
                <input type="text" name="password" placeholder="Mot de passe" required>
                <button type="submit" name="add_admin" class="add">Ajouter</button>
            </form>

            <table>
                <thead><tr><th>ID</th><th>Nom</th><th>Mot de passe</th><th>Action</th></tr></thead>
                <tbody>
                <?php
                $searchA = isset($_GET['search_admin']) ? $_GET['search_admin'] : '';
                $sql = "SELECT * FROM administration";
                if(!empty($searchA)) $sql .= " WHERE username LIKE '%".$conn->real_escape_string($searchA)."%'";
                $res=$conn->query($sql);
                while($row=$res->fetch_assoc()):
                ?>
                <tr>
                    <form method="post">
                        <td data-label="ID"><?= $row['id'] ?><input type="hidden" name="admin_id" value="<?= $row['id'] ?>"></td>
                        <td data-label="Nom"><input type="text" name="username" value="<?= htmlspecialchars($row['username']) ?>"></td>
                        <td data-label="Mot de passe"><input type="text" name="password" value="<?= htmlspecialchars($row['password']) ?>"></td>
                        <td data-label="Action">
                            <button type="submit" name="edit_admin" class="edit">Modifier</button>
                            <button type="submit" name="delete_admin" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet admin ?');">Supprimer</button>
                        </td>
                    </form>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php elseif($tab=='documents'): ?>
        <div class="card">
            <h3>Documents</h3>
            <table>
                <thead>
                    <tr><th>ID</th><th>Numéro</th><th>Date</th><th>Objet</th><th>Envoyeur</th><th>Destinataire</th><th>Statut</th></tr>
                </thead>
                <tbody>
                <?php
                $sql = "SELECT d.*, dest.nom AS dest_nom FROM documents d JOIN destinataires dest ON d.destinataire_id = dest.id ORDER BY d.date_saisir DESC";
                $res = $conn->query($sql);
                while($doc=$res->fetch_assoc()):
                ?>
                <tr>
                    <td data-label="ID"><?= $doc['id'] ?></td>
                    <td data-label="Numéro"><?= htmlspecialchars($doc['num_sequence']) ?></td>
                    <td data-label="Date"><?= htmlspecialchars($doc['date_saisir']) ?></td>
                    <td data-label="Objet"><?= htmlspecialchars($doc['objet_saisir']) ?></td>
                    <td data-label="Envoyeur"><?= htmlspecialchars($doc['envoyeur']) ?></td>
                    <td data-label="Destinataire"><?= htmlspecialchars($doc['dest_nom']) ?></td>
                    <td data-label="Statut">
                        <span class="status-badge <?= $doc['lu'] ? 'validated' : 'not-read' ?>">
                            <?= $doc['lu'] ? 'Validé' : 'Non lu' ?>
                        </span>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php elseif($tab=='destinataires'): ?>
        <div class="card">
            <h3>Gestion des Destinataires</h3>
            <form method="post">
                <input type="text" name="nom_dest" placeholder="Nom destinataire" required>
                <button type="submit" name="add_dest" class="add">Ajouter</button>
            </form>
            <table>
                <thead><tr><th>ID</th><th>Nom</th><th>Action</th></tr></thead>
                <tbody>
                <?php
                $res=$conn->query("SELECT * FROM destinataires");
                while($row=$res->fetch_assoc()):
                ?>
                <tr>
                    <td data-label="ID"><?= $row['id'] ?></td>
                    <td data-label="Nom"><?= htmlspecialchars($row['nom']) ?></td>
                    <td data-label="Action">
                        <form method="post" style="display:inline">
                            <input type="hidden" name="dest_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="delete_dest" class="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce destinataire ?');">Supprimer</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <?php elseif($tab=='stats'): ?>
        <div class="card">
            <h3>Statistiques</h3>
            <div class="stats-container">
                <h4>Documents envoyés par bureau</h4>
                <ul>
                <?php
                $res = $conn->query("SELECT envoyeur, COUNT(*) as total FROM documents GROUP BY envoyeur");
                while($r=$res->fetch_assoc()) echo "<li>".htmlspecialchars($r['envoyeur'])." : {$r['total']}</li>";
                ?>
                </ul>

                <h4 style="margin-top: 1.5rem;">Documents Validés / Non validés</h4>
                <ul>
                <?php
                $res = $conn->query("SELECT lu, COUNT(*) as total FROM documents GROUP BY lu");
                while($r=$res->fetch_assoc()) echo "<li>".($r['lu']?'Validés':'Non validés')." : {$r['total']}</li>";
                ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>
