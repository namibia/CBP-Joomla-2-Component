<?php
/**
* 
* 	@version 	2.1.0 June 8, 2012
* 	@package 	Encription Class
*	@copyright	Vast Development Method <http://www.vdm.io>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

/**
*	Encription Class
**/

class Vault{
	
	private $key1;
	private $key2;
	private $key3;
	private $key4;
	
	private $lock;
		
	public function __construct($lock = true){
		
		/* get salt */
		$key1 		=& JComponentHelper::getParams('com_costbenefitprojection')->get('salt_one');
		$key2 		=& JComponentHelper::getParams('com_costbenefitprojection')->get('salt_two');
		$this->key3 =& JComponentHelper::getParams('com_costbenefitprojection')->get('salt_three');
		$this->key4 =& JComponentHelper::getParams('com_costbenefitprojection')->get('salt_four');
				
		$this->key1 = md5($key1);
		$this->key2 = md5($key2);
		
		/* set type */
		$this->lock = $lock;
	}
	
	/**
	*	Decrypt secret
	*
	*	@return string base64
	**/
	private function unlock($secret)
	{
		/* start search */
		$seek 	= explode($this->key3, $secret);
		$found 	= explode($this->key4, $seek[1]);
		
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
	
		$key = substr($this->key1, 0, $ks/2) . substr(strtoupper($this->key2), (round(strlen($this->key2) / 2)), $ks/2);
	
		$key = substr($key.$this->key1.$this->key2.strtoupper($this->key1),0,$ks);
	
		/* Intialize encryption */
		mcrypt_generic_init($td, $key, $var);
	
		/* Decrypt encrypted string */
		$de = mdecrypt_generic($td, $ed);
	
		/* Terminate decryption handle and close module */
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		return unserialize(substr($de,0,$length));
	}
	
	/**
	*	Encrypt data
	*
	*	@return string base64
	**/
	private function lock($input)
	{
		$input = serialize($input);
	
		$length = strlen($input);
		/* Open the cipher */
		$td = mcrypt_module_open('rijndael-256', '', 'cbc', '');
	
		/* Create the IV and determine the keysize length */
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
		$ks = mcrypt_enc_get_key_size($td);
	
		$key = substr($this->key1, 0, $ks/2) . substr(strtoupper($this->key2), (round(strlen($this->key2) / 2)), $ks/2);
	
		$key = substr($key.$this->key1.$this->key2.strtoupper($this->key1),0,$ks);
	
		/* Intialize encryption */
		mcrypt_generic_init($td, $key, $iv);
	
		/* Encrypt data */
		$encrypted = mcrypt_generic($td, $input);
	
		/* Terminate decryption handle and close module */
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
		
		$encrypted = base64_encode($iv). $this->key3. $length . $this->key4. base64_encode($encrypted); 
		
		return $encrypted;
	}
	
	public function the($data, $define = false)
	{
    	if ($this->lock){
			$data = $this->lock($data);
		} else {
			$data = $this->unlock($data);
			if($define){
				if (is_array($data)){
					$this->defineArray($data);
					return true;
				}
			}
		}
		
		return $data;
	} 
	
	private function defineArray($array, $keys = NULL )
	{
		foreach( $array as $key => $value )
		{
			$keyname = ($keys ? $keys . "_" : "") . $key;
			if( is_array( $array[$key] ) )
				$this->defineArray( $array[$key], $keyname );
			else
				define( $keyname, $value);
			}
		}

}