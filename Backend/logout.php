<?php
session_start();
session_destroy();
header("Location: ../UI/index.html");
exit();
?>
