<?php

if (!isset($_SESSION['documento']) || !isset($_SESSION['rol'])) {
    header("location: ../../index.html");
    exit();
}
