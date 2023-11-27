<?php
    require_once './models/cuenta.php';
    require_once './models/retiro.php';

    class RetiroController {

        public function realizarRetiro($request, $response, $args) {
            $body = $request->getParsedBody();
    
            $cuenta = Cuenta::buscarUnaCuenta($body['nroCuenta']);
            if ($cuenta != false) {
                $retiro = new Retiro();
    
                $retiro->setCuenta($body['nroCuenta']);
                $retiro->setMoneda($body['moneda']);
                $retiro->setMonto($body['monto']);
                $retiro->setTipoCuenta($body['tipoCuenta']);
                $fechaActual = new DateTime();
                $fechaActual = $fechaActual->format('Y-m-d');
                $retiro->setFecha($fechaActual);
                $retiro->setCancelado(false);
    
                $saldo = $cuenta->getSaldo() - $body['monto'];
                if ($saldo < 0) {
                    $payload = json_encode(array("Error" => "Fondos insuficientes para realizar el retiro"));
                } else {
                    $retiro->crearRetiro();
                    $cuenta->setSaldo($saldo);
                    Cuenta::modificarCuenta($cuenta);
    
                    $payload = json_encode(array("mensaje" => "Retiro realizado con éxito"));
                }
            } else {
                $payload = json_encode(array("Error" => "No se encontró una cuenta con ese número"));
            }
    
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }
    
?>