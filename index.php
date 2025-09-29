<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accueil - Système de Gestion</title>
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
    transition: all 0.4s ease;
}

.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 1rem;
}

/* --- Header & Navigation --- */
header {
    background-color: #ffffff;
    padding: 1rem 2rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 2px solid #2f4f2f;
}

.header-logo {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-logo svg {
    width: 36px;
    height: 36px;
    fill: #2f4f2f;
}

.header-logo h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2f4f2f;
    font-family: 'Montserrat', sans-serif;
    margin: 0;
}

.btn-login {
    background-color: #2f4f2f;
    color: #ffffff;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-login:hover {
    background-color: #3c673c;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* --- Hero Section --- */
.hero-section {
    text-align: center;
    padding: 4rem 1rem;
}

.hero-section h1 {
    font-weight: 700;
    color: #2f4f2f;
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

img{
  width: 200px;
  height: 100px;
}

.hero-section p {
    color: #6b7280;
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}

/* --- Features & Cards --- */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    padding: 1rem;
}

.card {
    background-color: #ffffff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    text-align: center;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.card .icon {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #2f4f2f;
}

.card h5 {
  
    font-weight: 700;
    color: #2f4f2f;
    margin-bottom: 0.5rem;
}

.card p {
    color: #6b7280;
}

.card ul {
    list-style: none;
    text-align: left;
    margin-top: 1rem;
    padding-left: 0;
}

.card ul li {
    background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="%232f4f2f" class="bi bi-check-circle" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm-2.5-4a.5.5 0 0 0-.5.5v2.5a.5.5 0 0 0 1 0V11a.5.5 0 0 0-.5-.5zM8 7a.5.5 0 0 0-.5.5v6a.5.5 0 0 0 1 0V7.5a.5.5 0 0 0-.5-.5zM11.5 9a.5.5 0 0 0-.5.5v4.5a.5.5 0 0 0 1 0V9.5a.5.5 0 0 0-.5-.5z"/></svg>') no-repeat left center;
    background-size: 16px;
    padding-left: 25px;
    margin-bottom: 0.5rem;
    color: #4b5563;
}

/* --- Footer --- */
footer {
    background-color: #1a1a1a;
    color: #f0f2f5;
    text-align: center;
    padding: 2.5rem 1rem;
    margin-top: 2rem;
    border-top: 5px solid #2f4f2f;
}

footer h5 {
  
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
}

footer p {
    color: #d1d5db;
    margin: 0.25rem 0;
}

.card h5{
  margin-bottom: 10px;
}

footer a {
    color: #90ee90;
    text-decoration: none;
    transition: color 0.3s ease;
}
.features-grid .card h4{
  margin-bottom: 20px;
  color: #2f4f2f;
  
}


footer a:hover {
    text-decoration: underline;
    color: #72b272;
}

/* --- Responsive --- */
@media (max-width: 768px) {
    .header-logo h1 {
        display: none;
    }
    .hero-section h1 {
        font-size: 2rem;
    }
}
</style>
</head>
<body>

<!-- Header -->
<header>
    <div class="header-logo">
        <img src="img/logo.png">
        
    </div>
    <a href="connecter.php" class="btn-login">Se connecter</a>
</header>

<!-- Hero Section -->
<section class="hero-section">
    <h1>Système de Gestion des Documents</h1>
    <p>Simplifiez la gestion de vos documents administratifs avec notre solution digitale moderne et sécurisée.</p>
</section>

<!-- Schéma d'utilisation -->
<div class="container features-grid">
    <div class="card">
        <div class="icon">📝</div>
        <h5>1. Saisir</h5>
        <p>Saisissez et envoyez vos documents en quelques clics.</p>
    </div>
    <div class="card">
        <div class="icon">📥</div>
        <h5>2. Recevoir</h5>
        <p>Consultez les documents reçus dans votre interface.</p>
    </div>
    <div class="card">
        <div class="icon">🛠️</div>
        <h5>3. Gérer</h5>
        <p>Administration complète pour les responsables.</p>
    </div>
</div>

<!-- Interfaces -->
<section class="container features-grid">
    <div class="card">
        <h4>Interface Bureaux</h4>
        <p>Accédez à votre espace personnel pour gérer vos documents, consulter les envois et réceptions.</p>
        <ul>
            <li>Saisie rapide de documents</li>
            <li>Suivi des envois et réceptions</li>
            <li>Interface intuitive</li>
        </ul>
    </div>
    <div class="card">
        <h4>Administration</h4>
        <p>Panel d'administration complet pour la gestion des utilisateurs et des documents.</p>
        <ul>
            <li>Gestion des utilisateurs</li>
            <li>Modification des documents</li>
            <li>Contrôle total du système</li>
        </ul>
    </div>
</section>

<!-- Footer -->
<footer>
    <h5>Support Technique</h5>
    <p>Notre équipe technique est disponible pour vous accompagner</p>
    <div>
        <p><strong>Email :</strong> <a href="mailto:admin@commune-taourirt.ma">admin@commune-taourirt.ma</a></p>
        <p><strong>Téléphone :</strong> <a href="tel:+212557864890">+212 557864890</a></p>
    </div>
</footer>

</body>
</html>
