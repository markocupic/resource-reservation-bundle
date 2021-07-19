<?php

declare(strict_types=1);

/*
 * This file is part of Resource Booking Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * @link https://github.com/markocupic/resource-booking-bundle
 */

namespace Markocupic\ResourceBookingBundle\AjaxController;

use Contao\System;
use Exception;
use Markocupic\ResourceBookingBundle\Event\AjaxRequestEvent;
use Markocupic\ResourceBookingBundle\Response\AjaxResponse;

/**
 * Class BookingFormValidationController.
 */
final class BookingFormValidationController extends AbstractController implements ControllerInterface
{
    /**
     * @required
     * Use setter via "required" annotation injection in child classes instead of __construct injection
     * see: https://stackoverflow.com/questions/58447365/correct-way-to-extend-classes-with-symfony-autowiring
     * see: https://symfony.com/doc/current/service_container/calls.html
     */
    public function _setController(): void
    {
    }

    /**
     * @throws Exception
     */
    public function generateResponse(AjaxRequestEvent $ajaxRequestEvent): void
    {
        /** @var System $systemAdapter */
        $systemAdapter = $this->framework->getAdapter(System::class);

        // Load language file
        $systemAdapter->loadLanguageFile('default', $this->translator->getLocale());
        $ajaxResponse = $ajaxRequestEvent->getAjaxResponse();

        $this->initialize();

        $ajaxResponse->setStatus(AjaxResponse::STATUS_ERROR);
        $ajaxResponse->setData('noDatesSelected', false);
        $ajaxResponse->setData('resourceIsAlreadyFullyBooked', false);
        $ajaxResponse->setData('passedValidation', false);
        $ajaxResponse->setData('noBookingRepeatStopWeekTstampSelected', false);
        $ajaxResponse->setData('passedValidation', true);

        $slotCollection = $this->getSlotCollectionFromRequest();

        if (!$this->isBookingPossible($slotCollection)) {
            $ajaxResponse->setData('passedValidation', false);

            if ($this->hasErrorMessage()) {
                $ajaxResponse->setErrorMessage($this->translator->trans($this->getErrorMessage(), [], 'contao_default'));
            }

            if (empty($slotCollection)) {
                $ajaxResponse->setErrorMessage($this->translator->trans('RBB.ERR.selectBookingDatesPlease', [], 'contao_default'));
            } else {
                $slotCollection->reset();

                while ($slotCollection->next()) {
                    $slot = $slotCollection->next();

                    if (true === $slot->invalidDate) {
                        $ajaxResponse->setErrorMessage($this->translator->trans('RBB.ERR.selectBookingDatesPlease', [], 'contao_default'));
                        break;
                    }

                    if (!$slot->isBookable) {
                        if ($slot->isFullyBooked) {
                            $ajaxResponse->setErrorMessage($this->translator->trans('RBB.ERR.resourceIsAlreadyFullyBooked', [], 'contao_default'));
                        } else {
                            $ajaxResponse->setErrorMessage($this->translator->trans('RBB.ERR.notEnoughItemsAvailable', [], 'contao_default'));
                        }
                        $ajaxResponse->setData('passedValidation', false);
                        break;
                    }
                }
            }
        } else {
            $ajaxResponse->setConfirmationMessage($this->translator->trans('RBB.MSG.resourceAvailable', [], 'contao_default'));
        }

        $ajaxResponse->setData('slotSelection', $slotCollection->fetchAll());
        $ajaxResponse->setStatus(AjaxResponse::STATUS_SUCCESS);
    }
}