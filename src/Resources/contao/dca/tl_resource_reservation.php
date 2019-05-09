<?php

/**
 * Chronometry Module for Contao CMS
 * Copyright (c) 2008-2019 Marko Cupic
 * @package chronometry-bundle
 * @author Marko Cupic m.cupic@gmx.ch, 2019
 * @link https://github.com/markocupic/chronometry-bundle
 */

/**
 * Table tl_calendar_events
 */
$GLOBALS['TL_DCA']['tl_resource_reservation'] = array(

    // Config
    'config'   => array(
        'dataContainer'    => 'Table',
        'switchToEdit'     => true,
        'ptable'           => 'tl_resource_reservation_resource',
        'enableVersioning' => true,
        'sql'              => array(
            'keys' => array(
                'id'  => 'primary',
                'pid' => 'index',
            ),
        ),
    ),
    // List
    'list'     => array(
        'sorting'           => array(
            'mode'        => 0,
            'fields'      => array('title ASC'),
            'panelLayout' => 'filter;sort,search,limit'
        ),
        'label'             => array
        (
            'fields'      => array('title'),
            'showColumns' => true
        ),
        'global_operations' => array(
            'all' => array(
                'label'      => &$GLOBALS['TL_LANG']['MSC']['all'],
                'href'       => 'act=select',
                'class'      => 'header_edit_all',
                'attributes' => 'onclick="Backend.getScrollOffset()" accesskey="e"',
            ),
        ),
        'operations'        => array(

            'edit'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_resource_reservation']['editmeta'],
                'href'  => 'act=edit',
                'icon'  => 'edit.gif',
            ),
            'delete' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_resource_reservation']['delete'],
                'href'       => 'act=delete',
                'icon'       => 'delete.gif',
                'attributes' => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"',
            ),
            'toggle' => array(
                'label'      => &$GLOBALS['TL_LANG']['tl_resource_reservation']['toggle'],
                'icon'       => 'visible.gif',
                'attributes' => 'onclick="Backend.getScrollOffset();return AjaxRequest.toggleVisibility(this,%s)"',
                //'button_callback' => array('tl_resource_reservation', 'toggleIcon'),
            ),
            'show'   => array(
                'label' => &$GLOBALS['TL_LANG']['tl_resource_reservation']['show'],
                'href'  => 'act=show',
                'icon'  => 'show.gif',
            ),
        ),
    ),
    // Palettes
    'palettes' => array(
        'default' => '{title_legend},pid,title,description,member;{time_legend},startTime,endTime',
    ),
    // Fields
    'fields'   => array(
        'id'          => array(
            'sql' => "int(10) unsigned NOT NULL auto_increment",
        ),
        'pid'         => array(
            'label'      => &$GLOBALS['TL_LANG']['tl_resource_reservation']['pid'],
            'inputType'  => 'select',
            'foreignKey' => 'tl_resource_reservation_resource.title',
            'eval'       => array('mandatory' => true),
            'sql'        => "int(10) unsigned NOT NULL default '0'",
            'relation'   => array('type' => 'belongsTo', 'load' => 'lazy')
        ),
        'tstamp'      => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        'member'      => array(
            'label'      => &$GLOBALS['TL_LANG']['tl_resource_reservation']['member'],
            'inputType'  => 'select',
            'foreignKey' => 'tl_member.CONCAT(firstname," ",lastname)',
            'eval'       => array('mandatory' => true, 'tl_class' => 'clr'),
            'relation'   => array('type' => 'belongsTo', 'load' => 'lazy'),
            'sql'        => "int(10) unsigned NOT NULL default '0'",
        ),
        'firstname'       => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_resource_reservation']['firstname'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'lastname'       => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_resource_reservation']['lastname'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => false, 'maxlength' => 255, 'tl_class' => 'clr'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'title'       => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_resource_reservation']['title'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'text',
            'eval'      => array('mandatory' => true, 'maxlength' => 255, 'tl_class' => 'clr'),
            'sql'       => "varchar(255) NOT NULL default ''"
        ),
        'description' => array(
            'label'     => &$GLOBALS['TL_LANG']['tl_resource_reservation']['description'],
            'exclude'   => true,
            'search'    => true,
            'inputType' => 'textarea',
            'eval'      => array('tl_class' => 'clr'),
            'sql'       => "mediumtext NULL"
        ),
        'startTime'   => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_resource_reservation']['startTime'],
            'default'       => time(),
            'exclude'       => true,
            'filter'        => true,
            'sorting'       => true,
            'flag'          => 8,
            'inputType'     => 'text',
            'eval'          => array('rgxp' => 'datim', 'mandatory' => true, 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'load_callback' => array
            (//array('tl_resource_reservation', 'loadTime')
            ),
            'sql'           => "int(10) NULL"
        ),
        'endTime'     => array
        (
            'label'         => &$GLOBALS['TL_LANG']['tl_calendar_events']['endTime'],
            'default'       => time(),
            'exclude'       => true,
            'inputType'     => 'text',
            'eval'          => array('rgxp' => 'datim', 'mandatory' => true, 'doNotCopy' => true, 'datepicker' => true, 'tl_class' => 'w50 wizard'),
            'load_callback' => array
            (//array('tl_resource_reservation', 'loadTime')
            ),
            'sql'           => "int(10) NULL"
        ),
    )

);

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
class tl_resource_reservation extends Backend
{

    /**
     * Import the back end user object
     */
    public function __construct()
    {
        parent::__construct();
        $this->import('BackendUser', 'User');
    }

}
