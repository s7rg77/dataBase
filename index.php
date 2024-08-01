<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>dataBase</title>
    <style>
        html,
        body {
            margin: 0px;
            padding: 0px;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        body {
            background-color: black;
            color: #00FF00;
            font-family: monospace;
            font-size: 16px;
        }

        h1,
        h2 {
            margin-left: 10px;
            color: #FFFFFF;
            font-weight: normal;
        }

        header {
            margin-top: 10px;
            margin-right: 10px;
            display: flex;
            justify-content: flex-end;
        }

        main {
            margin-bottom: 10px;
            flex: 1;
        }

        .home,
        .doc,
        .git {
            margin-left: 10px;
            width: 100px;
        }

        p {
            margin-left: 10px;
        }

        label {
            margin-left: 10px;
        }

        input {
            padding: 5px;
            border: 1px solid #00FF00;
            background-color: black;
            color: #00FF00;
        }

        button {
            padding: 5px;
            border: none;
            background-color: #006400;
            color: #00FF00;
            cursor: pointer;
        }

        footer {
            width: 100%;
            background-color: #006400;
            color: #00FF00;
            text-align: center;
        }
    </style>
    <script>
        function goHome() {

            window.location.href = '/';

        }

        function goGit() {

            window.location.href = 'https://github.com/s7rg77/dataBase';

        }

        function goDoc() {

            window.location.href = '/doc';
            
        }
    </script>
</head>

<body>
    <header>
        <button class="doc" onclick="goDoc()">doc</button>
        <button class="git" onclick="goGit()">git</button>
        <button class="home" onclick="goHome()">back</button>
    </header>
    
    <main>
        <h1>dataBase</h1>
        <h2>sergio lópez</h2>
        <?php

        require_once 'Libros.php';

        $host = "...";
        $username = "...";
        $password = "...";
        $dbname = "...";

        $libros = new Libros();

        $mysqli = $libros->conexion($host, $username, $password, $dbname);

        ?>

        <form method="GET">
            <p>introduzca id de autor (0-1, vacío para mostrar todos)</p>
            <label for="id">id </label>
            <input type="text" name="id" id="id" placeholder="">
            <button type="submit" name="consultarAutores">consultar autores</button>
        </form>
        <br>
        <form method="GET">
            <p>introduzca id de autor (0-1, vacío para mostrar todos)</p>
            <label for="id">id </label>
            <input type="text" name="id" id="id" placeholder="">
            <button type="submit" name="consultarLibros">consultar libros</button>
        </form>
        <br>
        <form method="GET">
            <p>introduzca id de libro (0-6)</p>
            <label for="id">id </label>
            <input type="text" name="id" id="id" placeholder="">
            <button type="submit" name="consultarDatos">consultar datos</button>
        </form>
        <br>
        <h2>datos</h2>

        <?php
        if ($mysqli) {
            $getAutores = isset($_GET['consultarAutores']);
            if ($getAutores) {
                $id = isset($_GET['id']) ? $_GET['id'] : null;
                if (!is_numeric($id) && $id !== null && $id !== '') {
                    echo "<p>introduzca una id válida</p>";
                } else {
                    $datos = $libros->consultarAutores($mysqli, $id);
                    if (is_object($datos) && get_class($datos) === 'stdClass') {
                        printf("<p>autor: %s %s (%s)</p>", $datos->nombre, $datos->apellidos, $datos->nacionalidad);
                    } elseif (is_array($datos) && !empty($datos)) {
                        foreach ($datos as $autor) {
                            printf("<p>autor: %s %s (%s)</p>", $autor[1], $autor[2], $autor[3]);
                        }
                    } else {
                        echo "<p>no se encontraron resultados</p>";
                    }
                }
            }
        } else {
            echo "<p>error de conexión a la base de datos</p>";
        }

        if ($mysqli) {
            $getLibros = isset($_GET['consultarLibros']);
            if ($getLibros) {
                $id_autor = isset($_GET['id']) ? $_GET['id'] : null;
                if (!is_numeric($id_autor) && $id_autor !== null && $id_autor !== '') {
                    echo "<p>introduzca una id válida</p>";
                } else {
                    $datos = $libros->consultarAutores($mysqli, $id_autor);
                    if ($datos !== null) {
                        if (is_array($datos)) {
                            foreach ($datos as $autor) {
                                printf("<p>autor: %s %s (%s)</p>", $autor[1], $autor[2], $autor[3]);
                            }
                        } else {
                            printf("<p>autor: %s %s (%s)</p>", $datos->nombre, $datos->apellidos, $datos->nacionalidad);
                        }
                    } else {
                        echo "<p>error al obtener datos del autor</p>";
                    }
                    $datos = $libros->consultarLibros($mysqli, $id_autor);
                    if (!empty($datos)) {
                        foreach ($datos as $libro) {
                            printf("<p>libro: %s (%s)</p>", $libro->titulo, $libro->f_publicacion);
                        }
                    } else {
                        echo "<p>no se encontraron resultados</p>";
                    }
                }
            }
        } else {
            echo "<p>error de conexión a la base de datos</p>";
        }

        $getDatosLibros = isset($_GET['consultarDatos']);
        if ($getDatosLibros) {
            $id = isset($_GET['id']) ? $_GET['id'] : null;
            if ($id !== null && !is_numeric($id)) {
                echo "<p>introduzca una id válida</p>";
            } else {
                $datos_libros = $libros->consultarDatosLibro($mysqli, $id);
                if ($datos_libros !== null) {
                    if (is_array($datos_libros)) {
                        foreach ($datos_libros as $datos_libro) {
                            echo "<p>id: " . $datos_libro->id . "</p>";
                            echo "<p>título: " . $datos_libro->titulo . "</p>";
                            echo "<p>fecha de publicación: " . $datos_libro->f_publicacion . "</p>";
                            $id_autor = $datos_libro->id_autor;
                            $datos = $libros->consultarAutores($mysqli, $id_autor);
                            if ($datos !== null) {
                                if (is_array($datos)) {
                                    foreach ($datos as $autor) {
                                        echo "<p>autor: " . $autor[1] . " " . $autor[2] . "</p>";
                                        echo "<p>nacionalidad: " . $autor[3] . "</p>";
                                    }
                                } else {
                                    echo "<p>autor: " . $datos->nombre . " " . $datos->apellidos . "</p>";
                                    echo "<p>nacionalidad: " . $datos->nacionalidad . "</p>";
                                }
                            } else {
                                echo "<p>error al obtener datos del autor</p>";
                            }
                        }
                    } else {
                        echo "<p>id: " . $datos_libros->id . "</p>";
                        echo "<p>título: " . $datos_libros->titulo . "</p>";
                        echo "<p>fecha de publicacion: " . $datos_libros->f_publicacion . "</p>";
                        $id_autor = $datos_libros->id_autor;
                        $datos = $libros->consultarAutores($mysqli, $id_autor);
                        if ($datos !== null) {
                            if (is_array($datos)) {
                                foreach ($datos as $autor) {
                                    echo "<p>autor: " . $autor[1] . " " . $autor[2] . "</p>";
                                    echo "<p>nacionalidad: " . $autor[3] . "</p>";
                                }
                            } else {
                                echo "<p>autor: " . $datos->nombre . " " . $datos->apellidos . "</p>";
                                echo "<p>nacionalidad: " . $datos->nacionalidad . "</p>";
                            }
                        } else {
                            echo "<p>error al obtener datos del autor</p>";
                        }
                    }
                } else {
                    echo "<p>error al consultar datos del libro</p>";
                }
            }
        }

        $libros->closeConnection();

        ?>
    </main>

    <footer>
        <h3>desarrollo web entorno servidor</h3>
    </footer>

</body>

</html>