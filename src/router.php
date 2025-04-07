<?php
use React\Http\Message\Response;
use Psr\Http\Message\ServerRequestInterface;

require_once __DIR__ . '/DataHandler.php';
require_once __DIR__ . '/ContactHandler.php';

function routeRequest(ServerRequestInterface $request)
{
    $path = $request->getUri()->getPath();
    $method = $request->getMethod();

    if ($path === "/") {
        return new Response(200, ['Content-Type' => 'text/html'], file_get_contents(__DIR__ . '/../public/index.html'));
    }

    if ($path === "/contact" && $method === "GET") {
        return new Response(200, ['Content-Type' => 'text/html'], file_get_contents(__DIR__ . '/../public/contact.html'));
    }

    if ($path === "/contact" && $method === "POST") {
        return handleContactForm($request);
    }

    if ($path === "/style.css") {
        return new Response(200, ['Content-Type' => 'text/css'], file_get_contents(__DIR__ . '/../public/style.css'));
    }

    if (str_starts_with($path, "/data")) {
        return handleDataRequest($request);
    }

    return new Response(404, [], "Ruta no encontrada");
}
