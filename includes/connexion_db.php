<?php
try
{
    $db=new PDO('mysql:host=localhost;dbname=memoire2021_Bally','root','root');
}
catch (PDOException $e)
{
    die($e->getMessage());
}
?>