<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

$checkCredentials = function (Request $request, RequestHandler $handler): Response {

    // echo "In function checkCredentials" . PHP_EOL;

    if ($request->getMethod() == 'GET') {
        echo "Es un GET" . PHP_EOL;
        $response = $handler->handle($request);
    } elseif ($request->getMethod() == 'POST') {
        echo "Es un POST" . PHP_EOL;
        $body = $request->getParsedBody();

        if ($body['perfil'] == 'Administrador') {
            echo "El nombre del usuario es " . $body['nombre'] . PHP_EOL;
            $response = $handler->handle($request);
        } else {
            // Mensaje de Error
            $response = new Response();
            $response->getBody()->write("El usuario no tiene permisos.");
        }
    }

    return $response;
};

$checkCredentialsJSON = function (Request $request, RequestHandler $handler): Response {

    // echo "In function checkCredentials" . PHP_EOL;

    if ($request->getMethod() == 'GET') {
        // echo "Es un GET" . PHP_EOL;
        $response = $handler->handle($request);
    } elseif ($request->getMethod() == 'POST') {
        // echo "Es un POST" . PHP_EOL;

        $contentType = $request->getHeaderLine('Content-Type');

        if (strstr($contentType, 'application/json')) {
            // $contents = $request->getParsedBody();
            // var_dump($contents['obj_json']);
            // var_dump($contents['nombre']);
            // var_dump($contents->perfil);

            
            $contents = json_decode(file_get_contents('php://input'), true);
            $obj_json = $contents['obj_json'];
            
            if ($obj_json['perfil'] == 'Administrador') {
                // echo "El nombre del usuario es " . $obj_json['nombre'] . PHP_EOL;
                $response = $handler->handle($request);
            } else {
                // Mensaje de Error
                $response = new Response();
                $response->getBody()->write("El usuario no tiene permisos.");
            }


        };
    }

    return $response;
};