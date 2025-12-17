<?php

$host = "localhost";
$user = "root";
$password = "";
$dbname = ""; 
$tabla = "";       
$dir = "WW_" . $tabla;


if (!is_dir($dir)) mkdir($dir);


$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}


$result = $conn->query("DESC $tabla");
$campos = [];
$primaryKey = null;

while ($row = $result->fetch_assoc()) {
    $campos[] = $row['Field'];
    if ($row['Key'] === 'PRI') {
        $primaryKey = $row['Field'];
    }
}


$modelo = "<?php\n";
$modelo .= "/** Modelo de datos para la tabla $tabla */\n";
$modelo .= "class " . ucfirst($tabla) . "Model {\n";
foreach ($campos as $campo) {
    $modelo .= "    public \$$campo;\n";
}
$modelo .= "\n    public function __construct(" . implode(", ", array_map(fn($c) => "\$$c", $campos)) . ") {\n";
foreach ($campos as $campo) {
    $modelo .= "        \$this->$campo = \$$campo;\n";
}
$modelo .= "    }\n";
$modelo .= "}\n";
file_put_contents("$dir/modelo_datos.php", $modelo);


$ctrlLista = "<?php\n";
$ctrlLista .= "require_once 'modelo_datos.php';\n";
$ctrlLista .= "require_once 'conexion.php'; // archivo con la conexión a la BD\n\n";
$ctrlLista .= "/** Controlador de lista para $tabla */\n";
$ctrlLista .= "function listar_$tabla() {\n";
$ctrlLista .= "    global \$conn;\n";
$ctrlLista .= "    \$sql = 'SELECT * FROM $tabla';\n";
$ctrlLista .= "    \$res = \$conn->query(\$sql);\n";
$ctrlLista .= "    return \$res->fetch_all(MYSQLI_ASSOC);\n";
$ctrlLista .= "}\n";
file_put_contents("$dir/controller_lista.php", $ctrlLista);


$ctrlForm = "<?php\n";
$ctrlForm .= "require_once 'modelo_datos.php';\n";
$ctrlForm .= "require_once 'conexion.php';\n\n";
$ctrlForm .= "/** Controlador de formulario para $tabla */\n";
$ctrlForm .= "function guardar_$tabla(\$datos) {\n";
$ctrlForm .= "    global \$conn;\n";
$cols = implode(", ", $campos);
$vals = implode(", ", array_map(fn($c) => "'{\$datos['$c']}'", $campos));
$ctrlForm .= "    \$sql = \"INSERT INTO $tabla ($cols) VALUES ($vals)\";\n";
$ctrlForm .= "    return \$conn->query(\$sql);\n";
$ctrlForm .= "}\n\n";
$ctrlForm .= "function obtener_$tabla(\$id) {\n";
$ctrlForm .= "    global \$conn;\n";
$ctrlForm .= "    \$sql = \"SELECT * FROM $tabla WHERE $primaryKey = '\$id'\";\n";
$ctrlForm .= "    \$res = \$conn->query(\$sql);\n";
$ctrlForm .= "    return \$res->fetch_assoc();\n";
$ctrlForm .= "}\n";
file_put_contents("$dir/controller_form.php", $ctrlForm);


$thead = "";
$tdata = "";
foreach ($campos as $c) {
    $thead .= "<th>$c</th>";
    $tdata .= "<td><?= \$fila['$c'] ?></td>";
}
$templateLista = <<<HTML

<h2>Listado de $tabla</h2>
<table border="1">
    <tr>
        $thead
        <th>Acciones</th>
    </tr>
    <?php foreach (\$datos as \$fila): ?>
    <tr>
        $tdata
        <td><a href="form_$tabla.php?id=<?= \$fila['$primaryKey'] ?>">Editar</a></td>
    </tr>
    <?php endforeach; ?>
</table>
HTML;
file_put_contents("$dir/template_lista.php", $templateLista);


$formCampos = "";
foreach ($campos as $c) {
    $formCampos .= "<label>$c</label><input type='text' name='$c' value='<?= \$dato['$c'] ?? '' ?>'><br>\n";
}
$templateForm = <<<HTML

<h2>Formulario de $tabla</h2>
<form method="post" action="">
    $formCampos
    <input type="submit" value="Guardar">
</form>
HTML;
file_put_contents("$dir/template_form.php", $templateForm);


if (!file_exists("$dir/conexion.php")) {
    $conexion = <<<PHP
<?php
\$conn = new mysqli('$host', '$user', '$password', '$dbname');
if (\$conn->connect_error) {
    die("Conexión fallida: " . \$conn->connect_error);
}
?>
PHP;
    file_put_contents("$dir/conexion.php", $conexion);
}

echo "✅ Archivos generados en la carpeta: $dir\n";
?>
