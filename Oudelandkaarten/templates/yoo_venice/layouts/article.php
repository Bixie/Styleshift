<?php
/**
* @package   yoo_venice
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
*/

    global $tm_count;
    if (!isset($tm_count)) {
        $tm_count = 1;
    }
    else {
        $tm_count++;
    }
?>

<?php if ($this['config']->get('article_style')=='tm-article-blog' && $this->warp['system']->application->input->get('view') == 'category' && $image) : ?>
<article class="uk-article tm-article-blog-featured-image" <?php if ($permalink) echo 'data-permalink="'.$permalink.'"'; ?>>

<div class="uk-grid" data-uk-grid-match>

	<?php if ($image && $image_alignment == 'none') : ?>

	<div class="uk-width-medium-1-2 <?php echo (($tm_count) % 2 == 1) ? 'uk-flex-order-first-medium' : 'uk-float-right uk-flex-order-last-medium'; ?> tm-article-blog-image">
		<?php if ($url) : ?>
			<a href="<?php echo $url; ?>" style="background-image:url(<?php echo $image; ?>);"></a>
		<?php else : ?>
			<div class="tm-article-image" style="background-image:url(<?php echo $image; ?>);"></div>
		<?php endif; ?>
	</div>

	<div class="uk-width-medium-1-2 tm-article-blog-content uk-flex uk-flex-middle">
	<div>
		<?php if ($title) : ?>
		<h1 class="uk-article-title">
			<?php if ($url && $title_link) : ?>
				<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
			<?php else : ?>
				<span><?php echo $title; ?></span>
			<?php endif; ?>
		</h1>
		<?php endif; ?>

	<?php else : ?>

	<div class="uk-width-1-1">
	<div>
		<?php if ($title) : ?>
		<?php echo $hook_beforearticle; ?>
		<h1 class="uk-article-title">
			<?php if ($url && $title_link) : ?>
				<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
			<?php else : ?>
			<?php echo $title; ?>
			<?php endif; ?>
		</h1>

		<?php echo $hook_aftertitle; ?>

		<?php endif; ?>

		<?php if ($image) : ?>
			<?php if ($url) : ?>
				<a class="uk-align-<?php echo $image_alignment; ?>" href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
			<?php else : ?>
				<img class="uk-align-<?php echo $image_alignment; ?>" src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>">
			<?php endif; ?>
		<?php endif; ?>

	<?php endif; ?>

		<?php if ($article) : ?>
		<div class="tm-article">
			<?php echo $article; ?>
		</div>
		<?php endif; ?>

		<?php if ($tags) : ?>
		<p><?php echo JText::_('TPL_WARP_TAGS').': '.$tags; ?></p>
		<?php endif; ?>

		<?php if ($more) : ?>
			<a class="uk-button uk-button-large" href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $more; ?></a>
		<?php endif; ?>

		<?php if ($author || $date || $category) : ?>
		<p class="uk-article-meta">

			<?php

				$author   = ($author && $author_url) ? '<a href="'.$author_url.'">'.$author.'</a>' : $author;
				$date     = ($date) ? ($datetime ? '<span class="tm-article-date"><time datetime="'.$datetime.'">'.JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3')).'</time></span>' : JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3'))) : '';
				$category = ($category && $category_url) ? '<span class="tm-article-category uk-hidden-small"><a href="'.$category_url.'">'.$category.'</a></span>' : $category;


				if ($author) {
					echo '<span class="tm-article-author uk-hidden-small">'.$author.'</span>';
				}

				if ($date) {
					echo $date;
				}

				if ($category) {
					echo $category;
				}

			?>

		</p>
		<?php endif; ?>

		<?php if ($edit) : ?>
		<p><?php echo $edit; ?></p>
		<?php endif; ?>

		<?php if ($previous || $next) : ?>
        <ul class="uk-pagination">
            <?php if ($previous) : ?>
            <li class="uk-pagination-previous">
                <a href="<?php echo $previous; ?>" title="<?php echo JText::_('JPREV') ?>">
                    <i class="uk-icon-arrow-left"></i>
                    <?php echo JText::_('JPREV') ?>
                </a>
            </li>
            <?php endif; ?>

            <?php if ($next) : ?>
            <li class="uk-pagination-next">
                <a href="<?php echo $next; ?>" title="<?php echo JText::_('JNEXT') ?>">
                    <?php echo JText::_('JNEXT') ?>
                    <i class="uk-icon-arrow-right"></i>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>

		<?php echo $hook_afterarticle; ?>
	</div>

	</div>
</div>

</article>

<?php else : ?>

<article class="uk-article<?php if ($image && $image_alignment == 'none') echo ' tm-has-featured-image'; ?>" <?php if ($permalink) echo 'data-permalink="'.$permalink.'"'; ?>>

	<?php if ($image && $image_alignment == 'none') : ?>
		<?php if ($url) : ?>
			<a class="tm-featured-image" href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
		<?php else : ?>
			<span class="tm-featured-image"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></span>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($title) : ?>
	<h1 class="uk-article-title">
		<?php if ($url && $title_link) : ?>
			<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $title; ?></a>
		<?php else : ?>
			<?php echo $title; ?>
		<?php endif; ?>
	</h1>
	<?php endif; ?>

	<?php echo $hook_aftertitle; ?>

	<?php if ($image && $image_alignment != 'none') : ?>
		<?php if ($url) : ?>
			<a class="uk-align-<?php echo $image_alignment; ?>" href="<?php echo $url; ?>" title="<?php echo $image_caption; ?>"><img src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>"></a>
		<?php else : ?>
			<img class="uk-align-<?php echo $image_alignment; ?>" src="<?php echo $image; ?>" alt="<?php echo $image_alt; ?>">
		<?php endif; ?>
	<?php endif; ?>

	<?php echo $hook_beforearticle; ?>

	<?php if ($article) : ?>
	<div>
		<?php echo $article; ?>
	</div>
	<?php endif; ?>

	<?php if ($tags) : ?>
	<p><?php echo JText::_('TPL_WARP_TAGS').': '.$tags; ?></p>
	<?php endif; ?>

	<?php if ($more) : ?>
	<p>
		<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><?php echo $more; ?></a>
	</p>
	<?php endif; ?>

	<?php if ($author || $date || $category) : ?>
	<p class="uk-article-meta">

		<?php

			$author   = ($author && $author_url) ? '<a href="'.$author_url.'">'.$author.'</a>' : $author;
			$date     = ($date) ? ($datetime ? '<span class="tm-article-date"><time datetime="'.$datetime.'">'.JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3')).'</time></span>' : JHtml::_('date', $date, JText::_('DATE_FORMAT_LC3'))) : '';
			$category = ($category && $category_url) ? '<span class="tm-article-category uk-hidden-small"><a href="'.$category_url.'">'.$category.'</a></span>' : $category;


			if ($author) {
				echo '<span class="tm-article-author uk-hidden-small">'.$author.'</span>';
			}

			if ($date) {
				echo $date;
			}

			if ($category) {
				echo $category;
			}

		?>

	</p>
	<?php endif; ?>

	<?php if ($edit) : ?>
	<p><?php echo $edit; ?></p>
	<?php endif; ?>

	<?php if ($previous || $next) : ?>
    <ul class="uk-pagination">
        <?php if ($previous) : ?>
        <li class="uk-pagination-previous">
            <a href="<?php echo $previous; ?>" title="<?php echo JText::_('JPREV') ?>">
                <i class="uk-icon-arrow-left"></i>
                <?php echo JText::_('JPREV') ?>
            </a>
        </li>
        <?php endif; ?>

        <?php if ($next) : ?>
        <li class="uk-pagination-next">
            <a href="<?php echo $next; ?>" title="<?php echo JText::_('JNEXT') ?>">
                <?php echo JText::_('JNEXT') ?>
                <i class="uk-icon-arrow-right"></i>
            </a>
        </li>
        <?php endif; ?>
    </ul>
    <?php endif; ?>

	<?php echo $hook_afterarticle; ?>

</article>
<?php endif; ?>