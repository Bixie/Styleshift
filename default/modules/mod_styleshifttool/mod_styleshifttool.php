<?php
/**
 *    com_bix_printshop - Online-PrintStore for Joomla
 *  Copyright (C) 2010-2012 Matthijs Alles
 *    Bixie.nl

 */

// no direct access
defined('_JEXEC') or die;
/**
 * @var \Joomla\Registry\Registry $params
 */

//init vars
$configString = file_get_contents(__DIR__.'/config.json');
$config = new \Joomla\Registry\Registry();
$config->loadString($configString);
$config->set('token',JSession::getFormToken());

require JModuleHelper::getLayoutPath('mod_styleshifttool', $params->get('layout', 'default'));