<?php
require './baseDatos/accesoDatos.php';

class Cuenta implements JsonSerializable{

    private $nroCuenta;
    private $nombre;
    private $apellido;
    private $tipoDocumento;    
    private $nroDocumento;
    private $email;
    private $tipoCuenta;
    private $moneda;
    private $saldo;
    private $softDelete;

    public function getNroCuenta()
    {
        return $this->nroCuenta;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellido()
    {
        return $this->apellido;
    }

    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    public function getNroDocumento()
    {
        return $this->nroDocumento;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    public function getMoneda()
    {
        return $this->moneda;
    }

    public function getSaldo()
    {
        return $this->saldo;
    }

    public function getSoftDelete()
    {
        return $this->softDelete;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;
    }

    public function setNroDocumento($nroDocumento)
    {
        $this->nroDocumento = $nroDocumento;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;
    }

    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    }

    public function setSaldo($saldo)
    {
        $this->saldo = $saldo;
    }

    public function setSoftDelete($softDelete)
    {
        $this->softDelete = $softDelete;
    }


    // public function __construct($nombre, $apellido, $tipoDocumento, $nroDocumento, $email, $tipoCuenta, $moneda, $saldoInicial = 0)
    // {
    //     $this->nombre = $nombre;
    //     $this->apellido = $apellido;
    //     $this->email = $email;
    //     $this->tipoDocumento = $tipoDocumento;
    //     $this->nroDocumento = $nroDocumento;
    //     $this->tipoCuenta = $tipoCuenta;
    //     $this->moneda = $moneda;
    //     $this->saldo[$moneda] = $saldoInicial;
    //     $this->softDelete = false;
    // }

    public function jsonSerialize() {
        return [
            'nroCuenta' => $this->nroCuenta,
            'nombre' => $this->nombre,
            'apellido' => $this->apellido,
            'email' => $this->email,
            'tipoDocumento' => $this->tipoDocumento,
            'nroDocumento' => $this->nroDocumento,
            'tipoCuenta' => $this->tipoCuenta,
            'saldo' => $this->saldo,
            'softDelete' => $this->softDelete
        ];
    }

    public function crearCuenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cuentas (nombre, apellido, tipoDocumento, nroDocumento, email, tipoCuenta, moneda, saldo, softDelete)
         VALUES (:nombre, :apellido, :tipoDocumento, :nroDocumento, :email, :tipoCuenta, :moneda, :saldo, :softDelete)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':nroDocumento', $this->nroDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $this->saldo, PDO::PARAM_INT);
        $consulta->bindValue(':softDelete', $this->softDelete, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodasCuentas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }

    public static function obtenerCuentasActivas()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE softDelete = false");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }

    public static function buscarUnaCuenta($nroCuenta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM cuentas WHERE nroCuenta = :nroCuenta");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Cuenta');
    }

    public static function eliminarCuenta($nroCuenta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cuentas SET softDelete = true WHERE nroCuenta = :nroCuenta");
        $consulta->bindValue(':nroCuenta', $nroCuenta, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function modificarCuenta($cuenta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE cuentas SET nombre = :nombre, apellido = :apellido, email = :email, tipoDocumento = :tipoDocumento,
                                                 nroDocumento = :nroDocumento, tipoCuenta = :tipoCuenta, moneda = :moneda, saldo = :saldo WHERE nroCuenta = :nroCuenta");
        $consulta->bindValue(':nombre', $cuenta->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $cuenta->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':email', $cuenta->email, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $cuenta->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':nroDocumento', $cuenta->nroDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':tipoCuenta', $cuenta->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $cuenta->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $cuenta->saldo, PDO::PARAM_INT);
        $consulta->bindValue(':nroCuenta', $cuenta->nroCuenta, PDO::PARAM_INT);
        $consulta->execute();
    }
}
    
?>