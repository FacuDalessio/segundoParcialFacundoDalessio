<?php
    require_once './models/cuenta.php';
    require_once './models/deposito.php';
    require_once './models/retiro.php';
    require_once './models/ajuste.php';

    class AjusteController{
        public function realizarAjuste($request, $response, $args) {
            $body = $request->getParsedBody();

            $ajuste = new Ajuste();
            $ajuste->setMotivo($body['motivo']);
            $ajuste->setNombreTransaccion($body['nombreTransaccion']);
            $ajuste->setTransaccion($body['nroTransaccion']);

            switch ($body['nombreTransaccion']) {
                case 'deposito':
                    $deposito = Deposito::buscarUnoDeposito($body['nroTransaccion']);
                    if ($deposito != false) {
                        $cuenta = Cuenta::buscarUnaCuenta($deposito->getCuenta());
                        $saldo = $cuenta->getSaldo() - $deposito->getMonto();
                        $cuenta->setSaldo($saldo);
                        Cuenta::modificarCuenta($cuenta);
                        Deposito::cancelarDeposito($body['nroTransaccion']);
                        $ajuste->crearAjuste();
                        $payload = json_encode(array("mensaje" => "Ajuste realizado con éxito"));
                    }else{
                        $payload = json_encode(array("Error" => "No se encontro un deposito con ese numero"));
                    }
                    break;
                case 'retiro':
                    $retiro = Retiro::buscarUnoRetiro($body['nroTransaccion']);
                    if ($retiro != false) {
                        $cuenta = Cuenta::buscarUnaCuenta($retiro->getCuenta());
                        $saldo = $cuenta->getSaldo() + $retiro->getMonto();
                        $cuenta->setSaldo($saldo);
                        Cuenta::modificarCuenta($cuenta);
                        Retiro::cancelarRetiro($body['nroTransaccion']);
                        $ajuste->crearAjuste();
                        $payload = json_encode(array("mensaje" => "Ajuste realizado con éxito"));
                    } else {
                        $payload = json_encode(array("Error" => "No se encontró un retiro con ese número"));
                    }
                    break;
                default:
                    $payload = json_encode(array("Error" => "Nombre de transacción incorrecto"));
                    break;
            }
            
            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }
?>