<?php

namespace Delcesar\VisaBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class guardianvisa
{

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /* verifica que el usuario sigue logeado en el sistema */

    public function logeado()
    {
        $esta = false;
        if ($this->session->has('idusuariovisa')) {
            $esta = true;
        }
        return $esta;
    }

    public function validarAcceso()
    {
        $respuesta = false;
        if ($this->logeado()) {
            $respuesta = true;
        } else {
            $this->session->getFlashBag()->add(
                'alerta-sistema',
                'No has iniciado sesión en el sistema'
            );
        }
        return $respuesta;
    }
}
