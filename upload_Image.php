<!-- Formate et sauvegarde l'image -->

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imageData = $_POST['imageData'];
    $companyName = trim($_POST['companyName']);

    // Vérifier si les données de l'image sont présentes
    if (!empty($imageData) && !empty($companyName)) {
        // Extraire le type de l'image (par exemple "image/png")
        $imageParts = explode(";base64,", $imageData);
        $imageTypeAux = explode("image/", $imageParts[0]);
        $imageType = $imageTypeAux[1];

        // Décoder les données base64
        $imageBase64 = base64_decode($imageParts[1]);

        // Créer un nom de fichier valide
        $fileName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $companyName) . '.' . $imageType;

        // Définir le chemin de sauvegarde
        $filePath = 'images/' . $fileName;

        // Sauvegarder le fichier sur le serveur
        file_put_contents($filePath, $imageBase64);

        echo "Image sauvegardée avec succès : <a href='$filePath'>$fileName</a>";
    } else {
        echo "Veuillez coller une image et fournir le nom de l'entreprise.";
    }
}
?>