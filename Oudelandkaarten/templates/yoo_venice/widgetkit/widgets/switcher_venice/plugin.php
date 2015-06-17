<?php
/**
* @package   yoo_venice
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

return array(

    'name' => 'widget/switcher_venice',

    'main' => 'YOOtheme\\Widgetkit\\Widget\\Widget',

    'config' => array(

        'name'  => 'switcher_venice',
        'label' => 'Switcher+',
        'icon'  => 'widgets/switcher_venice/widget.svg',
        'view'  => 'plugins/widgets/switcher_venice/views/widget.php',
        'item'  => array('title', 'content', 'media'),
        'settings' => array(
            'nav' => 'nav',
            'thumbnail_height' => '290',
            'position' => 'top',
            'alignment' => 'left',
            'animation' => 'fade',
            'text_align' => 'center',
            'title_size' => 'panel',
            'media_align' => 'left',
            'media_width' => '2-5',
            'media_breakpoint' => 'medium',
            'content_align' => true,
            'link_style' => 'button-large',
            'title' => true,
            'media' => true,
            'content' => true,
            'link' => true,
            'link_text' => 'Read more',
            'class' => ''
        )

    ),

    'events' => array(

        'init.site' => function($event, $app) {
        },

        'init.admin' => function($event, $app) {
            $app['angular']->addTemplate('switcher_venice.edit', 'plugins/widgets/switcher_venice/views/edit.php');
        }

    )

);
