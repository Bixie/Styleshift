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

//facebook crap
JFactory::getDocument()->addScriptDeclaration(
"<!-- Facebook Conversion Code for Conversie - Styleshift - Wat kost een website -->
<script>(function() {
 var _fbq = window._fbq || (window._fbq = []);
 if (!_fbq.loaded) {
   var fbds = document.createElement('script');
   fbds.async = true;
   fbds.src = '//connect.facebook.net/en_US/fbds.js';
   var s = document.getElementsByTagName('script')[0];
   s.parentNode.insertBefore(fbds, s);
   _fbq.loaded = true;
 }
})();
window._fbq = window._fbq || [];
window._fbq.push(['track', '6020912795179', {'value':'0.00','currency':'EUR'}]);
</script>
<noscript><img height=\"1\" width=\"1\" alt=\"\" style=\"display:none\" src=\"https://www.facebook.com/tr?ev=6020912795179&amp;cd[value]=0.00&amp;cd[currency]=EUR&amp;noscript=1\" /></noscript>
");

require JModuleHelper::getLayoutPath('mod_styleshifttool', $params->get('layout', 'default'));