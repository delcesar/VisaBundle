<?php

namespace Delcesar\VisaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Delcesar\VisaBundle\Entity\Cliente;
use Delcesar\VisaBundle\Entity\Recibo;
use Delcesar\VisaBundle\Entity\Serviciorecibo;

class AdminController extends Controller
{

    /**
     * Mostrar el estado de todos los recibos creados
     */
    public function indexAction()
    {
        // validar sesion
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            return $this->redirect($this->generateUrl('delcesar_visa_logout'));
        }

        // recuperar recibos
        $em = $this->getDoctrine()->getManager('visa');
        $query = $em->createQuery('SELECT r FROM DelcesarVisaBundle:Recibo r ORDER BY r.fecharegistro DESC')
            ->setMaxResults(25);
        $recibos = $query->getResult();

        // recuperar los servicios de cada recibo
        $servicios = array();
        foreach ($recibos as $recibo) {
            $servicios[$recibo->getIdrecibo()] = $em->getRepository("DelcesarVisaBundle:Serviciorecibo")->findBy(array("idrecibo" => $recibo->getIdrecibo()));
        }

        return $this->render('DelcesarVisaBundle:Admin:index.html.twig', array("recibos" => $recibos, "servicios" => $servicios));
    }

    /**
     * Detalles de un recibo
     */
    public function detallesReciboAction(Request $request)
    {
        // validar acceso
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            return $this->redirect($this->generateUrl('delcesar_visa_logout'));
        }

        // datos del recibo
        $idrecibo = $request->query->get('idrecibo');
        $em = $this->getDoctrine()->getManager('visa');
        $recibo = $em->getRepository("DelcesarVisaBundle:Recibo")->find($idrecibo);

        if ($recibo) {
            $servicios = $em->getRepository("DelcesarVisaBundle:Serviciorecibo")->findBy(array("idrecibo" => $idrecibo));
            return $this->render('DelcesarVisaBundle:Admin:detallesRecibo.html.twig', array("recibo" => $recibo, "servicios" => $servicios));
        } else {
            //recibo no encontrado
            $request->getSession()->getFlashBag()->add(
                'alerta-sistema',
                'No se encuentra el recibo con ID ' . $idrecibo
            );

            return $this->redirect($this->generateUrl('delcesar_visa_admin_homepage'));
        }
    }

    /**
     * generar codigo de pago
     */
    public function generarCodigoAction()
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            return $this->redirect($this->generateUrl('delcesar_visa_logout'));
        }

        $em = $this->getDoctrine()->getManager('visa');
        $paises = $em->getRepository('DelcesarVisaBundle:Pais')->findBy(array(), array("nombre" => "ASC"));
        return $this->render('DelcesarVisaBundle:Admin:generarCodigo.html.twig', array("paises" => $paises));
    }

    /**
     * modal codigo generado
     */
    public function crearCodigoAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            $ret["sesion"] = false;
            $ret["redirect"] = $this->generateUrl('delcesar_visa_logout');
        } else {
            $ret["sesion"] = true;
            //
            $nombre = $request->request->get('nombre');
            $email = $request->request->get('email');
            $nacionalidad = $request->request->get("nacionalidad");
            $idioma = $request->request->get("idioma");
            $monto = $request->request->get('monto');
            $fechalimite = $request->request->get('fecha-limite');
            $servicios = json_decode($request->request->get('jsonservicios'), true);

            // validar los campos recibidos
            $continuar = true;
            $ret["error"] = "Revisa los campos: ";
            if ($nombre == "") {
                $continuar = false;
                $ret["error"] .= "Nombre del cliente, ";
                $ret["invalido"][] = "nombre";
            }
            if ($email == "") {
                $continuar = false;
                $ret["error"] .= "Email del cliente, ";
                $ret["invalido"][] = "email";
            }
            if (!is_numeric($monto)) {
                $continuar = false;
                $ret["error"] .= "Monto del recibo, ";
                $ret["invalido"][] = "monto";
            }
            if (count($servicios) == 0) {
                $continuar = false;
                $ret["error"] .= "Servicios del recibo, ";
                $ret["invalido"][] = "nombre-servicio";
            }
            if ($fechalimite == "") {
                $continuar = false;
                $ret["error"] .= "Fecha límite de pago";
                $ret["invalido"][] = "fecha-limite";
            }

            // todos los campos validos
            if ($continuar) {
                $em = $this->getDoctrine()->getManager('visa');
                $hoy = new \Datetime();
                $cliente = new Cliente();
                $cliente->setNombre($nombre);
                $cliente->setEmail($email);
                $pais = $em->getRepository('DelcesarVisaBundle:Pais')->find($nacionalidad);
                $cliente->setNacionalidad($pais);
                $cliente->setIdioma($idioma);
                $em->persist($cliente);

                // crear recibo
                $recibo = new Recibo();
                $recibo->setIdcliente($cliente);
                $alfanumerico = '123456789ABCDEFGHIJKLMNPQRSTUVWXYZ';
                $codigoreserva = "";
                for ($i = 0; $i < 6; $i++) {
                    $codigoreserva .= $alfanumerico[mt_rand(0, 33)];
                }

                $recibo->setCodigo($codigoreserva);
                $recibo->setMonto($monto);
                $deadline = \DateTime::createFromFormat('Y-m-d', $fechalimite);
                $deadline->setTime(23, 59);
                $recibo->setFechalimitepago($deadline);
                $recibo->setFecharegistro($hoy);
                $usuario = $em->getRepository('DelcesarVisaBundle:Usuario')->find($request->getSession()->get('idusuariovisa'));
                $recibo->setIdusuarioregistro($usuario);
                $recibo->setEstadopago(0);
                $recibo->setEstadoregistro(1);
                $em->persist($recibo);

                // registrar servicios
                foreach ($servicios as $servicio) {
                    $servrecibo = new Serviciorecibo();
                    $servrecibo->setIdrecibo($recibo);
                    $servrecibo->setNombre($servicio["nombre"]);
                    $em->persist($servrecibo);
                }

                $em->flush();

                $ret["html"] = $this->renderView('DelcesarVisaBundle:Admin:modalCodigoGenerado.html.twig', array("codigo" => $codigoreserva));
                $ret["valido"] = true;
            } else {
                $ret["valido"] = false;
            }
        }

        return new JsonResponse($ret);
    }

    /**
     * editar datos de un recibo
     */
    public function editarReciboAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            return $this->redirect($this->generateUrl('delcesar_visa_logout'));
        }

        $idrecibo = $request->query->get('idrecibo');
        $em = $this->getDoctrine()->getManager('visa');
        $recibo = $em->getRepository("DelcesarVisaBundle:Recibo")->find($idrecibo);

        // recibo en estado pendiente?
        if ($recibo && $recibo->getEstadopago() == 0 && $recibo->getEstadoregistro() == 1) {
            $servicios = $em->getRepository("DelcesarVisaBundle:Serviciorecibo")->findBy(array("idrecibo" => $idrecibo));
            $paises = $em->getRepository('DelcesarVisaBundle:Pais')->findBy(array(), array("nombre" => "ASC"));
            return $this->render('DelcesarVisaBundle:Admin:editarRecibo.html.twig', array("recibo" => $recibo, "servicios" => $servicios, "paises" => $paises));
        } else {
            // recibo no encontrado
            $request->getSession()->getFlashBag()->add(
                'alerta-sistema',
                'El recibo con ID ' . $idrecibo . ' ya no se puede editar'
            );

            return $this->redirect($this->generateUrl('delcesar_visa_admin_homepage'));
        }
    }

    /**
     * actualizar datos de un recibo
     */
    public function actualizarReciboAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            $ret["sesion"] = false;
            $ret["redirect"] = $this->generateUrl('delcesar_visa_logout');
        } else {
            $ret["sesion"] = true;

            $idrecibo = $request->request->get('idrecibo');
            $nombre = $request->request->get('nombre');
            $email = $request->request->get('email');
            $nacionalidad = $request->request->get("nacionalidad");
            $idioma = $request->request->get("idioma");
            $monto = $request->request->get('monto');
            $fechalimite = $request->request->get('fecha-limite');
            $servicios = json_decode($request->request->get('jsonservicios'), true);

            // verificar que el recibo se puede aun editar
            $em = $this->getDoctrine()->getManager('visa');
            $recibo = $em->getRepository("DelcesarVisaBundle:Recibo")->find($idrecibo);

            if ($recibo && $recibo->getEstadopago() == 0 && $recibo->getEstadoregistro() == 1) {

                // validar los campos recibidos
                $continuar = true;
                $ret["error"] = "Revisa los campos: ";
                if ($nombre == "") {
                    $continuar = false;
                    $ret["error"] .= "Nombre del cliente, ";
                    $ret["invalido"][] = "nombre";
                }
                if ($email == "") {
                    $continuar = false;
                    $ret["error"] .= "Email del cliente, ";
                    $ret["invalido"][] = "email";
                }
                if (!is_numeric($monto)) {
                    $continuar = false;
                    $ret["error"] .= "Monto del recibo, ";
                    $ret["invalido"][] = "monto";
                }
                if (count($servicios) == 0) {
                    $continuar = false;
                    $ret["error"] .= "Servicios del recibo, ";
                    $ret["invalido"][] = "nombre-servicio";
                }
                if ($fechalimite == "") {
                    $continuar = false;
                    $ret["error"] .= "Fecha límite de pago";
                    $ret["invalido"][] = "fecha-limite";
                }

                // campos validos
                if ($continuar) {
                    $hoy = new \Datetime();
                    $cliente = $recibo->getIdcliente();
                    $cliente->setNombre($nombre);
                    $cliente->setEmail($email);
                    $pais = $em->getRepository('DelcesarVisaBundle:Pais')->find($nacionalidad);
                    $cliente->setNacionalidad($pais);
                    $cliente->setIdioma($idioma);
                    $recibo->setMonto($monto);
                    $deadline = \DateTime::createFromFormat('Y-m-d', $fechalimite);
                    $deadline->setTime(23, 59);
                    $recibo->setFechalimitepago($deadline);
                    $recibo->setFecharegistro($hoy);
                    $usuario = $em->getRepository('DelcesarVisaBundle:Usuario')->find($request->getSession()->get('idusuariovisa'));
                    $recibo->setIdusuarioregistro($usuario);

                    // crear nuevo registro para los servicios del recibo
                    $q = $em->createQuery('DELETE FROM DelcesarVisaBundle:Serviciorecibo s WHERE s.idrecibo = :idrecibo')
                        ->setParameter('idrecibo', $recibo);
                    $q->execute();
                    foreach ($servicios as $servicio) {
                        $servrecibo = new Serviciorecibo();
                        $servrecibo->setIdrecibo($recibo);
                        $servrecibo->setNombre($servicio["nombre"]);
                        $em->persist($servrecibo);
                    }

                    $em->flush();

                    $ret["valido"] = true;
                } else {
                    $ret["valido"] = false;
                }
            } else {
                $ret["sesion"] = false;
                $ret["redirect"] = $this->generateUrl('delcesar_visa_logout');
            }
        }

        return new JsonResponse($ret);
    }

    /**
     * cancelar un recibo
     */
    public function cancelarReciboAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            $ret["sesion"] = false;
            $ret["redirect"] = $this->generateUrl('delcesar_visa_logout');
        } else {
            $ret["sesion"] = true;

            $idrecibo = $request->query->get('idrecibo');

            // verificar que el recibo se puede aun editar
            $em = $this->getDoctrine()->getManager('visa');
            $recibo = $em->getRepository("DelcesarVisaBundle:Recibo")->find($idrecibo);

            // recibo en estado pendiente?
            if ($recibo && $recibo->getEstadopago() == 0 && $recibo->getEstadoregistro() == 1) {
                $recibo->setEstadoregistro(0);
                $em->flush();
                $ret["valido"] = true;
            } else {
                $ret["valido"] = false;
            }
        }

        return new JsonResponse($ret);
    }

    /**
     * buscar codigo de pago
     */
    public function buscarReciboAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            return $this->redirect($this->generateUrl('delcesar_visa_logout'));
        }

        return $this->render('DelcesarVisaBundle:Admin:buscarCodigo.html.twig');
    }

    /**
     * tabla de resultados  de búsqueda
     */
    public function resultadosBusquedaReciboAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            $ret["sesion"] = false;
            $ret["redirect"] = $this->generateUrl('delcesar_visa_logout');
        } else {
            $ret["sesion"] = true;

            $keyword = $request->request->get('keyword');

            // buscar el recibo
            $em = $this->getDoctrine()->getManager('visa');
            $recibos = $em->createQuery('SELECT r FROM DelcesarVisaBundle:Recibo r LEFT JOIN r.idcliente c WHERE r.codigo = :codigo OR c.nombre LIKE :nombre ORDER BY r.fecharegistro DESC')
                ->setParameter("codigo", $keyword)
                ->setParameter("nombre", "%" . $keyword . "%")
                ->getResult();

            // los servicios de cada recibo
            $servicios = array();
            foreach ($recibos as $recibo) {
                $servicios[$recibo->getIdrecibo()] = $em->getRepository("DelcesarVisaBundle:Serviciorecibo")->findBy(array("idrecibo" => $recibo->getIdrecibo()));
            }

            //
            $ret["html"] = $this->renderView('DelcesarVisaBundle:Admin:resultadosBusqueda.html.twig', array("recibos" => $recibos, "servicios" => $servicios, "keyword" => $keyword));
            $ret["valido"] = true;
        }

        return new JsonResponse($ret);
    }

    /**
     * buscar transacciones
     *  */
    public function buscarTransaccionesAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            return $this->redirect($this->generateUrl('delcesar_visa_logout'));
        }

        return $this->render('DelcesarVisaBundle:Admin:buscarTransacciones.html.twig');
    }

    // resultados busqueda de transacciones
    public function resultadosTransaccionesPeriodoAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            $ret["sesion"] = false;
            $ret["redirect"] = $this->generateUrl('delcesar_visa_logout');
        } else {
            $ret["sesion"] = true;

            // buscar el recibo
            $desde = $request->request->get('desde');
            $hasta = $request->request->get('hasta');
            $em = $this->getDoctrine()->getManager('visa');
            $transacciones = $em->createQuery('SELECT t FROM DelcesarVisaBundle:Transaccion t WHERE t.fecharegistro >= :desde AND t.fecharegistro <= :hasta ORDER BY t.fechahoratx DESC')
                ->setParameter("desde", $desde . " 00:00:00")
                ->setParameter("hasta", $hasta . " 23:59:59")
                ->getResult();

            $ret["html"] = $this->renderView('DelcesarVisaBundle:Admin:transaccionesPeriodo.html.twig', array("transacciones" => $transacciones, "desde" => $desde, "hasta" => $hasta));
            $ret["valido"] = true;

            return new JsonResponse($ret);
        }
    }

    /**
     * Detalles de la transaccion
     * */
    public function detallesTransaccionAction(Request $request)
    {
        $guardian = $this->get('guardianvisa')->validarAcceso();
        if (!$guardian) {
            return $this->redirect($this->generateUrl('delcesar_visa_logout'));
        }

        $idtransaccion = $request->query->get('idtransaccion');
        $em = $this->getDoctrine()->getManager('visa');
        $transaccion = $em->getRepository("DelcesarVisaBundle:Transaccion")->find($idtransaccion);

        if ($transaccion) {
            return $this->render('DelcesarVisaBundle:Admin:detallesTransaccion.html.twig', array("transaccion" => $transaccion));
        }

        $request->getSession()->getFlashBag()->add(
            'alerta-sistema',
            'No se encuentra la transacción con ID ' . $idtransaccion
        );
        return $this->redirect($this->generateUrl('delcesar_visa_admin_buscar_transacciones'));
    }
}
