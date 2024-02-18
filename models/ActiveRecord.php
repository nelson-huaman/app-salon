<?php

namespace Model;

class ActiveRecord {

   // Base de Datos
   protected static $db;
   protected static $tabla = '';
   protected static $columnasDB = [];

   // Alertas y mensajes
   protected static $alertas = [];

   // Conexion a la Base de Datos
   public static function setDB($database) {
      self::$db = $database;
   }

   public static function setAlerta($tipo, $mensaje) {
      static::$alertas[$tipo][] = $mensaje;
   }

   // Validacion
   public static function getAlertas() {
      return static::$alertas;
   }

   public function validar() {
      static::$alertas = [];
      return static::$alertas;
   }

   // Consultar SQL para crear objetos en la memoria
   public static function consultarSQL($query) {

      // Consuiltar a la BD
      $resultado = self::$db->query($query);

      // Iterar los resultados
      $array = [];
      while ($registro = $resultado->fetch_assoc()) {
         $array[] = static::crearObjeto($registro);
      }

      // Liberar Memoria
      $resultado->free();
      return $array;

   }

   // Crear Objeto
   protected static function crearObjeto($registro) {

      $objeto = new static;
      foreach($registro as $key => $value) {
         if(property_exists($objeto, $key)) {
            $objeto->$key = $value;
         }
      }
      return $objeto;

   }

   // Identificar y Unir los Atributos de la DB
   public function atributos() {

      $atributos = [];
      foreach(static::$columnasDB as $columna) {
         if($columna === 'id') continue;
         $atributos[$columna] = $this->$columna;
      }
      return $atributos;

   }

   // Sanatizar los datos antes de guardar a la base de datos
   public function sanitizarAtributos() {

      $atributos = $this->atributos();
      $sanitizado = [];
      foreach($atributos as $key => $value) {
         $sanitizado[$key] = self::$db->escape_string($value);
      }
      return $sanitizado;

   }


   // Sincronizar DB con Objetos en Memoria
   public function sincronizar($args = []) {

      foreach($args as $key => $value) {
         if(property_exists($this, $key) && !is_null($value)) {
            $this->$key = $value;
         }
      }

   }

   // Guardar registros
   public function guardar() {

      $resultado = '';
      if(!is_null($this->id)) {
         $resultado = $this->actualizar();
      } else {
         $resultado = $this->crear();
      }

      return $resultado;

   }

   // Crear registros
   public function crear() {

      // Sanitizar los Datos
      $atributos = $this->sanitizarAtributos();
      
      // Insertar Datos
      $query = "INSERT INTO " . static::$tabla . " (";
      $query .= join(', ', array_keys($atributos));
      $query .= ") VALUES ('";
      $query .= join("', '", array_values($atributos));
      $query .= "')";

      // Insertar Datos
      $resultado = self::$db->query($query);

      return [
         'resultado' => $resultado,
         'id' => self::$db->insert_id
      ];

   }

   // Actualizar Registro
   public function actualizar() {

      $atributos = $this->sanitizarAtributos();

      $valores = [];
      foreach($atributos as $key => $value) {
         $valores[] = "{$key}='{$value}'";
      }

      $query = "UPDATE " . static::$tabla . " SET ";
      $query .= join(', ', $valores);
      $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
      $query .= " LIMIT 1";

      $resultado = self::$db->query($query);
      return $resultado;

   }

   // Eliminar Registro
   public function eliminar() {

      $query = "DELETE FROM " . static::$tabla . " WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
      $resultado = self::$db->query($query);
      return $resultado;

   }

   // Todo los Registros
   public static function all() {

      $query = "SELECT * FROM " . static::$tabla;
      $resultado = self::consultarSQL($query);
      return $resultado;

   }

   // registro por ID
   public static function find($id) {

      $query = "SELECT * FROM " . static::$tabla . " WHERE id = ${id}";
      $resultado = self::consultarSQL($query);
      return array_shift($resultado);

   }

   // registro con una Cantidad
   public static function get($limite) {

      $query = "SELECT * FROM " . static::$tabla . " LIMIT ${limite}";
      $resultado = self::consultarSQL($query);
      return array_shift($resultado);

   }

   // registro por ID
   public static function where($columna, $valor) {

      $query = "SELECT * FROM " . static::$tabla . " WHERE ${columna} = '${valor}'";
      $resultado = self::consultarSQL($query);
      return array_shift($resultado);

   }

   // Consulta Plana de SQL (Utilizar cuando los métodos del modelo no son suficientes)
   public static function SQL($query) {

      $resultado = self::consultarSQL($query);
      return $resultado;

   }

}