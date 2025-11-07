<?php
session_start();

// Détruire la session
session_unset();
session_destroy();

// Rediriger vers la page admin
header('Location: ../../admin.php');
exit;
?>
