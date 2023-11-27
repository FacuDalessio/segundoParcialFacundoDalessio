<?php
    class Ajuste implements JsonSerializable{
        private $id;
        private $motivo;
        private $nombreTransaccion;
        private $transaccion;

        public function jsonSerialize() {
            return [
                'id' => $this->id,
                'motivo' => $this->motivo,
                'nombreTransaccion' => $this->nombreTransaccion,
                'transaccion' => $this->transaccion
            ];
        }

        public function getId()
    {
        return $this->id;
    }

    public function getMotivo()
    {
        return $this->motivo;
    }

    public function getNombreTransaccion()
    {
        return $this->nombreTransaccion;
    }

    public function getTransaccion()
    {
        return $this->transaccion;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setMotivo($motivo)
    {
        $this->motivo = $motivo;
    }

    public function setNombreTransaccion($nombreTransaccion)
    {
        $this->nombreTransaccion = $nombreTransaccion;
    }

    public function setTransaccion($transaccion)
    {
        $this->transaccion = $transaccion;
    }

    public function crearAjuste() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ajustes (motivo, nombreTransaccion, transaccion)
         VALUES (:motivo, :nombreTransaccion, :transaccion)");
        $consulta->bindValue(':motivo', $this->motivo, PDO::PARAM_STR);
        $consulta->bindValue(':nombreTransaccion', $this->nombreTransaccion, PDO::PARAM_STR);
        $consulta->bindValue(':transaccion', $this->transaccion, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodosAjustes() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ajustes");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Ajuste');
    }

    public static function buscarUnoAjuste($idAjuste) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("SELECT * FROM ajustes WHERE id = :id");
        $consulta->bindValue(':id', $idAjuste, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Ajuste');
    }
}
?>