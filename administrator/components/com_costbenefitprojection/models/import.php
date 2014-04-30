<?php
/**
* 
* 	@version 	2.0.0 March 13, 2014
* 	@package 	Staff Health Cost Benefit Projection
* 	@editor  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/

defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.archive');
jimport('joomla.filesystem.path');

class CostbenefitprojectionModelImport extends JModelLegacy
{
	
	/**
	 * @var object JTable object
	 */
	protected $_url = NULL;
	
	/**
	 * Import Settings
	 */
	protected $importType = NULL;
	protected $importDate = NULL;
	
	/**
	 * To check cause sets
	 */
	protected $countryName = false;
	protected $values = false;
	protected $totals = false;

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context = 'com_costbenefitprojection.import';

	/**
	 * Install an extension from either folder, url or upload.
	 *
	 * @return	boolean result of install
	 * @since	1.5
	 */
	function import()
	{
		$this->setState('action', 'install');
		
		// get selected type
		$this->importType = JRequest::getInt('import_type');
		
		// get import date
		$this->importDate = JRequest::getInt('import_year');

		switch(JRequest::getWord('installtype')) {
			case 'upload':
				$package = $this->_getPackageFromUpload();
				break;

			case 'url':
				$package = $this->_getPackageFromUrl();
				break;

			default:
				$package = false;
				break;
		}

		// Get an costbenefitprojection instance
		$costbenefitprojection = JInstaller::getInstance();
		// set name
		if ($this->importType == 1){
			$fileType = JText::_('COM_COSTBENEFITPROJECTION_TYPE_CAUSE');
		} elseif($this->importType == 2){
			$fileType = JText::_('COM_COSTBENEFITPROJECTION_TYPE_RISK');
		}
		// Install the package
		if (!$package) {
			// There was an error installing the package
			$msg = JText::sprintf('COM_COSTBENEFITPROJECTION_IMPORT_ERROR', $fileType);
			$result = false;
		} elseif ($this->importType == 3){
			if ($this->setList($package)) {
				// Package installed sucessfully
				$msg = JText::sprintf('COM_COSTBENEFITPROJECTION_IMPORT_SUCCESS', $fileType);
				$result = true;
	
				// Cleanup the install files
				$this->remove($package['dir']);
			} else {
				// There was an error installing the package
				$msg = JText::sprintf('COM_COSTBENEFITPROJECTION_IMPORT_ERROR', $fileType);
				$result = false;
			}
		} else {
			if ($this->setData($package)) {
				// Package installed sucessfully
				$msg = JText::sprintf('COM_COSTBENEFITPROJECTION_IMPORT_SUCCESS', $fileType);
				$result = true;
	
				// Cleanup the install files
				$this->remove($package['dir']);
			} else {
				// There was an error installing the package
				$msg = JText::sprintf('COM_COSTBENEFITPROJECTION_IMPORT_ERROR', $fileType);
				$result = false;
			}
		}

		// Set some model state values
		$app = JFactory::getApplication();
		$app->enqueueMessage($msg);
		
		return $result;
	}

	/**
	 * Works out an installation package from a HTTP upload
	 *
	 * @return package definition or false on failure
	 */
	protected function _getPackageFromUpload()
	{
		// Get the uploaded file information
		$userfile	= JRequest::getVar('install_package', null, 'files', 'array');

		// Make sure that file uploads are enabled in php
		if (!(bool) ini_get('file_uploads')) {
			JError::raiseWarning('', JText::_('COM_COSTBENEFITPROJECTION_MSG_INSTALL_WARNINSTALLFILE'));
			return false;
		}

		/*// Make sure that zlib is loaded so that the package can be unpacked
		if (!extension_loaded('zlib')) {
			JError::raiseWarning('', JText::_('COM_COSTBENEFITPROJECTION_MSG_INSTALL_WARNINSTALLZLIB'));
			return false;
		}*/

		// If there is no uploaded file, we have a problem...
		if (!is_array($userfile)) {
			JError::raiseWarning('', JText::_('COM_COSTBENEFITPROJECTION_MSG_INSTALL_NO_FILE_SELECTED'));
			return false;
		}

		// Check if there was a problem uploading the file.
		if ($userfile['error'] || $userfile['size'] < 1) {
			JError::raiseWarning('', JText::_('COM_COSTBENEFITPROJECTION_MSG_INSTALL_WARNINSTALLUPLOADERROR'));
			return false;
		}

		// Build the appropriate paths
		$config		= JFactory::getConfig();
		$tmp_dest	= $config->get('tmp_path') . '/' . $userfile['name'];
		$tmp_src	= $userfile['tmp_name'];

		// Move uploaded file
		jimport('joomla.filesystem.file');
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Park the downloaded package file
		$package = $this->check($tmp_dest, $userfile['name']);
		$package['type'] = 'upload';
		
		return $package;
	}

	/**
	 * Install an extension from a URL
	 *
	 * @return	Package details or false on failure
	 * @since	1.5
	 */
	protected function _getPackageFromUrl()
	{
		// Get a database connector
		$db = JFactory::getDbo();

		// Get the URL of the package to install
		$url 	= JRequest::getString('install_url');

		// Did you give us a URL?
		if (!$url) {
			JError::raiseWarning('', JText::_('COM_COSTBENEFITPROJECTION_MSG_INSTALL_ENTER_A_URL'));
			return false;
		}

		// Download the package at the URL given
		$p_file = $this->downloadPackage($url);

		// Was the package downloaded?
		if (!$p_file) {
			JError::raiseWarning('', JText::_('COM_COSTBENEFITPROJECTION_MSG_INSTALL_INVALID_URL'));
			return false;
		}

		$config		= JFactory::getConfig();
		$tmp_dest	= $config->get('tmp_path');

		// Park the downloaded package file
		$package = $this->check($tmp_dest . '/' . $p_file, $p_file);
		$package['type'] = 'url';

		return $package;
	}
	
	/**
	 * Check a file and verifies it as a CSV file
	 * Supports .gz .tar .tar.gz and .zip
	 *
	 * @param   string  $p_filename  The uploaded package filename or install directory
	 *
	 * @return  array  of elements
	 *
	 * @since   11.1
	 */
	protected function check($p_filename, $filename)
	{
		// Path to the archive
		$archivename = $p_filename;

		// Clean the paths to use for archive extraction
		$archivename = JPath::clean($archivename);
		
		// check the extention
		if ('csv' !== strtolower(pathinfo($archivename, PATHINFO_EXTENSION))){
			// Cleanup the install files
			$this->remove($archivename);
			return false;
		}		
		
		// set directory
		$retval['dir'] = $archivename;

		// set file name
		$retval['name'] = $filename;
		
		return $retval;
	}
	
	/**
	 * Clean up temporary uploaded package and unpacked extension
	 *
	 * @param   string  $package    Path to the uploaded package file
	 * @param   string  $resultdir  Path to the unpacked extension
	 *
	 * @return  boolean  True on success
	 *
	 * @since   11.1
	 */
	protected function remove($package)
	{
		$config = JFactory::getConfig();

		// Is the package file a valid file?
		if (is_file($package))
		{
			JFile::delete($package);
		}
		elseif (is_file(JPath::clean($config->get('tmp_path') . '/' . $package)))
		{
			// It might also be just a base filename
			JFile::delete(JPath::clean($config->get('tmp_path') . '/' . $package));
		}
	}
	
	/**
	 * Downloads a package
	 *
	 * @param   string  $url     URL of file to download
	 * @param   string  $target  Download target filename [optional]
	 *
	 * @return  mixed  Path to downloaded package or boolean false on failure
	 *
	 * @since   11.1
	 */
	protected function downloadPackage($url, $target = false)
	{
		$config = JFactory::getConfig();

		// Capture PHP errors
		$php_errormsg = 'Error Unknown';
		$track_errors = ini_get('track_errors');
		ini_set('track_errors', true);

		// Set user agent
		$version = new JVersion;
		ini_set('user_agent', $version->getUserAgent('Installer'));

		$http = JHttpFactory::getHttp();

		try
		{
			$response = $http->get($url);
		}
		catch (Exception $exc)
		{
			$response = null;
		}

		if (is_null($response))
		{
			JError::raiseWarning(42, JText::_('COM_COSTBENEFITPROJECTION_ERROR_DOWNLOAD_SERVER_CONNECT'));

			return false;
		}

		if (302 == $response->code && isset($response->headers['Location']))
		{
			return $this->downloadPackage($response->headers['Location']);
		}
		elseif (200 != $response->code)
		{
			if ($response->body === '')
			{
				$response->body = $php_errormsg;
			}

			JError::raiseWarning(42, JText::sprintf('COM_COSTBENEFITPROJECTION_ERROR_DOWNLOAD_SERVER_CONNECT', $response->body));

			return false;
		}

		if (isset($response->headers['Content-Disposition']))
		{
			$contentfilename = explode("\"", $response->headers['Content-Disposition']);
			$target = $contentfilename[1];
		}

		// Set the target path if not given
		if (!$target)
		{
			$target = $config->get('tmp_path') . '/' . $this->getFilenameFromURL($url);
		}
		else
		{
			$target = $config->get('tmp_path') . '/' . basename($target);
		}

		// Write buffer to file
		JFile::write($target, $response->body);

		// Restore error tracking to what it was before
		ini_set('track_errors', $track_errors);

		// bump the max execution time because not using built in php zip libs are slow
		@set_time_limit(ini_get('max_execution_time'));

		// Return the name of the downloaded package
		return basename($target);
	}
	
	/**
	 * Gets a file name out of a url
	 *
	 * @param   string  $url  URL to get name from
	 *
	 * @return  mixed   String filename or boolean false if failed
	 *
	 * @since   11.1
	 */
	protected function getFilenameFromURL($url)
	{
		if (is_string($url))
		{
			$parts = explode('/', $url);
			return $parts[count($parts) - 1];
		}
		return false;
	}
	
	/**
	* Save the data from the CSV to the database
	*
	* @param string  $package Paths to the uploaded package file
	*
	* @return  boolean false on failure
	*
	**/
	protected function setData($package)
	{
		// Get a db connection.
		$db = JFactory::getDbo();
		// get list of cause & risks
		// Create a new query object.
		$query = $db->getQuery(true);
		 
		// Select all disease names.
		// Order it by the disease_name field.
		$query->select($db->quoteName('disease_name'));
		$query->from($db->quoteName('#__costbenefitprojection_diseases'));
		$query->order('disease_name ASC');
		 
		// Reset the query using our newly populated query object.
		$db->setQuery($query);
		
		// set globals for import
		$get_list 		= $db->loadColumn();
		$dateSql 		= JFactory::getDate()->toSql();
		$ages 			= array("15-19 years","20-24 years","25-29 years","30-34 years","35-39 years","40-44 years","45-49 years","50-54 years","55-59 years","60-64 years");
		$types 			= array("death","yld");
		$genders 		= array("Males","Females");
		$totals			= array("Total (All Causes)");
		$selection_year = $this->importDate;
		if ($this->importType == 1){
			$importType = 'cause';
			$target_headers	= array("country_name"=>"country_name","year"=>"year","name"=>"cause_name","age_name"=>"age_name","sex"=>"sex","measure"=>"measure","rt_mean"=>"rt_mean");
		} elseif($this->importType == 2){
			$importType = 'risk';
			$target_headers	= array("country_name"=>"country_name","year"=>"year","name"=>"risk_name","age_name"=>"age_name","sex"=>"sex","measure"=>"measure","rt_mean"=>"rt_mean");
		}
		// get current user
		$user = JFactory::getUser();
		
		// set the data
		if(($handle = fopen($package['dir'], 'r')) !== false)
		{
			// get the first row, which contains the column-titles
			$header = fgetcsv($handle);
			// find the pointers
			if($header){
				$country_name	= array_search($target_headers['country_name'], $header);
				$year 			= array_search($target_headers['year'], $header);
				$name 			= array_search($target_headers['name'], $header);
				$age_name 		= array_search($target_headers['age_name'], $header);
				$sex 			= array_search($target_headers['sex'], $header);
				$measure 		= array_search($target_headers['measure'], $header);
				$rt_mean 		= array_search($target_headers['rt_mean'], $header);
			}
			
			if($name && is_array($get_list)){
				// loop through the file line-by-line
				while(($data = fgetcsv($handle)) !== false)
				{
					
					if($data[$year] == $selection_year && in_array($data[$age_name], $ages) && in_array($data[$measure], $types) && in_array($data[$sex], $genders) && in_array($data[$name], $get_list)){
						// fix age nameing
						$ageName = preg_replace("/[^0-9-]/", "", $data[$age_name]);
						// set the dataset
						$this->countryName			= $data[$country_name];
						$this->values[$data[$name]][$data[$sex]][$data[$measure]][$ageName] = (float)$data[$rt_mean];
						
					}
					
					if($data[$year] == $selection_year && in_array($data[$age_name], $ages) && in_array($data[$measure], $types) && in_array($data[$sex], $genders) && in_array($data[$name], $totals)){
						if ($importType == 'cause'){
							// fix age nameing
							$ageName = preg_replace("/[^0-9-]/", "", $data[$age_name]);
							// set the dataset for cause totals
							$this->totals['totals'][$selection_year][$data[$sex]][$data[$measure]][$ageName] = (float)$data[$rt_mean];
						}
					}
					
					unset($data);
				}
			}
			fclose($handle);
		}
		
		// save the data
		if($this->countryName){
			// Create a new query object.
			$query = $db->getQuery(true);
			$query->select('country_id,params');
			$query->from($db->quoteName('#__costbenefitprojection_countries'));
			$query->where($db->quoteName('country_name')." = ".$db->quote($this->countryName));
			// echo nl2br(str_replace('#__','giz_',$query)); die;
			// Reset the query using our newly populated query object.
			$db->setQuery($query);
			// get country data
			$result 	= $db->loadAssoc();
			$country_id = $result['country_id'];
			$set_params = json_decode($result['params'], true);
			
			if($country_id && is_array($this->values)){
				
				// set cause total params for this country import
				if ($importType == 'cause'){
					$set_params['totals'][$selection_year] = $this->totals['totals'][$selection_year];
					// add new totals to params
					$params = json_encode($set_params);
					
					// Save to Database
						
					// Create a new query object.
					$query = $db->getQuery(true);
					// delete cause where year and ref already set for this country.
					$fields = array(
						$db->quoteName('params') . ' = ' . $db->quote($params),
						$db->quoteName('modified_by') . ' = ' . $user->id,
						$db->quoteName('modified_on') . ' = ' . $db->quote($dateSql)
					);
					 
					// Conditions for which records should be updated.
					$conditions = array(
						$db->quoteName('country_id') . " = " . $country_id
					);
					// Prepare the update query. 
					$query->update($db->quoteName('#__costbenefitprojection_countries'))->set($fields)->where($conditions);
					// echo nl2br(str_replace('#__','giz_',$query)); die;
					// Set the query using our newly populated query object and execute it.
					$db->setQuery($query);
					 
					$result = $db->query();
				}
				
				foreach($this->values as $name => $null)
				{
					// set name to find id for
					$allias = JApplication::stringURLSafe($name);
					$allias = str_replace(array('.', ','), '' , $allias);
		
					// Create a new query object.
					$query = $db->getQuery(true);
					$query->select('disease_id');
					$query->from($db->quoteName('#__costbenefitprojection_diseases'));
					$query->where($db->quoteName('disease_alias')." = ".$db->quote($allias));
					// echo nl2br(str_replace('#__','giz_',$query)); die;
					// Reset the query using our newly populated query object.
					$db->setQuery($query);
					$ref_id = $db->loadResult();
					if (!$ref_id){
						continue;
					}			
					
					// setup params
					$params = array ($genders[0] => $this->values[$name][$genders[0]], $genders[1] => $this->values[$name][$genders[1]]);
					$params = json_encode($params);
					
					// check if data for this year is already set
					$query = $db->getQuery(true);
					$query->select('id');
					$query->from($db->quoteName('#__costbenefitprojection_defaultdata'));
					$query->where($db->quoteName('year') . " = " . $db->quote($selection_year));
					$query->where($db->quoteName('country_id') . " = " . $country_id);
					$query->where($db->quoteName('ref_id') . " = " . $ref_id);
					// Reset the query using our newly populated query object.
					$db->setQuery($query);
					$db->execute();
					$found = $db->getNumRows();
					if($found){
						$id = $db->loadResult();
						// Create a new query object.
						$query = $db->getQuery(true);
						// delete cause where year and ref already set for this country.
						$fields = array(
							$db->quoteName('params') . ' = ' . $db->quote($params),
							$db->quoteName('modified_by') . ' = ' . $user->id,
							$db->quoteName('modified_on') . ' = ' . $db->quote($dateSql)
						);
						 
						// Conditions for which records should be updated.
						$conditions = array(
							$db->quoteName('id') . " = " . $id
						);
						// Prepare the update query. 
						$query->update($db->quoteName('#__costbenefitprojection_defaultdata'))->set($fields)->where($conditions);
						// echo nl2br(str_replace('#__','giz_',$query)); die;
						// Set the query using our newly populated query object and execute it.
						$db->setQuery($query);
						 
						$result = $db->query();
						
					} else {
					
						// Create a new query object.
						$query = $db->getQuery(true);
						
						// Insert columns.
						$columns = array('year', 'ref_id', 'country_id', 'params', 'created_by', 'created_on');	
						// Insert values.
						$values = array($db->quote($selection_year), $ref_id, $country_id, $db->quote($params), $user->id, $db->quote($dateSql));	
					 
						// Prepare the insert query.
						$query
							->insert($db->quoteName('#__costbenefitprojection_defaultdata'))
							->columns($db->quoteName($columns))
							->values(implode(',', $values));
						 
						// Set the query using our newly populated query object and execute it.
						$db->setQuery($query);
						
						$result = $db->query();		
					}
				}
				return true;
			}
			return false;
		}
		return false;		
	}
	
	/**
	* Save the List of Causes & Risks from the CSV to the database
	*
	* @param string  $package Paths to the uploaded package file
	*
	* @return  boolean false on failure
	*
	**/
	protected function setList($package)
	{
		// set globals for import
		$dateSql 		= JFactory::getDate()->toSql();
		$target_headers	= array("cause"=>"Cause","ref"=>"Ref","category"=>"Category");
		// get current user
		$user = JFactory::getUser();
		
		// set the data
		if(($handle = fopen($package['dir'], 'r')) !== false)
		{
			// get the first row, which contains the column-titles
			$header = fgetcsv($handle);
			// find the pointers
			if($header){
				$cause		= array_search($target_headers['cause'], $header);
				$ref 		= array_search($target_headers['ref'], $header);
				$category 	= array_search($target_headers['category'], $header);
			}
			
			// loop through the file line-by-line
			while(($data = fgetcsv($handle)) !== false)
			{
				
				$categories[$data[$category]][$data[$cause]] = $data[$ref];
				
				unset($data);
			}
			fclose($handle);
		}
		
		$i = 1;
		// Get a db connection.
		$db = JFactory::getDbo();
		// save categories
		foreach($categories as $category => $causes){
			// set alias			
			$diseasecategory_alias = JApplication::stringURLSafe($category);
			$diseasecategory_alias = str_replace(array('.', ','), '' , $diseasecategory_alias);
			
			// Create a new query object.
			$query = $db->getQuery(true);
			
			// Insert columns.
			$columns = array('diseasecategory_name', 'diseasecategory_alias', 'access', 'published', 'created_by', 'created_on');
			 
			// Insert values.
			$values = array($db->quote($category), $db->quote($diseasecategory_alias), 1, 1, $user->id, $db->quote($dateSql));
			 
			// Prepare the insert query.
			$query
				->insert($db->quoteName('#__costbenefitprojection_diseasecategories'))
				->columns($db->quoteName($columns))
				->values(implode(',', $values));
			 
			// Set the query using our newly populated query object and execute it.
			$db->setQuery($query);
			$db->query();
			
			foreach($causes as $name => $ref){
				// set alias			
				$disease_alias = JApplication::stringURLSafe($name);
				$disease_alias = str_replace(array('.', ','), '' , $disease_alias);
				// Create a new query object.
				$query = $db->getQuery(true);
				
				// Insert columns.
				$columns = array('disease_name', 'disease_alias', 'ref', 'diseasecategory_id', 'access', 'published', 'created_by', 'created_on');
				 
				// Insert values.
				$values = array($db->quote($name), $db->quote($disease_alias), $db->quote($ref), $i, 1, 1, $user->id, $db->quote($dateSql));
				 
				// Prepare the insert query.
				$query
					->insert($db->quoteName('#__costbenefitprojection_diseases'))
					->columns($db->quoteName($columns))
					->values(implode(',', $values));
				 
				// Set the query using our newly populated query object and execute it.
				$db->setQuery($query);
				$db->query();
			}
			$i++;
		}
		
		return true;
		
	}
}