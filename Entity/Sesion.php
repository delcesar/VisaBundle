<?php

namespace Delcesar\VisaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sesion
 */
class Sesion
{

    /**
     * @var string
     */
    private $sessiontoken;

    /**
     * @var \DateTime
     */
    private $fecharegistro;

    /**
     * @var \Delcesar\VisaBundle\Entity\Recibo
     */
    private $idrecibo;

    /**
     * Set sessiontoken
     *
     * @param string $sessiontoken
     * @return Sesion
     */
    public function setSessiontoken($sessiontoken)
    {
        $this->sessiontoken = $sessiontoken;

        return $this;
    }

    /**
     * Get sessiontoken
     *
     * @return string
     */
    public function getSessiontoken()
    {
        return $this->sessiontoken;
    }

    /**
     * Set fecharegistro
     *
     * @param \DateTime $fecharegistro
     * @return Sesion
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
     * Set idrecibo
     *
     * @param \Delcesar\VisaBundle\Entity\Recibo $idrecibo
     * @return Sesion
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
