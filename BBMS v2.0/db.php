<?php
// setting up connection with database
$dsn = "mysql:host=localhost;dbname=bbms";

try {
    // Creating PDO class instance
    $pdo = new PDO($dsn, 'root', '');
    /* PDO::ERRMODE_EXCEPTION = In addition to setting the error code, PDO will throw a PDOException. This setting is also useful during debugging, as it will effectively "blow up" the script at the point of the error */
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { // If it throws exception we catch it and give message
    echo $e->getMessage();
}
