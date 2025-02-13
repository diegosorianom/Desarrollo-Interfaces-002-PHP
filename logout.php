<?php
session_start();

unset($_SESSION['login']);
unset($_SESSION['id_Usuario']);

header("Location: index.php");
session_destroy();
exit();
?>