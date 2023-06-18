<?php
include "functions.php";

echo(runSQL("

    SELECT email FROM users WHERE email = 'admin@gmail.com';

"));

?>