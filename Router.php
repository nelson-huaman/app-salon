<?php

namespace MVC;

class Router {

   public array $getRoutes = [];
   public array $postRoutes = [];

   public function get($url, $funcion) {
      $this->getRoutes[$url] = $funcion;
   }

   public function post($url, $funcion) {
      $this->postRoutes[$url] = $funcion;
   }

   public function comprobarRutas() {

      session_start();

      $currentUrl = strtok($_SERVER['REQUEST_URI'], '?') ?? '/';
      // $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
      $method = $_SERVER['REQUEST_METHOD'];

      if($method === 'GET') {
         $funcion = $this->getRoutes[$currentUrl] ?? null;
      } else {
         $funcion = $this->postRoutes[$currentUrl] ?? null;
      }

      if($funcion) {
         call_user_func($funcion, $this);
      } else {
         echo 'Página no encontrado o Ruta no válida';
      }

   }

   public function render($view, $datos = []) {

      foreach ($datos as $key => $value) {
         $$key = $value;
      }

      ob_start(); // Almacena en la Memoria
      include_once __DIR__ . "/views/$view.php";
      $contenido = ob_get_clean();
      include_once __DIR__ . "/views/layout.php";
   }
   










}