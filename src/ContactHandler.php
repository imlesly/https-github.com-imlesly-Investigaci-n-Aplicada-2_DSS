<?php
use React\MySQL\Factory;
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

function handleContactForm(ServerRequestInterface $request)
{
    $loop = \React\EventLoop\Loop::get();
    $factory = new Factory($loop);

    $db = $factory->createLazyConnection('root:@localhost/dssdb2');

    $params = $request->getParsedBody();
    $nombre = $params['nombre'] ?? '';
    $email = $params['email'] ?? '';
    $mensaje = $params['mensaje'] ?? '';

    return $db->query('INSERT INTO mensajes (nombre, email, mensaje) VALUES (?, ?, ?)', [$nombre, $email, $mensaje])
        ->then(fn() => new Response(200, [], "Mensaje enviado correctamente."))
        ->otherwise(fn($e) => new Response(500, [], "Error al enviar: " . $e->getMessage()));
}