<?php
    class Retiro implements JsonSerializable{
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

    public function crearRetiro()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO retiros (cuenta, tipoCuenta, moneda, monto, fecha, cancelado)
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

    public static function obtenerTodosRetiros()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM retiros");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Retiro');
    }

    public static function buscarUnoRetiro($idRetiro)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM retiros WHERE id = :id");
        $consulta->bindValue(':id', $idRetiro, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Retiro');
    }

    public static function cancelarRetiro($idRetiro)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE retiros SET cancelado = true WHERE id = :id");
        $consulta->bindValue(':id', $idRetiro, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function listarPorTipoCuentaFechaMoneda($tipoCuenta, $fecha, $moneda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM retiros WHERE tipoCuenta = :tipoCuenta AND moneda = :moneda AND fecha = :fecha");
        $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $moneda, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Retiro');
    }

    public static function listarPorCuenta($cuenta)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();

    $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM retiros WHERE cuenta = :cuenta");
    $consulta->bindValue(':cuenta', $cuenta, PDO::PARAM_INT);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Retiro');
}
public static function listarPorTipoCuenta($tipoCuenta)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();

    $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM retiros WHERE tipoCuenta = :tipoCuenta");
    $consulta->bindValue(':tipoCuenta', $tipoCuenta, PDO::PARAM_STR);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Retiro');
}
public static function listarPorMoneda($moneda)
{
    $objAccesoDatos = AccesoDatos::obtenerInstancia();

    $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM retiros WHERE moneda = :moneda");
    $consulta->bindValue(':moneda', $moneda, PDO::PARAM_STR);
    $consulta->execute();

    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Retiro');
}
}
?>