<?php

namespace Model;

class Servicio extends ActiveRecord {

   protected static $tabla = 'servicios';
   protected static $columnasDB = ['id', 'nombre', 'precio'];

   public $id;
   public $nombre;
   public $precio;

   public function __construct($args = []) {

      $this->id = $args['id'] ?? null;
      $this->nombre = $args['nombre'] ?? '';
      $this->precio = $args['precio'] ?? '';

   }

   public function validar() {

      if(!$this->nombre) {
         self::$alertas['error'][] = 'El Nombre de Servicio es Obligatorio';
      }

      if(!$this->precio) {
         self::$alertas['error'][] = 'El Precio de Servicio es Obligatorio';
      }

      if(!is_numeric($this->precio)) {
         self::$alertas['error'][] = 'El Precio no es Válido';
      }

      return self::$alertas;

   }


}