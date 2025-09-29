<?php
session_start();

if (isset($_SESSION['bureau'])) {
    header('location:../bureau/bureau.php');
    exit;
}

require "../conf/connecter.php";

$error = "";

if (isset($_POST["submit"])) {
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);

    $req = "SELECT * FROM users_bureau WHERE nom_bureau = '$username' AND mot_de_passe = '$password'";
    $query = mysqli_query($conn, $req);

    if (mysqli_num_rows($query) == 1) {
        $_SESSION['bureau'] = $username; // ✅ variable unique pour bureau
        header('location:../bureau/bureau.php');
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion Bureau</title>
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
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* --- Card --- */
.login-card {
    background-color: #ffffff;
    color: #1a1a1a;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 420px;
    text-align: center;
}

/* --- Logo & Title --- */
.header-brand {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.logo-container {
    background-color: #ffffff;
    padding: 0.5rem;
    border-radius: 50%;
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.logo-container svg {
    width: 32px;
    height: 32px;
    fill: #2f4f2f;
}

.header-brand h2 {
    font-weight: 700;
    font-family: 'Montserrat', sans-serif;
    color: #2f4f2f;
    font-size: 1.8rem;
    margin-top: 0.5rem;
}

/* --- Form --- */
.form-group {
    margin-bottom: 1.5rem;
    text-align: left;
}

.form-label {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 0.5rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #2f4f2f;
    box-shadow: 0 0 0 3px rgba(47, 79, 47, 0.2);
}

.btn-submit {
    display: block;
    width: 100%;
    padding: 0.75rem 1.5rem;
    margin-top: 2rem;
    border-radius: 6px;
    background-color: #2f4f2f;
    color: #ffffff;
    font-weight: 600;
    border: none;
    transition: all 0.3s ease;
    cursor: pointer;
}

.btn-submit:hover {
    background-color: #3c673c;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.footer-text {
    text-align: center;
    font-size: 0.9rem;
    margin-top: 2rem;
    color: #6b7280;
}

.footer-text a {
    text-decoration: none;
    color: #008080;
    font-weight: 600;
    transition: color 0.3s ease;
}

.footer-text a:hover {
    color: #006666;
    text-decoration: underline;
}
img{
  width: 200px;
  height: 100px;
}
/* --- Error Message --- */
.alert-error {
    margin-top: 1.5rem;
    padding: 0.8rem 1rem;
    background-color: #fff3f3;
    color: #cc0000;
    border: 1px solid #ffcccc;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
    animation: shake 0.3s ease-in-out;
}
h2{
  padding: 10px 10px 40px 10px;
}
@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-4px); }
    50% { transform: translateX(4px); }
    75% { transform: translateX(-4px); }
}

/* --- Responsive Design --- */
@media(max-width:480px) {
    .login-card {
        padding: 2rem 1.5rem;
        margin: 20px;
    }
}
</style>
</head>
<body>
<div class="login-card">
    <div class="header-brand">
        <div class="logo-container">
            <img src="../img/logo.png">

        </div>
        
    </div>
    <h2>Connexion Bureau</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label class="form-label" for="username">Nom d'utilisateur</label>
            <input type="text" id="username" name="username" class="form-control" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="password">Mot de passe</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn-submit">Se connecter</button>
        <?php if ($error): ?>
            <div class="alert-error" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
    </form>
    <div class="footer-text">
       <a href="../connecter.php">← Retour </a>
    </div>
</div>
</body>
</html>
