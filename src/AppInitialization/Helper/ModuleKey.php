<?php

declare(strict_types=1);

/*
 * This file is part of Resource Booking Bundle.
 *
 * (c) Marko Cupic 2021 <m.cupic@gmx.ch>
 * @license MIT
 * @link https://github.com/markocupic/resource-booking-bundle
 */

namespace Markocupic\ResourceBookingBundle\AppInitialization\Helper;

/**
 * Class ModuleKey.
 *
 * The module key is necessary to run multiple rbb applications on the same page
 * and is sent as a post parameter in every xhr request.
 *
 * The session data of each rbb instance is stored under $_SESSION[_resource_booking_bundle_attributes][#moduleKey#]
 *
 * The module key (#moduleId_#moduleIndex f.ex. 33_0) contains the module id and the module index
 * The module index is 0, if the current module is the first rbb module on the current page
 * The module index is 1, if the current module is the first rbb module on the current page, etc.
 *
 * Do only run once ModuleIndex::setModuleIndex() per module instance;
 */
class ModuleKey
{
    /**
     * @var string
     */
    private static $moduleKey;

    public static function setModuleKey(string $str): void
    {
        static::$moduleKey = $str;
    }

    /**
     * @throws \Exception
     */
    public static function getModuleKey(): ?string
    {
        return static::$moduleKey;
    }
}
