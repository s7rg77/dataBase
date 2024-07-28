<?php

class Libros
{

    private $mysqli;

    public function conexion($host, $user, $password, $db)
    {
        try {
            $this->mysqli = new mysqli($host, $user, $password, $db);
            echo "<p>conexión realizada</p>";
            return $this->mysqli;
        } catch (mysqli_sql_exception $e) {
            return null;
        }
    }

    public function consultarAutores($mysqli, $id_autor)
    {
        try {
            $query = "SELECT * FROM autor";
            if ($id_autor !== null && $id_autor !== '') {
                $query .= " WHERE id='$id_autor'";
                $result = $mysqli->query($query);
                if ($result) {
                    $autores = $result->fetch_object();
                    if ($autores === null) {
                        echo "<p>el autor con id $id_autor no existe<br></p>";
                    }
                } else {
                    echo "<p>error al realizar la consulta: " . $mysqli->error . "</p>";
                    return null;
                }
            } else {
                $result = $mysqli->query($query);
                $autores = [];
                while ($fila = $result->fetch_array(MYSQLI_NUM)) {
                    $autores[] = $fila;
                }
            }
            return $autores;
        } catch (mysqli_sql_exception $e) {
            echo "<p>error al consultar datos del autor: " . $e->getMessage() . "</p>";
            return null;
        }
    }

    public function consultarLibros($mysqli, $id_autor)
    {
        try {
            $query = "SELECT * FROM libro";
            if ($id_autor !== null && $id_autor !== '') {
                $query .= " WHERE id_autor='$id_autor'";
                $result = $mysqli->query($query);
                if ($result) {
                    $datos = [];
                    while ($fila = $result->fetch_object()) {
                        $fila->f_publicacion = date('d/m/Y', strtotime($fila->f_publicacion));
                        $datos[] = $fila;
                    }
                    if (empty($datos)) {
                        echo "<p>los libros con id de autor $id_autor no existen</p>";
                    }
                } else {
                    echo "<p>error al realizar la consulta: " . $mysqli->error . "</p>";
                    return null;
                }
            } else {
                $result = $mysqli->query($query);
                $datos = [];
                while ($fila = $result->fetch_object()) {
                    $fila->f_publicacion = date('d/m/Y', strtotime($fila->f_publicacion));
                    $datos[] = $fila;
                }
            }
            return $datos;
        } catch (mysqli_sql_exception $e) {
            echo "<p>error al consultar datos de los libros: " . $e->getMessage() . "</p>";
            return null;
        }
    }

    public function consultarDatosLibro($mysqli, $id_libro)
    {
        try {
            $query = "SELECT * FROM libro WHERE id='$id_libro'";
            $result = $mysqli->query($query);
            if ($result) {
                $libro = $result->fetch_object();
                if ($libro !== null) {
                    $libro->f_publicacion = date('d/m/Y', strtotime($libro->f_publicacion));
                } else {
                    echo "<p>el libro con id $id_libro no existe</p>";
                }
                return $libro;
            } else {
                echo "<p>error al realizar la consulta: " . $mysqli->error . "</p>";
                return null;
            }
        } catch (mysqli_sql_exception $e) {
            echo "<p>error al consultar datos del libro: " . $e->getMessage() . "</p>";
            return null;
        }
    }

    public function borrarAutor($mysqli, $id_autor)
    {
        try {
            $query = "DELETE FROM autor WHERE id='$id_autor'";
            if ($mysqli->query($query) === true) {
                echo "<p>autor eliminado correctamente, </p>";
                return true;
            } else {
                echo "<p>error al eliminar autor: " . $mysqli->error . "</p>";
                return false;
            }
        } catch (mysqli_sql_exception $e) {
            echo "<p>error al eliminar autor: " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public function borrarLibro($mysqli, $id_libro)
    {
        try {
            $query = "DELETE FROM libro WHERE id='$id_libro'";
            if ($mysqli->query($query) === true) {
                echo "<p>libro eliminado correctamente</p>";
                return true;
            } else {
                echo "<p>error al eliminar libro: " . $mysqli->error . "</p>";
                return false;
            }
        } catch (mysqli_sql_exception $e) {
            echo "<p>error al eliminar libro: " . $e->getMessage() . "</p>";
            return false;
        }
    }

    public function closeConnection()
    {
        if ($this->mysqli) {
            $this->mysqli->close();
            echo "<p>conexión cerrada</p>";
        }
    }
}
/*
$libros = new Libros();
$mysqli = $libros->conexion("...", "...", "...", "...");

if ($mysqli) {
$libros->consultarAutores($mysqli, 0);
$libros->consultarLibros($mysqli, 0);
$libros->consultarDatosLibro($mysqli, 0);

$libros->closeConnection();
} else {
echo "<p>error al conectar a la base de datos. </p>";
}
 */