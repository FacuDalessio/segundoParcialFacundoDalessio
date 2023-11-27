<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;
    use Slim\Routing\RouteContext;

    class CargarDeposito
    {
        public function __invoke(Request $request, RequestHandler $handler): Response
        {
            $body = $request->getParsedBody();
    
            if (isset($body['nroCuenta']) && isset($body['moneda']) && isset($body['monto'])) {
    
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array("mensaje" => "Faltó enviar datos para la creación del depósito"));
                $response->getBody()->write($payload);
            }
    
            return $response->withHeader('Content-Type', 'application/json');
        }
    }
    
?>