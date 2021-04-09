<?php

namespace Delcesar\VisaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Delcesar\VisaBundle\Entity\Sesion;
use Delcesar\VisaBundle\Entity\Transaccion;

class ClienteController extends Controller
{

    // DESARROLLO
    private $merchantId = "000000000";
    private $AccessKeyId = "YYYYYYYYYYYYYYYYY";
    private $SecretAccessKey = "secretsecretsecretsecretsecretsecret";
    private $endPoint = "https://devapice.vnforapps.com/api.ecommerce/api/v1/ecommerce/token/";
    private $endPointAuth = "https://devapice.vnforapps.com/api.authorization/api/v1/authorization/web/";


    public function indexAction()
    {
        return $this->render('VisaBundle:ClienteEnglish:index.html.twig');
    }

    public function responseAction()
    {
        return $this->render('VisaBundle:ClienteEnglish:respuesta.html.twig');
    }

    public function previewReciboAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager('visa');
        $codigo = $request->query->get('codigo');

        // buscar en recibos no pagados
        $recibo = $em->getRepository("VisaBundle:Recibo")->findOneBy(array("codigo" => $codigo, "estadoregistro" => 1, "estadopago" => 0));
        $servicios = array();
        $pagado = false;

        // codigo de pago valido?
        if (!$recibo) {
            $recibopagado = $em->getRepository("VisaBundle:Recibo")->findOneBy(array("codigo" => $codigo, "estadoregistro" => 1, "estadopago" => 1));
            if ($recibopagado) {
                $pagado = true;
            }
            return $this->render('VisaBundle:ClienteSpanish:codigoDenegado.html.twig', array("codigo" => $codigo, "pagado" => $pagado));
        }

        $servicios = $em->getRepository("VisaBundle:Serviciorecibo")->findBy(array("idrecibo" => $recibo->getIdrecibo()));
        return $this->render('VisaBundle:ClienteSpanish:previewRecibo.html.twig', array("recibo" => $recibo, "servicios" => $servicios, "codigo" => $codigo));
    }

    // confirmar pagar con visa
    public function confirmarPagarAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager('visa');
        $idrecibo = $request->query->get('idrecibo');

        // buscar en recibos no pagados
        $recibo = $em->getRepository("VisaBundle:Recibo")->find($idrecibo);
        $servicios = array();

        if ($recibo && $recibo->getEstadoregistro() == 1 && $recibo->getEstadopago() == 0) {
            $servicios = $em->getRepository("VisaBundle:Serviciorecibo")->findBy(array("idrecibo" => $recibo->getIdrecibo()));

            // generar el token de sesión
            $sesion = new Sesion();
            $guid = $this->getGUID();
            $sesion->setSessiontoken($guid);
            $sesion->setIdrecibo($recibo);
            $hoy = new \Datetime();
            $sesion->setFecharegistro($hoy);
            $em->persist($sesion);

            $em->flush();

            //
            $montofinal = round($recibo->getMonto() * 1.06, 2);
            $tokenvisa = $this->create_token($montofinal, $guid);

            // guardar en una variable de sesión
            $request->getSession()->set('sessionToken', $guid);
            $request->getSession()->set('idrecibo', $idrecibo);

            return $this->render('VisaBundle:ClienteSpanish:confirmarPagar.html.twig', array("recibo" => $recibo, "servicios" => $servicios, "tokenvisa" => $tokenvisa, "sessiontoken" => $guid, "merchantid" => $this->merchantId, "monto" => $montofinal));
        } else {
            $request->getSession()->getFlashBag()->add(
                'alerta-sistema',
                'Revisar código de pago'
            );
            return $this->redirect($this->generateUrl('Delcesar_visa_cliente_pagos_inicio'));
        }
    }

    public function respuestaAutorizacionAction(Request $request)
    {
        $transactionToken = $request->request->get('transactionToken');
        $sessionToken = $request->getSession()->get('sessionToken');
        $idrecibo = $request->getSession()->get('idrecibo');
        $jsonAuthorization = $this->authorization($transactionToken, $sessionToken);
        $em = $this->getDoctrine()->getManager('visa');
        $recibo = $em->getRepository("VisaBundle:Recibo")->find($idrecibo);
        $servicios = $em->getRepository("VisaBundle:Serviciorecibo")->findBy(array("idrecibo" => $recibo->getIdrecibo()));

        // validar la transacción
        if (!empty($jsonAuthorization["data"])) {

            // registrar la transacción en el historial
            $transaccion = new Transaccion();
            $transaccion->setTransacciontoken($transactionToken);
            $session = $em->getRepository("VisaBundle:Sesion")->find($sessionToken);
            $transaccion->setSessiontoken($session);

            if ($jsonAuthorization["data"]["RESPUESTA"] == 1) {
                $transaccion->setExito(1);
            } else {
                $transaccion->setExito(0);
            }

            $transaccion->setErrorcode($jsonAuthorization["errorCode"]);
            $transaccion->setErrormessage($jsonAuthorization["errorMessage"]);
            $transaccion->setRespuesta($jsonAuthorization["data"]["RESPUESTA"]);
            $transaccion->setIdunico($jsonAuthorization["data"]["ID_UNICO"]);
            $transaccion->setNumorden($jsonAuthorization["data"]["NUMORDEN"]);
            $transaccion->setPan($jsonAuthorization["data"]["PAN"]);
            $transaccion->setOritarjeta($jsonAuthorization["data"]["ORI_TARJETA"]);
            $transaccion->setNomemisor($jsonAuthorization["data"]["NOM_EMISOR"]);
            $transaccion->setCodaccion($jsonAuthorization["data"]["CODACCION"]);
            $transaccion->setDsccodaccion($jsonAuthorization["data"]["DSC_COD_ACCION"]);
            $transaccion->setImpautorizado($jsonAuthorization["data"]["IMP_AUTORIZADO"]);
            $transaccion->setCodautoriza($jsonAuthorization["data"]["COD_AUTORIZA"]);

            $hoy = new \DateTime();
            if (!empty($jsonAuthorization["data"]["FECHAYHORA_TX"])) {
                $fechatx = \DateTime::createFromFormat('d/m/Y H:i', $jsonAuthorization["data"]["FECHAYHORA_TX"]);
                // fecha de la transaccion
                if (!$fechatx) {
                    $transaccion->setFechahoratx($hoy);
                } else {
                    $transaccion->setFechahoratx($fechatx);
                }
            } else {
                $transaccion->setFechahoratx($hoy);
            }
            $transaccion->setFecharegistro($hoy);
            $em->persist($transaccion);
            $em->flush();
        }

        // enviar email de confirmación si se ha autorizado la transacción
        if (!empty($jsonAuthorization["data"]) && $jsonAuthorization["data"]["RESPUESTA"] == 1) {

            // registrar el estado de pago en el recibo
            $recibo->setEstadopago(1);
            $recibo->setFechapago($hoy);
            $em->flush();

            // enviar email de confirmación
            $cuerpo_email = $this->renderView('VisaBundle:ClienteSpanish:emailConfirmacion.html.twig', array("transaccion" => $transaccion, "recibo" => $recibo, "servicios" => $servicios));
            $serv_email = $this->get('email');
            $copia = array("email@dominio.com", "email20@domain.com");
            $serv_email->enviar_email($recibo->getIdcliente()->getNombre(), $recibo->getIdcliente()->getEmail(), "Confirmación de Pago " . $recibo->getCodigo(), $cuerpo_email, "Company", "online@domain.com", $copia);
        }

        return $this->render('VisaBundle:ClienteSpanish:respuestaAutorizacion.html.twig', array("respuesta" => $jsonAuthorization, "post" => $request->request->all(), "recibo" => $recibo, "servicios" => $servicios, "codigocomercio" => $this->merchantId));
    }

    /**
     * crear el GUID único
     */
    private function getGUID()
    {
        if (function_exists('com_create_guid')) {
            return com_create_guid();
        } else {
            mt_srand((float) microtime() * 10000);
            $charid = strtoupper(md5(uniqid(rand(), true)));
            $hyphen = chr(45); // "-"
            $uuid = chr(123) // "{"
                . substr($charid, 0, 8) . $hyphen
                . substr($charid, 8, 4) . $hyphen
                . substr($charid, 12, 4) . $hyphen
                . substr($charid, 16, 4) . $hyphen
                . substr($charid, 20, 12) . $hyphen
                . chr(125); // "}"
            $uuid = substr($uuid, 1, 36);
            return $uuid;
        }
    }

    // crear token en API Visa
    private function create_token($amount, $guid)
    {
        $url = $this->endPoint . $this->merchantId;
        $header = array("Content-Type: application/json", "VisaNet-Session-Key: $guid");
        $request_body = "{
            \"amount\":{$amount}
        }";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->AccessKeyId:$this->SecretAccessKey");
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);

        $json = json_decode($response);
        $token = "";

        if (!empty($json)) {
            $token = $json->sessionKey;
        }
        return $token;
    }


    /**
     * resultado de la autorización de una transacción
     */
    private function authorization($transactionToken, $sessionToken)
    {
        $url = $this->endPointAuth . $this->merchantId;
        $header = array("Content-Type: application/json", "VisaNet-Session-Key: $sessionToken");
        $request_body = "{
            \"transactionToken\":\"$transactionToken\",
            \"sessionToken\":\"$sessionToken\"
        }";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$this->AccessKeyId:$this->SecretAccessKey");
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($ch);
        $json = json_decode($response, true);

        return $json;
    }
}
