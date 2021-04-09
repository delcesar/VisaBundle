<?php

namespace Delcesar\VisaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Recibo
 */
class Recibo
{
    /**
     * @var integer
     */
    private $idrecibo;

    /**
     * @var string
     */
    private $codigo;

    /**
     * @var string
     */
    private $monto;

    /**
     * @var \DateTime
     */
    private $fechalimitepago;

    /**
     * @var \DateTime
     */
    private $fecharegistro;

    /**
     * @var integer
     */
    private $estadoregistro;

    /**
     * @var integer
     */
    private $estadopago;

    /**
     * @var \DateTime
     */
    private $fechapago;

    /**
     * @var \Delcesar\VisaBundle\Entity\Cliente
     */
    private $idcliente;

    /**
     * @var \Delcesar\VisaBundle\Entity\Usuario
     */
    private $idusuarioregistro;


    /**
     * Get idrecibo
     *
     * @return integer 
     */
    public function getIdrecibo()
    {
        return $this->idrecibo;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     * @return Recibo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string 
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * Set monto
     *
     * @param string $monto
     * @return Recibo
     */
    public function setMonto($monto)
    {
        $this->monto = $monto;

        return $this;
    }

    /**
     * Get monto
     *
     * @return string 
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * Set fechalimitepago
     *
     * @param \DateTime $fechalimitepago
     * @return Recibo
     */
    public function setFechalimitepago($fechalimitepago)
    {
        $this->fechalimitepago = $fechalimitepago;

        return $this;
    }

    /**
     * Get fechalimitepago
     *
     * @return \DateTime 
     */
    public function getFechalimitepago()
    {
        return $this->fechalimitepago;
    }

    /**
     * Set fecharegistro
     *
     * @param \DateTime $fecharegistro
     * @return Recibo
     */
    public function setFecharegistro($fecharegistro)
    {
        $this->fecharegistro = $fecharegistro;

        return $this;
    }

    /**
     * Get fecharegistro
     *
     * @return \DateTime 
     */
    public function getFecharegistro()
    {
        return $this->fecharegistro;
    }

    /**
     * Set estadoregistro
     *
     * @param integer $estadoregistro
     * @return Recibo
     */
    public function setEstadoregistro($estadoregistro)
    {
        $this->estadoregistro = $estadoregistro;

        return $this;
    }

    /**
     * Get estadoregistro
     *
     * @return integer 
     */
    public function getEstadoregistro()
    {
        return $this->estadoregistro;
    }

    /**
     * Set estadopago
     *
     * @param integer $estadopago
     * @return Recibo
     */
    public function setEstadopago($estadopago)
    {
        $this->estadopago = $estadopago;

        return $this;
    }

    /**
     * Get estadopago
     *
     * @return integer 
     */
    public function getEstadopago()
    {
        return $this->estadopago;
    }

    /**
     * Set fechapago
     *
     * @param \DateTime $fechapago
     * @return Recibo
     */
    public function setFechapago($fechapago)
    {
        $this->fechapago = $fechapago;

        return $this;
    }

    /**
     * Get fechapago
     *
     * @return \DateTime 
     */
    public function getFechapago()
    {
        return $this->fechapago;
    }

    /**
     * Set idcliente
     *
     * @param \Delcesar\VisaBundle\Entity\Cliente $idcliente
     * @return Recibo
     */
    public function setIdcliente(\Delcesar\VisaBundle\Entity\Cliente $idcliente = null)
    {
        $this->idcliente = $idcliente;

        return $this;
    }

    /**
     * Get idcliente
     *
     * @return \Delcesar\VisaBundle\Entity\Cliente 
     */
    public function getIdcliente()
    {
        return $this->idcliente;
    }

    /**
     * Set idusuarioregistro
     *
     * @param \Delcesar\VisaBundle\Entity\Usuario $idusuarioregistro
     * @return Recibo
     */
    public function setIdusuarioregistro(\Delcesar\VisaBundle\Entity\Usuario $idusuarioregistro = null)
    {
        $this->idusuarioregistro = $idusuarioregistro;

        return $this;
    }

    /**
     * Get idusuarioregistro
     *
     * @return \Delcesar\VisaBundle\Entity\Usuario 
     */
    public function getIdusuarioregistro()
    {
        return $this->idusuarioregistro;
    }
}
