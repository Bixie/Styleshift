<?php
/**
 *    com_bix_printshop - Online-PrintStore for Joomla
 *  Copyright (C) 2010-2012 Matthijs Alles
 *    Bixie.nl

 */

// no direct access
defined('_JEXEC') or die;
/**
 * @var JRegistry $params
 */

$params->get('scrollspyls', '');


require JModuleHelper::getLayoutPath('mod_styleshifttool', $params->get('layout', 'default'));