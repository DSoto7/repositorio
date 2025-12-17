-- Tabla: EstudianteCienciasComputacionales
CREATE TABLE EstudianteCienciasComputacionales (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    apellido VARCHAR(50) NOT NULL,
    edad INT,
    especialidad VARCHAR(50)
);