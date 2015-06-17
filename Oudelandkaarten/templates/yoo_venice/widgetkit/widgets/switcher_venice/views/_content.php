<?php
/**
* @package   yoo_venice
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

// Margin
$margin = '';
if ($settings['position'] != 'left' && $settings['position'] != 'right') {
    $margin = 'tm-margin-' . $settings['position'];
}

// Media Align
switch ($settings['media_align']) {
    case 'top':
    case 'bottom':
        $media_align = 'uk-text-center';
        break;
    case 'left':
        $media_align = 'uk-align-medium-left uk-text-center';
        break;
    case 'right':
        $media_align = 'uk-align-medium-right uk-text-center';
        break;
}

// Media Width
$media_width = 'uk-width-1-1 uk-width-medium-' . $settings['media_width'];

switch ($settings['media_width']) {
    case '1-5':
        $content_width = '4-5';
        break;
    case '1-4':
        $content_width = '3-4';
        break;
    case '3-10':
        $content_width = '7-10';
        break;
    case '1-3':
        $content_width = '2-3';
        break;
    case '2-5':
        $content_width = '3-5';
        break;
    case '1-2':
    default:
        $content_width = '1-2';
        break;
}

$content_width = 'uk-width-1-1 uk-width-medium-' . $content_width;

// Content Align
$content_align  = $settings['content_align'] ? 'uk-flex uk-flex-middle' : '';

// Title Size
switch ($settings['title_size']) {
    case 'h1':
    case 'h2':
    case 'h3':
    case 'h4':
        $title_size = 'uk-' . $settings['title_size'] . ' uk-margin-top-remove';
        break;
    case 'large':
        $title_size = 'uk-heading-large uk-margin-top-remove';
        break;
    default:
        $title_size = 'uk-panel-title';
        break;
}

// Link Style
switch ($settings['link_style']) {
    case 'button':
        $link = 'uk-button';
        break;
    case 'primary':
        $link = 'uk-button uk-button-primary';
        break;
    case 'button-large':
        $link = 'uk-button uk-button-large';
        break;
    case 'primary-large':
        $link = 'uk-button uk-button-large uk-button-primary';
        break;
    default:
        $link = '';
        break;
}

?>

<ul id="wk-<?php echo $settings['connect_id']; ?>" class="wk-switcher-venice-content uk-switcher uk-text-<?php echo $settings['text_align']; ?> <?php echo $margin; ?>" data-uk-check-display>
<?php foreach ($items as $item) : ?>

    <?php

        // Media Type
        $attrs = array();
        $class  = '';

        if ($item->type('media') == 'image') {
            $attrs['alt'] = $item['title'];
        }

        if ($item->type('media') == 'video') {
            $class .= 'uk-responsive-width ';
            $attrs['controls'] = '';
        }

        if ($item['width']) {
            $attrs['width'] = $item['width'];
        }

        if ($item['height']) {
            $attrs['height'] = $item['height'];
        }

        if ($class) {
            $attrs['class'] = $class;
        }

        $media = $item->media('media', $attrs);

        if ($item['link'] && $settings['link']) {
            $media = '<a href="' . $item['link'] . '">' . $media . '</a>';
        }

    ?>

    <li>
        <?php if ($item['media'] && $settings['media'] && $settings['media_align'] == 'top') : ?>
        <p class="<?php echo $media_align; ?>"><?php echo $media; ?></p>
        <?php endif; ?>

        <?php if ($item['media'] && $settings['media'] && in_array($settings['media_align'], array('left', 'right'))) : ?>
        <div class="uk-grid uk-flex" data-uk-grid-margin>
            <div class="<?php echo $media_width ?><?php if ($settings['media_align'] == 'right') echo ' uk-float-right uk-flex-order-last' ?>">
                <?php echo $media; ?>
            </div>
            <div class="<?php echo $content_width ?> <?php echo $content_align; ?>">
                <div class="uk-slidenav-position">
        <?php endif; ?>

        <?php if ($item['title'] && $settings['title']) : ?>
        <h3 class="<?php echo $title_size; ?>"><?php echo $item['title']; ?></h3>
        <?php endif; ?>

        <?php if ($item['media'] && $settings['media'] && $settings['media_align'] == 'bottom') : ?>
        <p class="<?php echo $media_align; ?>"><?php echo $media; ?></p>
        <?php endif; ?>

        <?php if ($item['content'] && $settings['content']) : ?>
        <div class="uk-margin"><?php echo $item['content']; ?></div>
        <?php endif; ?>

        <?php if ($item['link'] && $settings['link']) : ?>
        <p><a<?php if($link) echo ' class="' . $link . '"' ?> href="<?php echo $item['link']; ?>"><?php echo $settings['link_text']; ?></a></p>
        <?php endif; ?>

        <?php if ($item['media'] && $settings['media'] && in_array($settings['media_align'], array('left', 'right'))) : ?>

                    <a href="#" data-uk-switcher-item="previous" class="uk-slidenav uk-slidenav-inverted uk-slidenav-previous"></a>
                    <a href="#" data-uk-switcher-item="next" class="uk-slidenav uk-slidenav-inverted uk-slidenav-next"></a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </li>

<?php endforeach; ?>
</ul>