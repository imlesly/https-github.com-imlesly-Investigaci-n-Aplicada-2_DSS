<?php
$db = new mysqli('localhost', 'root', '', 'dssdb2');
if ($db->connect_error) {
    die("Conexión fallida: " . $db->connect_error);
}
