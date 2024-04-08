<?php
    session_start();
    session_destroy();

    header("Location: /assignment/index.php");
    exit;