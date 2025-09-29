<?php
session_start();
if (!isset($_SESSION['bureau'])) {
    header('Location: ../connecter/loginbureau.php');
    exit;
}
require '../conf/connecter.php';
$bureau = $_SESSION['bureau'];

// Récupérer tous les destinataires sauf le bureau connecté
$stmt = $conn->prepare("SELECT id, nom FROM destinataires WHERE nom != ?");
$stmt->bind_param("s", $bureau);
$stmt->execute();
$destinataires = $stmt->get_result();
$message = "";

// --- Traitement du formulaire ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si tous les champs requis sont remplis
    if (empty($_POST["num_sequence"]) || empty($_POST["date_saisir"]) || empty($_POST["objet_saisir"]) || empty($_POST["destinataire"]) || empty($_FILES["fichier_pdf"]["name"])) {
        $message = "<p class='error'>❌ Veuillez remplir tous les champs obligatoires.</p>";
    } else {
        $num_sequence = $_POST["num_sequence"];
        $date_saisir = $_POST["date_saisir"];
        $objet_saisir = $_POST["objet_saisir"];
        $destinataires_ids = $_POST["destinataire"]; 
        
        // Gérer le téléchargement du fichier une seule fois
        $targetDir = __DIR__ . "/uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        
        $nom_fichier = basename($_FILES["fichier_pdf"]["name"]);
        $targetFilePath = $targetDir . $nom_fichier;
        
        if (move_uploaded_file($_FILES["fichier_pdf"]["tmp_name"], $targetFilePath)) {
            $all_success = true;
            $failed_recipients = [];

            // Préparer la requête d'insertion une seule fois
            $stmt_insert = $conn->prepare("
                INSERT INTO documents 
                (num_sequence, date_saisir, objet_saisir, destinataire_id, nom_fichier, envoyeur) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            
            // Boucler sur chaque destinataire et insérer un document
            foreach ($destinataires_ids as $destinataire_id) {
                $stmt_insert->bind_param("sssiss", $num_sequence, $date_saisir, $objet_saisir, $destinataire_id, $nom_fichier, $bureau);
                if (!$stmt_insert->execute()) {
                    $all_success = false;
                    $failed_recipients[] = $destinataire_id;
                }
            }
            $stmt_insert->close();

            if ($all_success) {
                $message = "<p class='success'>✅ Document enregistré avec succès pour tous les destinataires.</p>";
            } else {
                $message = "<p class='error'>❌ Erreur lors de l'enregistrement pour certains destinataires.</p>";
            }
        } else {
            $message = "<p class='error'>⚠️ Échec du téléchargement du fichier.</p>";
        }
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Saisir un Document</title>
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
    max-width: 700px;
    margin: 90px auto 60px;
    background-color: #ffffff;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
}

h2 {
    color: #2f4f2f;
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    font-weight: 700;
    text-align: center;
}

/* --- Forms & Inputs --- */
form {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

label {
    font-weight: 600;
    color: #374151;
    display: block;
    margin-bottom: 0.5rem;
}

input, textarea, select {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
}

input:focus, textarea:focus, select:focus {
    border-color: #3c673c;
    box-shadow: 0 0 0 3px rgba(47, 79, 47, 0.2);
    outline: none;
}
select[multiple] {
    height: 150px; /* Ajuster la hauteur pour une meilleure visibilité des options */
}

/* --- Buttons --- */
.btns {
    margin-top: 1rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
}

button {
    padding: 0.75rem 1.5rem;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 100px;
}

button.submit {
    background-color: #2f4f2f;
    color: #ffffff;
}

button.submit:hover {
    background-color: #3c673c;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

button.cancel {
    background-color: #6b7280;
    color: #ffffff;
}

button.cancel:hover {
    background-color: #4b5563;
    transform: translateY(-2px);
    box-shadow: 4px 8px rgba(0, 0, 0, 0.1);
}

/* --- Messages --- */
.success {
    color: #2f4f2f;
    background-color: #e8f5e9;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

.error {
    color: #b30000;
    background-color: #ffe8e8;
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 1.5rem;
}

/* --- Responsive Design --- */
@media(max-width:600px) {
    header {
        flex-direction: column;
        gap: 1rem;
        padding: 1rem;
        text-align: center; /* Centre les éléments dans le header */
    }
    .header-brand {
        justify-content: center; /* Centre la marque sur mobile */
        width: 100%;
    }
    .header-brand h2 {
        font-size: 1.2rem;
    }
    nav {
        display: flex;
        flex-direction: column;
        width: 100%;
    }
    nav a {
        text-align: center;
    }
    .container {
        padding: 1.5rem;
        margin: 30px 1rem 60px;
    }
    .btns {
        flex-direction: column;
        gap: 0.75rem;
    }
    .btns button {
        width: 100%;
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
            <h2>Bureau: <?= htmlspecialchars($bureau); ?></h2>
        </div>
    </div>
    <nav>
        <a href="#">Saisir</a>
        <a href="envoyer.php">Envoyer</a>
        <a href="recus.php">Reçus</a>
        <a href="../logout.php">Déconnexion</a>
    </nav>
</header>

<div class="container">
    <?= $message; ?>
    <form action="" method="post" enctype="multipart/form-data">
        <h2>Saisir :</h2>
        <label for="num_sequence">Numéro de séquence</label>
        <input type="text" id="num_sequence" name="num_sequence" required>

        <label for="date_saisir">Date</label>
        <input type="date" id="date_saisir" name="date_saisir" value="<?= date('Y-m-d'); ?>" required>

        <label for="objet_saisir">Objet</label>
        <textarea id="objet_saisir" name="objet_saisir" rows="3" required></textarea>

        <label for="destinataire">Destinataire(s)</label>
        <select id="destinataire" name="destinataire[]" multiple required>
            <?php while ($row = $destinataires->fetch_assoc()): ?>
                <option value="<?= $row['id']; ?>"><?= htmlspecialchars($row['nom']); ?></option>
            <?php endwhile; ?>
        </select>

        <label for="fichier_pdf">Fichier PDF</label>
        <input type="file" id="fichier_pdf" name="fichier_pdf" accept="application/pdf" required>

        <div class="btns">
            <button type="submit" class="submit">Envoyer</button>
            <button type="reset" class="cancel">Annuler</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const header = document.querySelector('.fixed-header');
        let lastScrollY = window.scrollY;

        const isMobile = () => window.innerWidth <= 600;

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
