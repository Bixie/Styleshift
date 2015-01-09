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

	public function processAjax () {
		$return = array(
			'success'=>false,
			'messages'=>array('danger'=>array(),'warning'=>array(),'success'=>array())
		);



		return $return;
	}

}