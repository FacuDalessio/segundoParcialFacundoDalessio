<?php
class Deposito implements JsonSerializable
{
    private $id;
    private $cuenta;
    private $tipoCuenta; // Nuevo atributo
    private $moneda;
    private $monto;
    private $fecha;
    private $cancelado;

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'cuenta' => $this->cuenta,
            'tipoCuenta' => $this->tipoCuenta,
            'moneda' => $this->moneda,
            'monto' => $this->monto,
            'fecha' => $this->fecha,
            'cancelado' => $this->cancelado
        ];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCuenta()
    {
        return $this->cuenta;
    }

    public function getTipoCuenta()
    {
        return $this->tipoCuenta;
    }

    public function getMoneda()
    {
        return $this->moneda;
    }

    public function getMonto()
    {
        return $this->monto;
    }

    public function getFecha()
    {
        return $this->fecha;
    }

    public function getCancelado()
    {
        return $this->cancelado;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setCuenta($cuenta)
    {
        $this->cuenta = $cuenta;
    }

    public function setTipoCuenta($tipoCuenta)
    {
        $this->tipoCuenta = $tipoCuenta;
    }

    public function setMoneda($moneda)
    {
        $this->moneda = $moneda;
    }

    public function setMonto($monto)
    {
        $this->monto = $monto;
    }

    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
    }

    public function setCancelado($cancelado)
    {
        $this->cancelado = $cancelado;
    }

    public function crearDeposito()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO depositos (cuenta, tipoCuenta, moneda, monto, fecha, cancelado)
         VALUES (:cuenta, :tipoCuenta, :moneda, :monto, :fecha, :cancelado)");
        $consulta->bindValue(':cuenta', $this->cuenta, PDO::PARAM_INT);
        $consulta->bindValue(':tipoCuenta', $this->tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':monto', $this->monto, PDO::PARAM_INT);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':cancelado', $this->cancelado, PDO::PARAM_BOOL);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodosDepositos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }

    public static function buscarUnoDeposito($idDeposito)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM depositos WHERE id = :id");
        $consulta->bindValue(':id', $idDeposito, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Deposito');
    }

    public static function cancelarDeposito($idDeposito)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE depositos SET cancelado = true WHERE id = :id");
        $consulta->bindValue(':id', $idDeposito, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function listarPorTipoCuentaFechaMoneda($tipoCuenta, $fecha, $moneda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE tipoCuenta = :tipoCuenta AND moneda = :moneda AND fecha = :fecha");
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $moneda, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
    }

    public static function listarPorCuenta($cuenta)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();

    $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE cuenta = :cuenta");
    $consulta->bindValue(':cuenta', $cuenta, PDO::PARAM_INT);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
}
public static function listarPorTipoCuenta($tipoCuenta)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();

    $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE tipoCuenta = :tipoCuenta");
    $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
}
public static function listarPorMoneda($moneda)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();

    $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM depositos WHERE moneda = :moneda");
    $consulta->bindValue(':moneda', $moneda, PDO::PARAM_STR);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Deposito');
}
}
