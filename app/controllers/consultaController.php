<?php
    require_once './models/cuenta.php';
    require_once './models/deposito.php';
    require_once './models/retiro.php';

    class ConsultaController{

        public function consultaDeposito($request, $response, $args){
            $paramas =  $request->getQueryParams();
            $moneda = $paramas['moneda'];
            $tipoCuenta = $paramas['tipoCuenta'];
            if (!isset($paramas['fecha'])) {
                $fechaAux = new DateTime();
                $fechaAux->sub(new DateInterval('P1D'));
                $fecha = $fechaAux->format('Y-m-d');
            }else{
                $fecha = $paramas['fecha'];
            }
            $lista = Deposito::listarPorTipoCuentaFechaMoneda($tipoCuenta, $fecha, $moneda);
            $montoTotal = 0;
            foreach ($lista as $deposito) {
                $montoTotal+= $deposito->getMonto();
            }
            $payload = json_encode(array("Monto" => $montoTotal));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaDepositoCuenta($request, $response, $args){
            $paramas =  $request->getQueryParams();

            $lista = Deposito::listarPorCuenta($paramas['cuenta']);
            $payload = json_encode(array("Lista depositos" => $lista));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaDepositoTipoCuenta($request, $response, $args){
            $paramas =  $request->getQueryParams();

            $lista = Deposito::listarPorTipoCuenta($paramas['tipoCuenta']);
            $payload = json_encode(array("Lista depositos" => $lista));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaDepositoMoneda($request, $response, $args){
            $paramas =  $request->getQueryParams();

            $lista = Deposito::listarPorMoneda($paramas['moneda']);
            $payload = json_encode(array("Lista depositos" => $lista));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaRetiro($request, $response, $args){
            $paramas =  $request->getQueryParams();
            $moneda = $paramas['moneda'];
            $tipoCuenta = $paramas['tipoCuenta'];
            if (!isset($paramas['fecha'])) {
                $fechaAux = new DateTime();
                $fechaAux->sub(new DateInterval('P1D'));
                $fecha = $fechaAux->format('Y-m-d');
            }else{
                $fecha = $paramas['fecha'];
            }
            $lista = Retiro::listarPorTipoCuentaFechaMoneda($tipoCuenta, $fecha, $moneda);
            $montoTotal = 0;
            foreach ($lista as $retiro) {
                $montoTotal+= $retiro->getMonto();
            }
            $payload = json_encode(array("Monto" => $montoTotal));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaRetiroCuenta($request, $response, $args){
            $paramas =  $request->getQueryParams();

            $lista = Retiro::listarPorCuenta($paramas['cuenta']);
            $payload = json_encode(array("Lista retiros" => $lista));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaRetiroTipoCuenta($request, $response, $args){
            $paramas =  $request->getQueryParams();

            $lista = Retiro::listarPorTipoCuenta($paramas['tipoCuenta']);
            $payload = json_encode(array("Lista retiros" => $lista));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaRetiroMoneda($request, $response, $args){
            $paramas =  $request->getQueryParams();

            $lista = Retiro::listarPorMoneda($paramas['moneda']);
            $payload = json_encode(array("Lista retiros" => $lista));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }

        public function consultaRetiroDepositoPorCuenta($request, $response, $args){
            $paramas =  $request->getQueryParams();

            $retiros = Retiro::listarPorCuenta($paramas['cuenta']);
            $depositos = Deposito::listarPorCuenta($paramas['cuenta']);
            $payload = json_encode(array(
                "Retiros" => $retiros,
                "Depositos" => $depositos
            ));
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');

        }
    }
?>