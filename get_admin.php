<?php
$db = new mysqli('localhost', 'root', '', 'ecommerce-project');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$result = $db->query("SELECT * FROM users WHERE is_admin = 1 OR is_admin = true LIMIT 1");

if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
    echo "Admin trouvé :\n";
    echo "ID: " . $admin['id'] . "\n";
    echo "Nom: " . $admin['name'] . "\n";
    echo "Email: " . $admin['email'] . "\n";
    echo "Mot de passe: (haché) " . substr($admin['password'], 0, 20) . "...\n";
} else {
    echo "Aucun administrateur trouvé. Création d'un nouvel administrateur...\n";
    $name = 'Admin';
    $email = 'admin@example.com';
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    $stmt = $db->prepare("INSERT INTO users (name, email, password, is_admin, email_verified_at) VALUES (?, ?, ?, 1, NOW())");
    $stmt->bind_param("sss", $name, $email, $password);
    
    if ($stmt->execute()) {
        echo "Nouvel administrateur créé :\n";
        echo "Email: admin@example.com\n";
        echo "Mot de passe: admin123\n";
    } else {
        echo "Erreur lors de la création de l'administrateur : " . $db->error . "\n";
    }
}

$db->close();
?>
