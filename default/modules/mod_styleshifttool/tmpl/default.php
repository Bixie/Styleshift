<?php
/**
 *  mod_styleshifttool
 *  Copyright (C) 2015 Matthijs Alles
 *  Bixie.nl
 */

/**
 * @var \Joomla\Registry\Registry $config
 * @var \Joomla\Registry\Registry $params
 */

// no direct access
defined('_JEXEC') or die;

?>
<script type="text/rowTemplate">
	<li class="bix-item">
		<div class="uk-flex uk-flex-middle uk-margin-small-top uk-margin-small-bottom">
			<div class="uk-width-2-5 uk-width-medium-3-5">
				<span class="">{{tekst}}</span>
			</div>
			<div class="uk-width-1-5 uk-width-medium-1-5 radio-toggle uk-flex uk-flex-center">
				<i class="uk-icon-info-circle uk-icon-medium uk-text-primary"
				   title="{{tooltip}}" data-uk-tooltip="{pos:'left'}"></i>
			</div>
			<div class="uk-width-2-5 uk-width-medium-1-5 uk-flex uk-flex-center">
				<div class="toggle">
					<input type="checkbox" id="{{type}}_{{naam}}" name="{{type}}.{{naam}}" {{#defaultVal}} checked="checked"{{/defaultVal}}/>
					<label for="{{type}}_{{naam}}"></label>
				</div>
			</div>
		</div>
	</li>
</script>
<script type="text/prijsTemplate">
	<h2 class="bps-prijs-format uk-text-success uk-text-center">&euro; <span>{{prijs}}</span></h2>
</script>

<h1 class="uk-article-title">Wat kost een website</h1>

<p>Wij streven naar eenvoud en duidelijkheid. Daarom hebben wij deze tool ontwikkeld om u zelf
	de kosten van uw toekomstige website te laten berekenen.</p>

<div class="uk-margin-large-top" data-bix-styleshifttool='<?php echo $config->toString(); ?>'>

	<h3>Eenmalige investeringen</h3>
	<ul class="bix-eenmalig uk-list uk-list-line">
		<li class="bix-item">
			<div class="uk-grid uk-grid-small uk-margin-small-top uk-margin-small-bottom">
			    <div class="uk-width-medium-3-5">
					<div class="uk-flex uk-flex-middle">
						<div class="uk-width-4-5">
							<span class="">Aantal pagina's</span>
						</div>
						<div class="uk-width-1-5 radio-toggle uk-flex uk-flex-center">
							<i class="uk-icon-info-circle uk-icon-medium uk-text-primary"
							   title="Geef het aantal pagina's op dat de website zal bevatten. Eerste 5 pagina's zijn gratis."
							   data-uk-tooltip="{pos:'left'}"></i>
						</div>
					</div>

			    </div>
			    <div class="uk-width-medium-2-5 range-slider uk-flex uk-flex-center uk-flex-middle">
					<div class="">
						<input type="range" name="eenmalig.aantalpags"
							   min="<?php echo $config->get('paginas.min', 1); ?>"
							   max="<?php echo $config->get('paginas.max', 25); ?>"
							   value="<?php echo $config->get('paginas.value', 5); ?>" />
					</div>
					<div class="uk-margin-left">
						<strong class="output"><?php echo $config->get('paginas.value', 5); ?></strong>
					</div>
			    </div>
			</div>
		</li>
	</ul>
	<div class="uk-flex uk-flex-left">
		<div class="uk-width-2-3"><em>Eenmalig:</em></div>
		<div class="uk-width-1-3 bix-prijs-eenmalig"></div>
	</div>

	<h3>Periodieke investeringen</h3>
	<ul class="bix-periodiek uk-list uk-list-line"></ul>
	<div class="uk-flex uk-flex-right">
		<div class="uk-width-2-3"><em>Per maand:</em></div>
		<div class="uk-width-1-3 bix-prijs-periodiek"></div>
	</div>

	<div class="uk-form-row">
		<textarea class="uk-width-1-1" style="height:150px" rows="5"
				  placeholder="Overige vragen of opmerkingen"></textarea>
	</div>

	<div class="uk-form-row">
		<input class="uk-width-1-1 uk-form-large" type="email" name="email"
			   placeholder="Voer eventueel uw e-mailadres in"/>
	</div>

	<button type="button" class="uk-button uk-button-success uk-width-1-1 uk-button-large bix-submit"><i
			class="uk-icon-paper-plane-o uk-margin-small-right"></i>Verstuur deze offerte</button>

	<div class="uk-panel uk-panel-box uk-margin-top">

		<p>Naast bovenstaande producten en diensten hebben wij nog meer te bieden. Echter omdat veel van deze producten zo op maat
			voor u worden ontwikkeld, zijn ze niet geschikt voor een online offerte tool. Voor een overzicht van al onze producten kunt u hier kijken.</p>


	</div>
</div>

