<?php

namespace Delcesar\VisaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cliente
 */
class Cliente
{
    /**
     * @var integer
     */
    private $idcliente;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $idioma;

    /**
     * @var \Delcesar\VisaBundle\Entity\Pais
     */
    private $nacionalidad;


    /**
     * Get idcliente
     *
     * @return integer 
     */
    public function getIdcliente()
    {
        return $this->idcliente;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Cliente
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
     * Set email
     *
     * @param string $email
     * @return Cliente
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set idioma
     *
     * @param string $idioma
     * @return Cliente
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;

        return $this;
    }

    /**
     * Get idioma
     *
     * @return string 
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set nacionalidad
     *
     * @param \Delcesar\VisaBundle\Entity\Pais $nacionalidad
     * @return Cliente
     */
    public function setNacionalidad(\Delcesar\VisaBundle\Entity\Pais $nacionalidad = null)
    {
        $this->nacionalidad = $nacionalidad;

        return $this;
    }

    /**
     * Get nacionalidad
     *
     * @return \Delcesar\VisaBundle\Entity\Pais 
     */
    public function getNacionalidad()
    {
        return $this->nacionalidad;
    }
}
