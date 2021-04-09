<?php

namespace Delcesar\VisaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaccion
 */
class Transaccion
{
    /**
     * @var integer
     */
    private $idtransaccion;

    /**
     * @var string
     */
    private $transacciontoken;

    /**
     * @var boolean
     */
    private $exito;

    /**
     * @var string
     */
    private $errorcode;

    /**
     * @var string
     */
    private $errormessage;

    /**
     * @var integer
     */
    private $respuesta;

    /**
     * @var string
     */
    private $idunico;

    /**
     * @var integer
     */
    private $numorden;

    /**
     * @var string
     */
    private $pan;

    /**
     * @var string
     */
    private $oritarjeta;

    /**
     * @var string
     */
    private $nomemisor;

    /**
     * @var string
     */
    private $codaccion;

    /**
     * @var string
     */
    private $dsccodaccion;

    /**
     * @var string
     */
    private $impautorizado;

    /**
     * @var string
     */
    private $codautoriza;

    /**
     * @var \DateTime
     */
    private $fechahoratx;

    /**
     * @var \DateTime
     */
    private $fecharegistro;

    /**
     * @var \Delcesar\VisaBundle\Entity\Sesion
     */
    private $sessiontoken;


    /**
     * Get idtransaccion
     *
     * @return integer 
     */
    public function getIdtransaccion()
    {
        return $this->idtransaccion;
    }

    /**
     * Set transacciontoken
     *
     * @param string $transacciontoken
     * @return Transaccion
     */
    public function setTransacciontoken($transacciontoken)
    {
        $this->transacciontoken = $transacciontoken;

        return $this;
    }

    /**
     * Get transacciontoken
     *
     * @return string 
     */
    public function getTransacciontoken()
    {
        return $this->transacciontoken;
    }

    /**
     * Set exito
     *
     * @param boolean $exito
     * @return Transaccion
     */
    public function setExito($exito)
    {
        $this->exito = $exito;

        return $this;
    }

    /**
     * Get exito
     *
     * @return boolean 
     */
    public function getExito()
    {
        return $this->exito;
    }

    /**
     * Set errorcode
     *
     * @param string $errorcode
     * @return Transaccion
     */
    public function setErrorcode($errorcode)
    {
        $this->errorcode = $errorcode;

        return $this;
    }

    /**
     * Get errorcode
     *
     * @return string 
     */
    public function getErrorcode()
    {
        return $this->errorcode;
    }

    /**
     * Set errormessage
     *
     * @param string $errormessage
     * @return Transaccion
     */
    public function setErrormessage($errormessage)
    {
        $this->errormessage = $errormessage;

        return $this;
    }

    /**
     * Get errormessage
     *
     * @return string 
     */
    public function getErrormessage()
    {
        return $this->errormessage;
    }

    /**
     * Set respuesta
     *
     * @param integer $respuesta
     * @return Transaccion
     */
    public function setRespuesta($respuesta)
    {
        $this->respuesta = $respuesta;

        return $this;
    }

    /**
     * Get respuesta
     *
     * @return integer 
     */
    public function getRespuesta()
    {
        return $this->respuesta;
    }

    /**
     * Set idunico
     *
     * @param string $idunico
     * @return Transaccion
     */
    public function setIdunico($idunico)
    {
        $this->idunico = $idunico;

        return $this;
    }

    /**
     * Get idunico
     *
     * @return string 
     */
    public function getIdunico()
    {
        return $this->idunico;
    }

    /**
     * Set numorden
     *
     * @param integer $numorden
     * @return Transaccion
     */
    public function setNumorden($numorden)
    {
        $this->numorden = $numorden;

        return $this;
    }

    /**
     * Get numorden
     *
     * @return integer 
     */
    public function getNumorden()
    {
        return $this->numorden;
    }

    /**
     * Set pan
     *
     * @param string $pan
     * @return Transaccion
     */
    public function setPan($pan)
    {
        $this->pan = $pan;

        return $this;
    }

    /**
     * Get pan
     *
     * @return string 
     */
    public function getPan()
    {
        return $this->pan;
    }

    /**
     * Set oritarjeta
     *
     * @param string $oritarjeta
     * @return Transaccion
     */
    public function setOritarjeta($oritarjeta)
    {
        $this->oritarjeta = $oritarjeta;

        return $this;
    }

    /**
     * Get oritarjeta
     *
     * @return string 
     */
    public function getOritarjeta()
    {
        return $this->oritarjeta;
    }

    /**
     * Set nomemisor
     *
     * @param string $nomemisor
     * @return Transaccion
     */
    public function setNomemisor($nomemisor)
    {
        $this->nomemisor = $nomemisor;

        return $this;
    }

    /**
     * Get nomemisor
     *
     * @return string 
     */
    public function getNomemisor()
    {
        return $this->nomemisor;
    }

    /**
     * Set codaccion
     *
     * @param string $codaccion
     * @return Transaccion
     */
    public function setCodaccion($codaccion)
    {
        $this->codaccion = $codaccion;

        return $this;
    }

    /**
     * Get codaccion
     *
     * @return string 
     */
    public function getCodaccion()
    {
        return $this->codaccion;
    }

    /**
     * Set dsccodaccion
     *
     * @param string $dsccodaccion
     * @return Transaccion
     */
    public function setDsccodaccion($dsccodaccion)
    {
        $this->dsccodaccion = $dsccodaccion;

        return $this;
    }

    /**
     * Get dsccodaccion
     *
     * @return string 
     */
    public function getDsccodaccion()
    {
        return $this->dsccodaccion;
    }

    /**
     * Set impautorizado
     *
     * @param string $impautorizado
     * @return Transaccion
     */
    public function setImpautorizado($impautorizado)
    {
        $this->impautorizado = $impautorizado;

        return $this;
    }

    /**
     * Get impautorizado
     *
     * @return string 
     */
    public function getImpautorizado()
    {
        return $this->impautorizado;
    }

    /**
     * Set codautoriza
     *
     * @param string $codautoriza
     * @return Transaccion
     */
    public function setCodautoriza($codautoriza)
    {
        $this->codautoriza = $codautoriza;

        return $this;
    }

    /**
     * Get codautoriza
     *
     * @return string 
     */
    public function getCodautoriza()
    {
        return $this->codautoriza;
    }

    /**
     * Set fechahoratx
     *
     * @param \DateTime $fechahoratx
     * @return Transaccion
     */
    public function setFechahoratx($fechahoratx)
    {
        $this->fechahoratx = $fechahoratx;

        return $this;
    }

    /**
     * Get fechahoratx
     *
     * @return \DateTime 
     */
    public function getFechahoratx()
    {
        return $this->fechahoratx;
    }

    /**
     * Set fecharegistro
     *
     * @param \DateTime $fecharegistro
     * @return Transaccion
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
     * Set sessiontoken
     *
     * @param \Delcesar\VisaBundle\Entity\Sesion $sessiontoken
     * @return Transaccion
     */
    public function setSessiontoken(\Delcesar\VisaBundle\Entity\Sesion $sessiontoken = null)
    {
        $this->sessiontoken = $sessiontoken;

        return $this;
    }

    /**
     * Get sessiontoken
     *
     * @return \Delcesar\VisaBundle\Entity\Sesion 
     */
    public function getSessiontoken()
    {
        return $this->sessiontoken;
    }
}
