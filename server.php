<?php
require 'vendor/autoload.php';

use React\Http\HttpServer;
use React\Socket\SocketServer;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;

require_once __DIR__ . '/src/router.php';

$server = new HttpServer(function (ServerRequestInterface $request) {
    return routeRequest($request);
});

$socket = new SocketServer("127.0.0.1:8080");
$server->listen($socket);

echo "Servidor escuchando en http://127.0.0.1:8080\n";
