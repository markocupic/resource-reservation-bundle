<?php

declare(strict_types=1);

/**
 * Resource Booking Module for Contao CMS
 * Copyright (c) 2008-2020 Marko Cupic
 * @package resource-booking-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2020
 * @link https://github.com/markocupic/resource-booking-bundle
 */

namespace Markocupic\ResourceBookingBundle\Controller\Ajax;

use Contao\CoreBundle\Exception\RedirectResponseException;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\System;
use Markocupic\ResourceBookingBundle\Ajax\AjaxHandler;
use Markocupic\ResourceBookingBundle\Ajax\AjaxResponse;
use Markocupic\ResourceBookingBundle\AppInitialization\Initialize;
use Markocupic\ResourceBookingBundle\Csrf\CsrfTokenManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AjaxController
 * @package Markocupic\ResourceBookingBundle\Controller\Ajax
 */
class AjaxController extends AbstractController
{

    /** @var ContaoFramework */
    private $framework;

    /** @var SessionInterface */
    private $session;

    /** @var string */
    private $bagName;

    /** @var Initialize */
    private $appInitializer;

    /** @var AjaxHandler */
    private $ajaxHandler;

    /** @var CsrfTokenManager */
    private $csrfTokenManager;

    /**
     * AjaxController constructor.
     * @param SessionInterface $session
     * @param string $bagName
     * @param Initialize $appInitializer
     * @param AjaxHandler $ajaxHandler
     * @param CsrfTokenManager $csrfTokenManager
     */
    public function __construct(ContaoFramework $framework, SessionInterface $session, string $bagName, Initialize $appInitializer, AjaxHandler $ajaxHandler, CsrfTokenManager $csrfTokenManager)
    {
        $this->framework = $framework;
        $this->session = $session;
        $this->bagName = $bagName;
        $this->appInitializer = $appInitializer;
        $this->ajaxHandler = $ajaxHandler;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * xhttp logout route
     *
     * @Route("/_resource_booking/ajax/logout", name="resource_booking_ajax_logout_endpoint", condition="request.isXmlHttpRequest()", defaults={"_scope" = "frontend"})
     */
    public function logoutAction()
    {
        // Unset session
        $sessionBag = $this->session->getBag($this->bagName);
        $sessionBag->clear();

        // Logout user
        throw new RedirectResponseException(System::getContainer()->get('security.logout_url_generator')->getLogoutUrl());
    }

    /**
     * xhttp default route
     *
     * @param $action
     * @return JsonResponse
     * @throws \Exception
     * @Route("/_resource_booking/ajax/{action}", name="resource_booking_ajax_default_endpoint", condition="request.isXmlHttpRequest()", defaults={"_scope" = "frontend"})
     */
    public function defaultAjaxAction($action): JsonResponse
    {
        /** @var System $systemAdapter */
        $systemAdapter = $this->framework->getAdapter(System::class);

        // Handle ajax requests
        if ($this->csrfTokenManager->hasValidCsrfToken())
        {
            // Initialize application
            $this->appInitializer->initialize(true, null, null);

            if (is_callable([AjaxHandler::class, $action]))
            {
                /** @var AjaxResponse $xhrResponse */
                $xhrResponse = $this->ajaxHandler->{$action}();

                // HOOK: add custom logic
                if (isset($GLOBALS['TL_HOOKS']['resourceBookingAjaxResponse']) && \is_array($GLOBALS['TL_HOOKS']['resourceBookingAjaxResponse']))
                {
                    foreach ($GLOBALS['TL_HOOKS']['resourceBookingAjaxResponse'] as $callback)
                    {
                        /** @var AjaxResponse $xhrResponse */
                        $systemAdapter->importStatic($callback[0])->{$callback[1]}($action, $xhrResponse, $this);
                    }
                }

                return new JsonResponse($xhrResponse->getAll());
            }

            $xhrResponse = new AjaxResponse();
            $xhrResponse->setStatus(AjaxResponse::STATUS_ERROR);
            $xhrResponse->setErrorMessage(sprintf('Action "%s" not found.', $action));

            return new JsonResponse($xhrResponse->getAll());
        }

        $xhrResponse = new AjaxResponse();
        $xhrResponse->setStatus(AjaxResponse::STATUS_ERROR);
        $xhrResponse->setErrorMessage('No contao.csrf_token_name detected.');

        return new JsonResponse($xhrResponse->getAll());
    }

}
