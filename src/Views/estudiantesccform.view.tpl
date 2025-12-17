<section class="container-m px-4 py-4">
<h1>Formulario Estudiante</h1>

{{with estudiante}}
<form method="POST">
<input type="hidden" name="id_estudiante" value="{{id_estudiante}}">

<label>Nombre</label>
<input {{~readonly}} name="nombre" value="{{nombre}}">

<label>Apellido</label>
<input {{~readonly}} name="apellido" value="{{apellido}}">

<label>Edad</label>
<input {{~readonly}} type="number" name="edad" value="{{edad}}">

<label>Especialidad</label>
<input {{~readonly}} name="especialidad" value="{{especialidad}}">

{{if showCommitBtn}}
<button class="primary">Confirmar</button>
{{endif showCommitBtn}}

<a class="button" href="index.php?page=EstudiantesCC">Regresar</a>
</form>
{{endwith estudiante}}
</section>
