<section class="container-m px-4 py-4">
<h1>Gestión de Estudiantes</h1>

<form method="GET" class="row mb-3">
<input type="hidden" name="page" value="EstudiantesCC">
<input type="text" name="partialNombre" value="{{partialNombre}}" placeholder="Buscar nombre">
<button class="primary">Buscar</button>
<a class="button" href="index.php?page=EstudiantesCCForm&mode=INS">Nuevo</a>
</form>

<table class="full-width">
<thead>
<tr>
<th>Nombre</th>
<th>Apellido</th>
<th>Edad</th>
<th>Especialidad</th>
<th>Acciones</th>
</tr>
</thead>
<tbody>
{{foreach estudiante in estudiantes}}
<tr>
<td>{{nombre}}</td>
<td>{{apellido}}</td>
<td>{{edad}}</td>
<td>{{especialidad}}</td>
<td>
<a href="index.php?page=EstudiantesCCForm&mode=DSP&id_estudiante={{id_estudiante}}">Ver</a> |
<a href="index.php?page=EstudiantesCCForm&mode=UPD&id_estudiante={{id_estudiante}}">Editar</a> |
<a href="index.php?page=EstudiantesCCForm&mode=DEL&id_estudiante={{id_estudiante}}">Eliminar</a>
</td>
</tr>
{{endforeach estudiante}}
</tbody>
</table>
</section>
