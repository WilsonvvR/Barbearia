<?php

    session_start();

    $user = "root";
    $pass = "w20i06l05";
    $db = "barbearia";
    $host = "localhost";

    try {

        $conn = new PDO("mysql:host={$host};dbname={$db}", $user, $pass);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    } catch (PDOException) {

        print "Erro: " . $e->getMessage() . "<br/>";
        die();
    }

?>