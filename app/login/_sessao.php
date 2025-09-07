<?php
       if(!isset($_SESSION)){
        session_start();
       }
       if((!empty($_SESSION["nome"])) && (!isset($_SESSION["nivel"]))){
        header('location:../../index.php');
        exit();
       }

