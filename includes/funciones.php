<?php

date_default_timezone_set('America/Bogota');

function debuguar($variable) : string {

   echo '<pre>';
   var_dump($variable);
   echo '</pre>';
   exit;

}

function stringHTML($html) : string {

   $sanado = htmlspecialchars($html);
   return $sanado;

}


// funcion que revisa  que el usuario este autenticado
function isAuth() : void {

   if(!isset($_SESSION['login'])) {
      header('Location: /');
   }

}

function isAdmin() : void {

   if(!isset($_SESSION['admin'])) {
      header('Location: /');
   }

}

function esUltimo(string $actual, string $proximo) : bool {

   if($actual !== $proximo) {
      return true;
   }
   return false;

}