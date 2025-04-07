<?php
use React\MySQL\Factory;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

function handleDataRequest(ServerRequestInterface $request)
{
    $loop = \React\EventLoop\Loop::get();
    $factory = new Factory($loop);
    $db = $factory->createLazyConnection('root:@localhost/dssdb2');

    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    // GET request to fetch all data
    if ($method === "GET") {
        return $db->query('SELECT * FROM datos')
            ->then(function (\React\MySQL\QueryResult $result) {
                return new Response(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode($result->resultRows)
                );
            })
            ->otherwise(function (Exception $e) {
                return new Response(
                    500,
                    ['Content-Type' => 'application/json'],
                    json_encode(['error' => $e->getMessage()])
                );
            });
    }

    // POST request to add new data
    if ($method === "POST") {
        $params = json_decode((string)$request->getBody(), true);
        $nombre = $params['nombre'] ?? '';
        $valor = $params['valor'] ?? '';

        return $db->query('INSERT INTO datos (nombre, valor) VALUES (?, ?)', [$nombre, $valor])
            ->then(function () {
                return new Response(
                    201,
                    ['Content-Type' => 'application/json'],
                    json_encode(['message' => 'Registro creado exitosamente'])
                );
            })
            ->otherwise(function (Exception $e) {
                return new Response(
                    500,
                    ['Content-Type' => 'application/json'],
                    json_encode(['error' => $e->getMessage()])
                );
            });
    }

    // DELETE request to remove data
    if ($method === "DELETE") {
        $parts = explode('/', $path);
        $id = end($parts);

        return $db->query('DELETE FROM datos WHERE id = ?', [$id])
            ->then(function () {
                return new Response(
                    200,
                    ['Content-Type' => 'application/json'],
                    json_encode(['message' => 'Registro eliminado exitosamente'])
                );
            })
            ->otherwise(function (Exception $e) {
                return new Response(
                    500,
                    ['Content-Type' => 'application/json'],
                    json_encode(['error' => $e->getMessage()])
                );
            });
    }

    return new Response(
        405,
        ['Content-Type' => 'application/json'],
        json_encode(['error' => 'Método no permitido'])
    );
}