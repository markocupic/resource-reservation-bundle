<?php

/**
 * Chronometry Module for Contao CMS
 * Copyright (c) 2008-2019 Marko Cupic
 * @package chronometry-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2019
 * @link https://github.com/markocupic/chronometry-bundle
 */

namespace Contao;

/**
 * Class ResourceReservationModel
 * @package Contao
 */
class ResourceReservationModel extends \Model
{

    /**
     * Table name
     * @var string
     */
    protected static $strTable = 'tl_resource_reservation';

    /**
     * @param $objResource
     * @param $starttime
     * @param $endtime
     * @param $memberid
     * @return ResourceReservationModel
     */
    public static function findOneByResourceIdStarttimeEndtimeAndOwnerId($objResource, $starttime, $endtime, $memberid)
    {
        $arrColumn = array('pid=?', 'startTime=?', 'endTime=?', 'member=?');
        $arrValues = array($objResource->id, $starttime, $endtime, $memberid);
        return self::findOneBy($arrColumn, $arrValues);
    }

}
