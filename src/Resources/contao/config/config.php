<?php

/**
 * Chronometry Module for Contao CMS
 * Copyright (c) 2008-2019 Marko Cupic
 * @package chronometry-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2019
 * @link https://github.com/markocupic/chronometry-bundle
 */

/**
 * Back end modules
 */
$GLOBALS['BE_MOD']['resourceBooking'] = array(
    'resourceType' => array
    (
        'tables' => array('tl_resource_booking_resource_type'),
        'table'  => array('TableWizard', 'importTable'),
        'list'   => array('ListWizard', 'importList')
    ),
    'resource'     => array
    (
        'tables' => array('tl_resource_booking_resource'),
        'table'  => array('TableWizard', 'importTable'),
        'list'   => array('ListWizard', 'importList')
    ),
    'timeSlotType' => array
    (
        'tables' => array('tl_resource_booking_time_slot_type', 'tl_resource_booking_time_slot'),
    ),
    'bookings' => array
    (
        'tables' => array('tl_resource_booking'),
    )
);

/**
 * Front end modules
 */
array_insert($GLOBALS['FE_MOD'], 2, array
(
    'resourceBooking' => array
    (
        'resourceBookingWeekCalendar' => 'Markocupic\\ResourceBookingBundle\\ModuleWeekcalendar',
    )
));

// Asset path
define('MOD_RESOURCE_BOOKING_ASSET_PATH', 'bundles/markocupicresourcebooking');

// Hooks
$GLOBALS['TL_HOOKS']['addCustomRegexp'][] = array('Markocupic\\ResourceBookingBundle\\RegexpHook', 'customRegexp');

