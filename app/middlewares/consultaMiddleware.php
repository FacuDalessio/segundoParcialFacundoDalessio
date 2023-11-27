<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;
    use Slim\Routing\RouteContext;

    class ConsultaFecha{
        public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (isset($body['moneda']) && isset($body['tipoCuenta'])) {

            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltó enviar datos para la consulta"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    } 
    class ConsultaCuenta{
        public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (isset($body['cuenta'])) {

            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltó enviar datos para la consulta"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    }
    class ConsultaTipoCuenta{
        public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (isset($body['tipoCuenta'])) {

            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltó enviar datos para la consulta"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    }
    class ConsultaMoneda{
        public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (isset($body['moneda'])) {

            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltó enviar datos para la consulta"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
    }
?>