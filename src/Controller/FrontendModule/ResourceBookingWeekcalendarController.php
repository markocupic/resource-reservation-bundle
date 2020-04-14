<?php

declare(strict_types=1);

/**
 * Resource Booking Module for Contao CMS
 * Copyright (c) 2008-2019 Marko Cupic
 * @package resource-booking-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2019
 * @link https://github.com/markocupic/resource-booking-bundle
 */

namespace Markocupic\ResourceBookingBundle\Controller\FrontendModule;

use Contao\Controller;
use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Environment;
use Contao\FrontendUser;
use Contao\ModuleModel;
use Contao\PageModel;
use Contao\Template;
use Markocupic\ResourceBookingBundle\Runtime\Runtime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Contao\CoreBundle\ServiceAnnotation\FrontendModule;

/**
 * Class ResourceBookingWeekcalendarController
 * @package Markocupic\ResourceBookingBundle\Controller\FrontendModule
 */
class ResourceBookingWeekcalendarController extends AbstractFrontendModuleController
{
    /** @var ContaoFramework */
    private $framework;

    /** @var Security */
    private $security;

    /** @var Runtime */
    private $runtime;

    /**
     * ResourceBookingWeekcalendarController constructor.
     * @param ContaoFramework $framework
     * @param Security $security
     * @param Runtime $runtime
     */
    public function __construct(ContaoFramework $framework, Security $security, Runtime $runtime)
    {
        $this->framework = $framework;
        $this->security = $security;
        $this->runtime = $runtime;
    }

    /**
     * @param Request $request
     * @param ModuleModel $model
     * @param string $section
     * @param array|null $classes
     * @param PageModel|null $page
     * @return Response
     * @throws \Exception
     */
    public function __invoke(Request $request, ModuleModel $model, string $section, array $classes = null, PageModel $page = null): Response
    {
        // Is frontend
        if ($page instanceof PageModel && $this->get('contao.routing.scope_matcher')->isFrontendRequest($request))
        {
            /** @var Controller $controllerAdapter */
            $controllerAdapter = $this->framework->getAdapter(Controller::class);

            /** @var Environment $environmentAdapter */
            $environmentAdapter = $this->framework->getAdapter(Environment::class);

            /** @var FrontendUser $user */
            $objUser = $this->security->getUser();
            if (!$objUser instanceof FrontendUser)
            {
                if ($request->query->has('date') || $request->query->has('resType') || $request->query->has('res'))
                {
                    $url = \Haste\Util\Url::removeQueryString(['date', 'resType', 'res'], $environmentAdapter->get('request'));
                    $controllerAdapter->redirect($url);
                }
                // Return empty string if user has not logged in as a frontend user
                return new Response('', Response::HTTP_NO_CONTENT);
            }

            // Add session id to url
            if (!$request->query->has('sessionId'))
            {
                $url = $environmentAdapter->get('request');

                $params = [
                    sprintf(
                        'sessionId=%s',
                        sha1(microtime()) . sha1(random_bytes(6))
                    ),
                ];
                $url = \Haste\Util\Url::addQueryString(implode('&', $params), $url);
                // redirect
                $controllerAdapter->redirect($url);
            }

            // Initialize application
            $this->runtime->initialize((int) $model->id, (int) $page->id);
        }

        // Call the parent method
        return parent::__invoke($request, $model, $section, $classes);
    }

    /**
     * @param Template $template
     * @param ModuleModel $model
     * @param Request $request
     * @return null|Response
     */
    protected function getResponse(Template $template, ModuleModel $model, Request $request): ?Response
    {
        $template->sessionId = $request->query->get('sessionId');

        // Let vue.js do the rest ;-)
        return $template->getResponse();
    }

}
