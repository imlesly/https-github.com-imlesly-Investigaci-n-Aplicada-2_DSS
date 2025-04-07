<?php
use React\MySQL\Factory;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

function handleDataRequest(ServerRequestInterface $request)
{
    $loop = \React\EventLoop\Loop::get();
    $factory = new Factory($loop);
    
    // Asegúrate de que estos datos coincidan con tu configuración de MySQL
    $db = $factory->createLazyConnection('root:@localhost/dssdb2');

    $method = $request->getMethod();
    $path = $request->getUri()->getPath();

    // GET para obtener todos los datos
    if ($method === 'GET') {
        return $db->query('SELECT * FROM datos')
            ->then(function (\React\MySQL\QueryResult $result) {
                return new Response(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode($result->resultRows)
                );
            });
    }

    // POST para insertar nuevos datos
    if ($method === 'POST') {
        $data = json_decode((string)$request->getBody(), true);
        $nombre = $data['nombre'] ?? '';
        $valor = $data['valor'] ?? '';

        return $db->query('INSERT INTO datos (nombre, valor) VALUES (?, ?)', [$nombre, $valor])
            ->then(function () {
                return new Response(
                    201,
                    ['Content-Type' => 'application/json'],
                    json_encode(['message' => 'Datos insertados correctamente'])
                );
            });
    }

    // DELETE para eliminar registros
    if ($method === 'DELETE') {
        $parts = explode('/', $path);
        $id = end($parts);

        return $db->query('DELETE FROM datos WHERE id = ?', [$id])
            ->then(function () {
                return new Response(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode(['message' => 'Registro eliminado correctamente'])
                );
            });
    }

    return new Response(
        405,
        ['Content-Type' => 'application/json'],
        json_encode(['error' => 'Método no permitido'])
    );
}