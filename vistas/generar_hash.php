<?php
$password_admin = 'buap2025#';
$password_cliente = 'Buap1234';

echo "Hash para admin (buap2025#): " . password_hash($password_admin, PASSWORD_BCRYPT) . "<br>";
echo "Hash para cliente1 (Buap1234): " . password_hash($password_cliente, PASSWORD_BCRYPT) . "<br>";
?>