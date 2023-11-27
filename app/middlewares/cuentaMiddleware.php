<?php
    use Psr\Http\Message\ServerRequestInterface as Request;
    use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
    use Slim\Psr7\Response;
    use Slim\Routing\RouteContext;

    class CargarCuenta
{
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $body = $request->getParsedBody();

        if (isset($body['nombre']) && isset($body['apellido']) && isset($body['tipoDocumento']) && isset($body['nroDocumento'])
            && isset($body['email']) && isset($body['tipoCuenta']) && isset($body['moneda'])) {

            $response = $handler->handle($request);
        } else {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltó enviar datos para la creación de la cuenta"));
            $response->getBody()->write($payload);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}

    class ModificarCuenta
    {
        public function __invoke(Request $request, RequestHandler $handler): Response
        {
            $datos = file_get_contents("php://input");
            $datosJson = json_decode($datos, true);

            $atributos = ['nombre', 'apellido', 'email', 'tipoDocumento', 'nroDocumento', 'tipoCuenta', 'moneda'];

            $routeContext = RouteContext::fromRequest($request);
            $route = $routeContext->getRoute();

            $cuenta = Cuenta::buscarUnaCuenta($route->getArgument('nroCuenta'));

            if ($cuenta != false) {

                foreach ($atributos as $atributo) {
                    if (isset($datosJson[$atributo]) && $datosJson[$atributo] != null) {

                        $setter = 'set' . ucfirst($atributo);

                        if (method_exists($cuenta, $setter)) {
                            $cuenta->{$setter}($datosJson[$atributo]);
                        }
                    }
                }

                $request = $request->withAttribute('cuenta', $cuenta);
                $response = $handler->handle($request);
            } else {
                $response = new Response();
                $payload = json_encode(array("Error" => "No se encontró una cuenta con ese numero"));
                $response->getBody()->write($payload);
            }

            return $response->withHeader('Content-Type', 'application/json');
            }
    }   
?>