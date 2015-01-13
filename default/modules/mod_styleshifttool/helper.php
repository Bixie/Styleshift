<?php
/* *
 *	Styleshift
 *  helper.php
 *	Created on 9-1-2015 14:36
 *  
 *  @author Matthijs
 *  @copyright Copyright (C)2015 Bixie.nl
 *
 */

// No direct access
defined('_JEXEC') or die;

class modStyleshifttoolHelper {
	/**
	 * @return array
	 * @throws Exception
	 */
	public static function processAjax () {
		return static::processRequest('process');
	}

	public static function submitAjax () {
		return static::processRequest('submit');
	}

	protected static function processRequest ($task){
		$return = [
			'success' => false,
			'token' => false,
			'messages' => ['danger' => [], 'warning' => [], 'success' => []]
		];
		if (!JSession::checkToken()) {
			$return['messages']['error'] = JText::_('JINVALID_TOKEN');
			return $return;
		}
		//init vars
		$app = JFactory::getApplication();
		$configString = file_get_contents(__DIR__.'/config.json');
		$config = new \Joomla\Registry\Registry();
		$config->loadString($configString);
		$data = $app->input->get('data', [], 'array');
		$return['data'] = $data;
		$return['token'] = JSession::getFormToken();
		//check data
		$selected = [];
		foreach ($data as $key => $value) {
			if ($value && $config->exists($key)) {
				$selected[$key] = $config->get($key);
			}
		}
		$return['calculation'] = static::calcOfferte($data['eenmalig.aantalpags'], $selected, $config);
		if ($task == 'process') {
			$return['success'] = true;
			return $return;
		}
		if ($task == 'submit') {

			//init mailvars
			$jconfig = JFactory::getConfig();
			$mailfrom = $jconfig->get('mailfrom');
			$fromname = $jconfig->get('fromname');
			$email = JMailHelper::isEmailAddress($data['email'])?$data['email']:$config->get('mailto', 'admin@bixie.nl');
			$opmerking = nl2br($data['opmerking']);

			$mailtekst = '';
			ob_start();
			include(__DIR__ . '/tmpl/mail.php');
			$mailtekst = ob_get_clean();
//			echo '<pre>'.$data['email'].$email;
//			print_r($config->get('paginas.vanaf', 6));
//			print_r($data);
//			echo '</pre>';

			$mail = JFactory::getMailer();
			$mail->IsHTML(true);
			$mail->AddReplyTo(array($mailfrom, $fromname));
			$mail->setSubject($config->get('mailOnderwerp', 'Offertebevestiging'))
				->setBody($mailtekst);
			if (!$mail->AddAddress($email, '')) {
				throw new Exception('Fout in mailadres ' . $email);
			}
			if (!$mail->AddCC($config->get('mailto', 'admin@bixie.nl'), $fromname)) {
				throw new Exception('Fout in mailadres admin');
			}
			$result = $mail->Send();
			if ($result) {
				$return['messages']['success'][] = 'Mail verzonden naar ' . $email . '.';
				$return['success'] = true;
			} else {
				$return['messages']['danger'][] = 'Fout in verzenden mail.';
			}

		}

		return $return;
	}

	/**
	 * @param $aantalpags
	 * @param $selected
	 * @param \Joomla\Registry\Registry $config
	 * @return array
	 * @throws Exception
	 */
	protected static function calcOfferte ($aantalpags, $selected, $config) {
		$calculation = [
			'eenmalig'=>$config->get('basisprijs', 0),
			'periodiek'=>$config->get('permaandprijs', 0)
		];
		foreach ($selected as $key => $data) {
			$parts = explode('.', $key);
			if (!isset($calculation[$parts[0]])) {
				throw new Exception("Fout in data: key $key niet in config");
			}
			$calculation[$parts[0]] += $data->prijs;
		}
//			echo '<pre>'.$aantalpags;
//			print_r($config->get('paginas.vanaf', 6));
//			print_r($config->get('paginas.perstuk', 0));
//			echo '</pre>';
		//aantal pags
		if ($aantalpags >= $config->get('paginas.vanaf', 6)) {
			$calcPags = $aantalpags - ($config->get('paginas.vanaf', 6) - 1);
			$calculation['eenmalig'] += $calcPags * $config->get('paginas.perstuk', 0);
		}
		return $calculation;
	}

}