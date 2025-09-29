<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Choix de Connexion</title>
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

.rounded {
    border-radius: 8px;
}

/* --- Logo & Card --- */
.login-options {
    background-color: #ffffff;
    color: #1a1a1a;
    padding: 2.5rem;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.05);
    width: 100%;
    max-width: 420px;
    text-align: center;
}

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

/* --- Buttons --- */
.btn-choice {
    display: block;
    width: 100%;
    padding: 0.75rem 1.5rem;
    margin: 1rem 0;
    border-radius: 6px;
    background-color: #2f4f2f;
    color: #ffffff;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-choice:hover {
    background-color: #3c673c;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.footer-text {
    margin-top: 1.5rem;
    font-size: 0.9rem;
    color: #6b7280;
}

img{
  width: 200px;
  height: 100px;
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

/* --- Responsive Design --- */
@media(max-width:480px) {
    .login-options {
        padding: 2rem 1.5rem;
        margin: 20px;
    }
}
</style>
</head>
<body>
    

<div class="login-options">
    <div class="header-brand">
        <div class="logo-container">
            <img src="img/logo.png">
        </div>
        
    </div>

    <a href="connecter/loginbureau.php" class="btn-choice">Connexion Bureau</a>
    <a href="connecter/loginadmin.php" class="btn-choice">Connexion Admin</a>

    <div class="footer-text">
        <a href="index.php">← Retour à l’accueil</a>
    </div>
</div>

</body>
</html>
