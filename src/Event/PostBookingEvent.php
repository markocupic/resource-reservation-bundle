<?php

declare(strict_types=1);

/*
 * This file is part of Resource Booking Bundle.
 *
 * (c) Marko Cupic 2020 <m.cupic@gmx.ch>
 * @license MIT
 * @link https://github.com/markocupic/resource-booking-bundle
 */

namespace Markocupic\ResourceBookingBundle\Event;

use Contao\FrontendUser;
use Contao\Model\Collection;
use Markocupic\ResourceBookingBundle\Session\Attribute\ArrayAttributeBag;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class PostBookingEvent
 * @package Markocupic\ResourceBookingBundle\Event
 */
class PostBookingEvent extends Event
{
    /**
     * @var Collection
     */
    private $bookingCollection;

    /**
     * @var FrontendUser
     */
    private $user;

    /**
     * @var ArrayAttributeBag
     */
    private $sessionBag;

    public function setBookingCollection(Collection $bookingCollection): void
    {
        $this->bookingCollection = $bookingCollection;
    }

    public function getBookingCollection(): Collection
    {
        return $this->bookingCollection;
    }

    public function setUser(FrontendUser $user): void
    {
        $this->user = $user;
    }

    public function getUser(): FrontendUser
    {
        return $this->user;
    }

    public function setSessionBag(ArrayAttributeBag $sessionBag): void
    {
        $this->sessionBag = $sessionBag;
    }

    public function getSessionBag(): ArrayAttributeBag
    {
        return $this->sessionBag;
    }
}