<?php
    require_once './models/cuenta.php';

    class CuentaController{

        public function altaCuenta($request, $response, $args){
              
            $body = $request->getParsedBody();
       
            $cuenta = new Cuenta();

            $cuenta->setNombre($body['nombre']);
            $cuenta->setApellido($body['apellido']);
            $cuenta->setTipoDocumento($body['tipoDocumento']);
            $cuenta->setNroDocumento($body['nroDocumento']);
            $cuenta->setEmail($body['email']);
            $cuenta->setTipoCuenta($body['tipoCuenta']);
            $cuenta->setMoneda($body['moneda']);
            $cuenta->setSaldo($body['saldo'] ?? 0);
            $cuenta->setSoftDelete(false);

            $nroCuenta = $cuenta->crearCuenta();

            $uploadedFiles = $request->getUploadedFiles();
            if (isset($uploadedFiles['imagen'])) {
                if (!is_dir("./ImagenesDeCuentas/2023")) {
                    mkdir("./ImagenesDeCuentas/2023", 0777, true);
                }
                $imagen = $uploadedFiles['imagen'];
                $directorioDestino ="./ImagenesDeCuentas/2023/" . $nroCuenta.$cuenta->getTipoCuenta().".png";
                $imagen->moveTo($directorioDestino);
            }

            $payload = json_encode(array("mensaje" => "Cuenta creado con exito"));

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
        }
        
        public function eliminarCuenta($request, $response, $args){
            $nroCuenta = $args['nroCuenta'];
            $cuenta = Cuenta::buscarUnaCuenta($nroCuenta);
            if ($cuenta != false) {
    
                Cuenta::eliminarCuenta($nroCuenta);

                if (!is_dir("./ImagenesBackupCuentas/2023")) {
                    mkdir("./ImagenesBackupCuentas/2023", 0777, true);
                }

                $directorioOrigen = "./ImagenesDeCuentas/2023/" . $cuenta->getNroCuenta() . $cuenta->getTipoCuenta() . ".png";
                if (file_exists($directorioOrigen)) {
                    $directorioDestinoNuevo = "./ImagenesBackupCuentas/2023/" . $cuenta->getNroCuenta() . $cuenta->getTipoCuenta() . ".png";
                    rename($directorioOrigen, $directorioDestinoNuevo);
                }

                $payload = json_encode(array("mensaje" => "Cuenta borrado con exito"));
            }else{
                $payload = json_encode(array("Error" => "No se encontro una cuenta con ese numero"));
            }
            
            $response->getBody()->write($payload);
            return $response
              ->withHeader('Content-Type', 'application/json');
        }
        
        public function consultarCuenta($request, $response, $args){
            $body = $request->getParsedBody();
            $cuenta = Cuenta::buscarUnaCuenta($body['nroCuenta']);
            if ($cuenta != false) {

                $payload = json_encode(array($cuenta->getMoneda() => $cuenta->getSaldo()));
            }else{
                $payload = json_encode(array("Error" => "No se encontro una cuenta con ese numero"));
            }
            
            $response->getBody()->write($payload);
            return $response
              ->withHeader('Content-Type', 'application/json');
            
        }
        
        public static function modificarCuenta($request, $response, $args){
            $cuenta = $request->getAttribute('cuenta');
        
            Cuenta::modificarCuenta($cuenta);

            $payload = json_encode(array("mensaje" => "Cuenta modificada con exito"));

            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
            }
    }
?>