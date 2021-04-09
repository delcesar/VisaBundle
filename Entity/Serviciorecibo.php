<?php

namespace Delcesar\VisaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Serviciorecibo
 */
class Serviciorecibo
{
    /**
     * @var integer
     */
    private $idserviciorecibo;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $url;

    /**
     * @var \DateTime
     */
    private $fechainicio;

    /**
     * @var \Delcesar\VisaBundle\Entity\Recibo
     */
    private $idrecibo;


    /**
     * Get idserviciorecibo
     *
     * @return integer 
     */
    public function getIdserviciorecibo()
    {
        return $this->idserviciorecibo;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Serviciorecibo
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Serviciorecibo
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set fechainicio
     *
     * @param \DateTime $fechainicio
     * @return Serviciorecibo
     */
    public function setFechainicio($fechainicio)
    {
        $this->fechainicio = $fechainicio;

        return $this;
    }

    /**
     * Get fechainicio
     *
     * @return \DateTime 
     */
    public function getFechainicio()
    {
        return $this->fechainicio;
    }

    /**
     * Set idrecibo
     *
     * @param \Delcesar\VisaBundle\Entity\Recibo $idrecibo
     * @return Serviciorecibo
     */
    public function setIdrecibo(\Delcesar\VisaBundle\Entity\Recibo $idrecibo = null)
    {
        $this->idrecibo = $idrecibo;

        return $this;
    }

    /**
     * Get idrecibo
     *
     * @return \Delcesar\VisaBundle\Entity\Recibo 
     */
    public function getIdrecibo()
    {
        return $this->idrecibo;
    }
}
