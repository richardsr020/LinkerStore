<?php
session_start();
session_destroy();
header("Location: ../../templates/logIn.html");
exit;
