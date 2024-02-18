<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia sesión con tus Datos</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form action="" method="post" class="formulario">
   <div class="campo">
      <label for="email">Email</label>
      <input
         type="email"
         name="email"
         id="email"
         placeholder="Tu Email">
   </div>
   <div class="campo">
      <label for="password">Password</label>
      <input
         type="password"
         name="password"
         id="password"
         placeholder="Tu Password">
   </div>

   <input type="submit" value="Iniciar Sesión" class="boton">
</form>

<div class="acciones">
   <a href="/crear-cuenta">¿Aún no tienes cuenta? Crear una</a>
   <a href="/olvide">¿Olvidates tu Password</a>
</div>