<?php

    $bddPDO = new PDO('mysql:host=localhost;dbname=ideastage_BDD;charset=utf8', 'root', 'A2#DevWeb!');
    $bddPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connexion réussie !";

    