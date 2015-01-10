<?php
/* *
 *	Styleshift
 *  mail.php
 *	Created on 9-1-2015 20:41
 *  
 *  @author Matthijs
 *  @copyright Copyright (C)2015 Bixie.nl
 *
 */
 
// No direct access
defined('_JEXEC') or die;

/**
 * @var array $data
 * @var array $selected
 * @var string $opmerking
 */
?>

<p>Bedankt voor je interesse.</p>
<p>Aantal pagina's: <?php echo $data['eenmalig.aantalpags']; ?></p>
<p>Punten die je hebt aangezet:</p>
<ul>
	<?php foreach ($selected as $selData) : ?>
		<li><?php echo $selData->tekst; ?></li>
	<?php endforeach; ?>
</ul>
<p>Eventuele opmerking:</p>
<p><?php echo $opmerking; ?></p>
<p>Groetn!</p>