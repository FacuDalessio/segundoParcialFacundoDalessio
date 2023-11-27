<?php
    require_once './models/cuenta.php';
    require_once './models/deposito.php';

    class DepositoController{

        public function altaDeposito($request, $response, $args){
            $body = $request->getParsedBody();

            $cuenta = Cuenta::buscarUnaCuenta($body['nroCuenta']);
            if ($cuenta != false) {

                $deposito = new Deposito();

                $deposito->setCuenta($body['nroCuenta']);
                $deposito->setMoneda($body['moneda']);
                $deposito->setMonto($body['monto']);
                $deposito->setTipoCuenta($body['tipoCuenta']);
                $fechaActual = new DateTime();
                $fechaActual = $fechaActual->format('Y-m-d');
                $deposito->setFecha($fechaActual);
                $deposito->setCancelado(false);

                $deposito->crearDeposito();

                $saldo = $cuenta->getSaldo() + $body['monto'];
                $cuenta->setSaldo($saldo);
                Cuenta::modificarCuenta($cuenta);

                $uploadedFiles = $request->getUploadedFiles();
                if (isset($uploadedFiles['imagen'])) {
                if (!is_dir(".//ImagenesDeDepositos/2023")) {
                    mkdir("./ImagenesDeDepositos/2023", 0777, true);
                }
                $imagen = $uploadedFiles['imagen'];
                $directorioDestino ="./ImagenesDeDepositos/2023/" . $cuenta->getNroCuenta().$cuenta->getTipoCuenta().".png";
                $imagen->moveTo($directorioDestino);
            }

                $payload = json_encode(array("mensaje" => "Depósito creado con éxito"));
            }else{
                $payload = json_encode(array("Error" => "No se encontro una cuenta con ese numero"));
            }

            $response->getBody()->write($payload);
            return $response
                ->withHeader('Content-Type', 'application/json');
        }
    }
?>