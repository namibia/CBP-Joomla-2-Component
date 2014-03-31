<?php
/**
* 
* 	@version 	1.0.0 July 24, 2013
* 	@package 	Cost Benefit Projection Tool Application
* 	@author  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Vast Development Method <http://www.vdm.io>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

function getDecryption($secret)
{
	/* get salt */
	$key1 = &JComponentHelper::getParams('com_costbenefitprojection')->get('salt_one');
	$key2 = &JComponentHelper::getParams('com_costbenefitprojection')->get('salt_two');
	$key3 = &JComponentHelper::getParams('com_costbenefitprojection')->get('salt_three');
	$key4 = &JComponentHelper::getParams('com_costbenefitprojection')->get('salt_four');

	/* start search */
	$seek 	= explode($key3, $secret);
	$found 	= explode($key4, $seek[1]);
	
	/* the original length as int */
	$length = (int)$found[0];
	
	/*  Set the encrypted value if correct  */
	if (base64_decode($found[1], true)) {
		$ed =  base64_decode($found[1]);
	} else {
		return false;
	}
	
	/*  Set the IV if correct  */
	if (base64_decode($seek[0], true)) {
		$var = base64_decode($seek[0]);
	} else {
		return false;
	}
	
	/* Open the cipher */
	$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');

	/* Determine the keysize length */
	$ks = mcrypt_enc_get_key_size($td);

	/* Create key */
	$key1 = md5($key1);
	$key2 = md5($key2);

	$key = substr($key1, 0, $ks/2) . substr(strtoupper($key2), (round(strlen($key2) / 2)), $ks/2);

	$key = substr($key.$key1.$key2.strtoupper($key1),0,$ks);

	/* Intialize encryption */
	mcrypt_generic_init($td, $key, $var);

	/* Decrypt encrypted string */
	$de = mdecrypt_generic($td, $ed);

	/* Terminate decryption handle and close module */
	mcrypt_generic_deinit($td);
	mcrypt_module_close($td);
	
	return unserialize(substr($de,0,$length));
}