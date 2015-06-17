<?php
/**
* @package   yoo_venice
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// Nav
$nav = ' uk-grid-width-1-' . count($items);

$javascript = 'switcher';


// Animation
$animation = $settings['animation'] ? ',animation:\'' . $settings['animation'] . '\'' : '';

?>

<ul class="wk-switcher-venice-nav uk-hidden-small uk-grid<?php echo $nav; ?>" data-uk-<?php echo $javascript; ?>="{connect:'#wk-<?php echo $settings['connect_id']; ?>'<?php echo $animation; ?>}">
<?php foreach ($items as $item) : ?>
    <li>
        <a class="uk-overlay" href="">
            <div style="background: url(<?php echo $item['media']; ?>) 50%; background-size: cover; height: <?php echo $settings['thumbnail_height']; ?>px;"></div>
            <div class="uk-overlay-area">
                <div class="uk-overlay-area-content">
                    <?php if ($item['title'] && $settings['title']) : ?>
                    <h3 class="uk-h4"><?php echo $item['title']; ?></h3>
                    <?php endif; ?>
                </div>
            </div>
        </a>
    </li>
<?php endforeach; ?>
</ul>
