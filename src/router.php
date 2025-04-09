<?php
require_once __DIR__ . '/Datahandler.php';

function routeRequest($request) {
    $path = $request->getUri()->getPath();

    // Rutas para archivos estáticos
    if ($path === '/') {
        return new React\Http\Message\Response(
            200,
            ['Content-Type' => 'text/html'],
            file_get_contents(__DIR__ . '/../public/index.html')
        );
    }

    if ($path === '/data-view') {
        return new React\Http\Message\Response(
            200,
            ['Content-Type' => 'text/html'],
            file_get_contents(__DIR__ . '/../public/data-view.html')
        );
    }

    if ($path === '/contact') {
        return new React\Http\Message\Response(
            200,
            ['Content-Type' => 'text/html'],
            file_get_contents(__DIR__ . '/../public/contact.html')
        );
    }

    // Ruta para la API de datos
    if (strpos($path, '/api/data') === 0) {
        return handleDataRequest($request);
    }

    return new React\Http\Message\Response(
        404,
        ['Content-Type' => 'text/plain'],
        'Not found 404'
    );
}