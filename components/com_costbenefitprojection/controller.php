<?php
/**
* 
* 	@version 	1.0.0 July 24, 2013
* 	@package 	Cost Benefit Projection Tool Application
* 	@author  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Copyright (C) 2013 German Development Cooperation <http://www.giz.de>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/
defined( '_JEXEC' ) or die;

jimport('joomla.application.component.controller');

class CostbenefitprojectionController extends JController
{
	public function go()
	{
		// Get visitors info
		$data_id = JRequest::getInt('id');
		$backUrl = JURI::base() ;
		$data_url_md5 = JRequest::getVar('url');
		$visitor_ip = getenv('REMOTE_ADDR');
		$date = date("Y-m-d"); 
		
		$qvisitor_ip = "'".$visitor_ip."'";
		$qdate = "'".$date."'";
		$qdata_url_md5 = "'".$data_url_md5."'";
		$db = JFactory::getDBO();
		
		// Get datas
		$query = $db->getQuery(true);
		$query->select('data_url')
		->from('#__costbenefitprojection_datas')
		->where('published =1 AND data_access =1 AND data_url_md5 = '.$qdata_url_md5.' AND data_id = '.$data_id);
		
		// debug the query  
		// echo nl2br(str_replace('#__','jos_',$query)); die;
		
		$db->setQuery($query);
		$data_url = $db->loadObjectList();
		
		// debug datas array
		// print_r($data_url); die;
		if ($data_url) {			
			
			$data_url_out = $data_url[0]->data_url;
			$data_url_raw =  parse_url($data_url[0]->data_url);
			$data_url_base = preg_replace('#^www\.(.+\.)#i', '$1', $data_url_raw['host']);
			
			// debug datas array
			// print_r($data_url_out); die;
			// print_r($data_url_base); die;
				
			$query = $db->getQuery(true);
			$query->select('ref_md5')
			->from('#__costbenefitprojection_check')
			->where('dates LIKE '.$qdate.' AND visitor_ip LIKE '.$qvisitor_ip.' AND in_out = 1 AND ref_md5 = '.$qdata_url_md5);
			
			// debug the query
			// echo nl2br(str_replace('#__','jos_',$query)); die;
			
			$db->setQuery($query);
			$old_out = $db->loadObjectList();
			if ($old_out){ header('Location: '.$data_url_out); } 
			else { 	
				// count the click	
				$query = $db->getQuery(true);
				 
				// Fields to update.
				$fields = 'data_hits_out = data_hits_out +1, data_hits_out_all = data_hits_out_all +1';
				 
				// Conditions for which records should be updated.
				$conditions = "data_id = ".$data_id;
				 
				$query->update($db->quoteName('#__costbenefitprojection_datas'))->set($fields)->where($conditions);
				 
				$db->setQuery($query);
					
				// debug the query  
				// echo nl2br(str_replace('#__','jos_',$query)); die; 
				
				try {
					$result = $db->query(); // Use $db->execute() for Joomla 3.0.
				} catch (Exception $e) {
					// Catch the error.
				}
				
				// Update Data Base with click_out
				// Create and populate an object.
				$visit = new stdClass();
				$visit->visitor_ip = $visitor_ip;
				$visit->ref_md5 = $data_url_md5;
				$visit->ref = $data_url_base;
				$visit->dates = $date;
				$visit->in_out = 1;
									
					// debug visit array
					// print_r($visit);
				try {
				    // Insert the object into table.
				    $result = JFactory::getDbo()->insertObject('#__costbenefitprojection_check', $visit);
				} catch (Exception $e) {
				    // catch any errors.
				    }
			
				// go to datas website
				header('Location: '.$data_url_out);
			}
		} else {
			header('Location: '.$backUrl);
			}
		
	}
}