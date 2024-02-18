<?php

namespace Model;

class Cita extends ActiveRecord {

   protected static $tabla = 'citas';
   protected static $columnasDB = ['id', 'hora', 'fecha', 'usuarioID'];

   public $id;
   public $hora;
   public $fecha;
   public $usuarioID;

   public function __construct($args = []) {

      $this->id = $args['id'] ?? null;
      $this->hora = $args['hora'] ?? '';
      $this->fecha = $args['fecha'] ?? '';
      $this->usuarioID = $args['usuarioID'] ?? '';

   }
   
}