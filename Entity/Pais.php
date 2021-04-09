<?php

namespace Delcesar\VisaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pais
 */
class Pais
{
    /**
     * @var string
     */
    private $idpais;

    /**
     * @var string
     */
    private $nombre;


    /**
     * Get idpais
     *
     * @return string 
     */
    public function getIdpais()
    {
        return $this->idpais;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Pais
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
}
