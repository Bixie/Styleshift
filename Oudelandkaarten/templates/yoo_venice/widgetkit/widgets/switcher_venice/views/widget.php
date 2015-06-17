<?php
/**
* @package   yoo_venice
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// Connect Id
$settings['connect_id'] = substr(uniqid(), -3);

?>

<div class="wk-switcher-venice<?php if ($settings['class']) echo ' ' . $settings['class']; ?>">

    <?php if ($settings['position'] == 'top') : ?>
    <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name') . '/views/_nav.php', compact('items', 'settings')); ?>
    <?php endif ?>

    <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name') . '/views/_content.php', compact('items', 'settings')); ?>

    <?php if ($settings['position'] == 'bottom') : ?>
    <?php echo $this->render('plugins/widgets/' . $widget->getConfig('name') . '/views/_nav.php', compact('items', 'settings')); ?>
    <?php endif ?>

</div>