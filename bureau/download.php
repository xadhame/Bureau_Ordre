<?php
if (!empty($_GET['file'])) {
    $file = basename($_GET['file']);
    $filepath = __DIR__ . '/uploads/' . $file;

    if (file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $file . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    } else {
        echo "Fichier non trouvé.";
    }
} else {
    echo "Aucun fichier spécifié.";
}
?>
