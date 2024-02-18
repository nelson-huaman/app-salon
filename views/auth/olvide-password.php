<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestablece tu password escribeindo tu Email a continuación</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="/olvide" method="post" class="formulario">
   <div class="campo">
      <label for="email">Email</label>
      <input
         type="email"
         name="email"
         id="email"
         placeholder="Tu Email">
   </div>
   <input type="submit" value="Inviar Instrucciones" class="boton">
</form>

<div class="acciones">
   <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
   <a href="/crear-cuenta">¿Aún no tienes cuenta? Crear una</a>
</div>