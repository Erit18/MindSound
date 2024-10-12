<?php
session_start();
session_destroy();
header("Location: ../../intranet.php");
exit();
?>