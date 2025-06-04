<?php
session_start();
session_destroy();
header("Location: visualizer.php");
exit;
