<?php
/**
* 
* 	@version 	2.0.0 March 13, 2014
* 	@package 	Staff Health Cost Benefit Projection
* 	@author  	Vast Development Method <http://www.vdm.io>
* 	@copyright	Copyright (C) 2014 German Development Cooperation <http://www.giz.de>
* 	@license	GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
*
**/
defined( '_JEXEC' ) or die;

jimport('joomla.application.component.modeladmin');

class CostbenefitprojectionModelIntervention extends JModelAdmin
{
	public function getTable($type = 'Intervention', $prefix = 'CostbenefitprojectionTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_costbenefitprojection.intervention', 'intervention', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form)) 
		{
			return false;
		}
		
		// Get the intervention_id
		$input = JFactory::getApplication()->input;
		$intervention_id = $input->getInt('intervention_id');
		
		if (isset($intervention_id)){
			$selected_result = $this->getSelected($intervention_id);
		}
		
		// setup the risk fields and add the set values to it.
		if (is_array($selected_result['disease_id'])){
			$fieldsLoad .= '<field type="spacer" name="title_disease" label="COM_COSTBENEFITPROJECTION_FIELD_ALL_DISEASES_NAME_LABEL" />';
			$fieldsLoad .= '<field type="spacer" name="line_disease1" hr="true"/>';
			foreach ($selected_result['disease_id'] as $id){
				if(!is_array($id)){
					$fieldsLoad .=
					'
						<field type="spacer" name="disease_title_'.$id.'" label="Settings for '.$this->getDiseasename($id).'"/>
								<field 
									name="disease_cpe_'.$id.'"
									type="text"
									label="COM_COSTBENEFITPROJECTION_INTERVENTION_COST_PER_EMPLOYEE_LABEL"
									description="COM_COSTBENEFITPROJECTION_INTERVENTION_COST_PER_EMPLOYEE_DESC"
									size="20"
									required="true"
								/>
								<field name="disease_mbr_'.$id.'"
									type="text" 
									label="COM_COSTBENEFITPROJECTION_INTERVENTION_MORB_REDUCTION_LABEL" 
									description="COM_COSTBENEFITPROJECTION_INTERVENTION_MORB_REDUCTION_DESC" 
									size="20"
									required="true" 
								/>
								<field name="disease_mtr_'.$id.'"
									type="text"
									label="COM_COSTBENEFITPROJECTION_INTERVENTION_MORTALITY_REDUCTION_LABEL"
									description="COM_COSTBENEFITPROJECTION_INTERVENTION_MORTALITY_REDUCTION_DESC"
									size="20"
									required="true" 
								/>
						<field type="spacer" name="disease_line_'.$id.'" hr="true" />
					';
				}
			} 
		} 
		
		// setup the risk fields and add the set values to it.
		if (is_array($selected_result['risk_id'])){
			$fieldsLoad .= '<field type="spacer" name="title_risk" label="COM_COSTBENEFITPROJECTION_FIELD_ALL_RISK_NAME_LABEL" />';
			$fieldsLoad .= '<field type="spacer" name="line_risk1" hr="true"/>';
			foreach ($selected_result['risk_id'] as $id){
				if(!is_array($id)){
					$fieldsLoad .=
					'
						<field type="spacer" name="risk_title_'.$id.'" label="Settings for '.$this->getRiskname($id).'"/>
								<field 
									name="risk_cpe_'.$id.'"
									type="text"
									label="COM_COSTBENEFITPROJECTION_INTERVENTION_COST_PER_EMPLOYEE_LABEL"
									description="COM_COSTBENEFITPROJECTION_INTERVENTION_COST_PER_EMPLOYEE_DESC"
									size="20"
									required="true"
								/>
								<field name="risk_mbr_'.$id.'"
									type="text" 
									label="COM_COSTBENEFITPROJECTION_INTERVENTION_MORB_REDUCTION_LABEL" 
									description="COM_COSTBENEFITPROJECTION_INTERVENTION_MORB_REDUCTION_DESC" 
									size="20"
									required="true"
								/>
						<field type="spacer" name="risk_line_'.$id.'" hr="true" />
					';
				}
			} 
		} 
		
		if ($fieldsLoad){
			// set fields to xml object			
			$element = new SimpleXMLElement('
			<fields name="params">
				<fieldset name="params" label="COM_COSTBENEFITPROJECTION_DR_SETTINGS_FIELDSET_LABEL">
					'.$fieldsLoad.'
				</fieldset>
			</fields>');
		} 
		// set field to form object
		$form->setField($element);
		
		$dbData = $selected_result['params'];
		
		// load set disease values back into form		
		if (is_array($selected_result['disease_id'])){
			foreach ($selected_result['disease_id'] as $id){
					$mtr = 'disease_mtr_'.$id;
					$cpe = 'disease_cpe_'.$id;
					$mbr = 'disease_mbr_'.$id;
					$form->setValue($mtr,'params',$dbData->$mtr);
					$form->setValue($cpe,'params',$dbData->$cpe);
					$form->setValue($mbr,'params',$dbData->$mbr);
			}
		} 
		
		// load set risk values back into form	
		if (is_array($selected_result['risk_id'])){
			foreach ($selected_result['risk_id'] as $id){
					$cpe = 'risk_cpe_'.$id;
					$mbr = 'risk_mbr_'.$id;
					$form->setValue($cpe,'params',$dbData->$cpe);
					$form->setValue($mbr,'params',$dbData->$mbr);
			}
		}
		
		return $form;
	}
	
	protected function loadFormData()
	{
		$data = JFactory::getApplication()->getUserState('com_costbenefitprojection.edit.intervention.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		
		// check point to see if the user is allowed to edit this item
		$user = JFactory::getUser();
		$userIs = $this->userIs($user->id);
		$itemOwner = $this->userIs($data->owner);
		
		if($itemOwner['id']){
			if ($userIs['type'] == 'client'){
				if ($userIs['country'] == $itemOwner['country']){
					if ($itemOwner['id'] != $userIs['id']){
						$this->checkin();
						throw new Exception('ERROR! this item does not belong to you, so you may not edit it. <a href="javascript:history.go(-1)">Go back</a>');
					} 
				} else {
					$this->checkin();
					throw new Exception('ERROR! this item does not belong to you, so you may not edit it. <a href="javascript:history.go(-1)">Go back</a>');
				}
			} else {
				$this->checkin();
				throw new Exception('ERROR! <a href="javascript:history.go(-1)">Go back</a>');
			}
		}
		
		return $data;
	}
	
	protected function getSelected($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('cluster_id');
		$query->from('#__costbenefitprojection_interventions');;
		$query->where('intervention_id = \''.$id.'\'');

		$db->setQuery($query);

		$result = $db->loadAssoc();
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		if (empty($result["cluster_id"])){
			$selectedResult = $this->getSelectedDiseaseRisk($id);
		} else {
			$cluster_id = json_decode($result["cluster_id"]);
			$i = 0;
			foreach ($cluster_id as $c_id){
				$selected[$i]  = $this->getSelectedDiseaseRisk($c_id);
				$i++;
			}
			$selectedResult['cluster'] = true;
			$own = $this->getSelectedDiseaseRisk($id);
			
			// removing all that are already set
			foreach ($selected as $key => $value){
				if (is_array($own["disease_id"]) && is_array($value["disease_id"])){
					$has = (count(array_intersect($own["disease_id"], $value["disease_id"]))) ? true : false;
					if($has){
						foreach ($value["disease_id"] as $pointer => $disease){
							if (in_array($disease, $own["disease_id"])){
								unset($selected[$key]["disease_id"][$pointer]);
								$cpe = "disease_cpe_".$disease;
								$mbr = "disease_mbr_".$disease;
								$mtr = "disease_mtr_".$disease;
								unset($selected[$key]["params"]->$cpe);
								unset($selected[$key]["params"]->$mbr);
								unset($selected[$key]["params"]->$mtr);
							}
						}
					}
				}
				if (is_array($own["risk_id"]) && is_array($value["risk_id"])){
					$has = (count(array_intersect($own["risk_id"], $value["risk_id"]))) ? true : false;
					if($has){
						foreach ($value["risk_id"] as $pointer => $disease){
							if (in_array($disease, $own["risk_id"])){
								unset($selected[$key]["risk_id"][$pointer]);
								$cpe = "risk_cpe_".$disease;
								$mbr = "risk_mbr_".$disease;
								$mtr = "risk_mtr_".$disease;
								unset($selected[$key]["params"]->$cpe);
								unset($selected[$key]["params"]->$mbr);
								unset($selected[$key]["params"]->$mtr);
							}
						}
					}
				}  
			}
			
			// now removing duplicates
			$selectedResult = array();
			$selectedResult["disease_id"] = array();
			$selectedResult["risk_id"] = array();
			$selectedResult["params"] = array();
			foreach ($selected as $key => $value){
				foreach ($selected as $key_i => $value_i){
					if (is_array($value["disease_id"]) && is_array($value_i["disease_id"])){
						$selectedResult["disease_id"] =  array_merge($value["disease_id"], $value_i["disease_id"],$selectedResult["disease_id"]);
					}
					if (is_array($value["risk_id"]) && is_array($value_i["risk_id"])){
						$selectedResult["risk_id"] =  array_merge($value["risk_id"], $value_i["risk_id"],$selectedResult["risk_id"]);
					}
				}
				if(is_object($value["params"])){
					$selectedResult["params"] =  array_merge_recursive((array) $value["params"], (array) $selectedResult["params"]);
				}
			}
			// now add the own values
			if (is_array($selectedResult["disease_id"]) && is_array($own["disease_id"])){
				$selectedResult["disease_id"] = array_merge($own["disease_id"],$selectedResult["disease_id"]);
			}
			if (is_array($selectedResult["risk_id"]) && is_array($own["risk_id"])){
				$selectedResult["risk_id"] = array_merge($own["risk_id"],$selectedResult["risk_id"]);
			}
			if (is_array($selectedResult["params"]) && is_object($own["params"])){
				$selectedResult["params"] = array_merge_recursive((array)$own["params"],$selectedResult["params"]);
			}
			
			// now sort the data and merge
			$selectedResult_risk = array_unique($selectedResult["risk_id"]);
			if(sort($selectedResult_risk)){
				$selectedResult["risk_id"]= array();
				foreach ($selectedResult_risk as $risk_id){
					$selectedResult["risk_id"][] = $risk_id;
				}
			}
			$selectedResult_disease = array_unique($selectedResult["disease_id"]);
			if(sort($selectedResult_disease)){
				$selectedResult["disease_id"] = array();
				foreach ($selectedResult_disease as $disease_id){
					$selectedResult["disease_id"][] = $disease_id;
				}
			}
			foreach ($selectedResult["params"] as $key => $value){
				if (is_array($value)){
					$i=0;
					foreach ($value as $val){
						if (!is_array($val)){
							if ($i == 0){
								$selectedResult["params"][$key] = $val;
							} else {
								$selectedResult["params"][$key] .= ' & '.$val;
							}
							$i++;
						}
					}
				}
			}
			$selectedResult["params"] = (object)$selectedResult["params"];
		}
		//echo '<pre>'; var_dump($own);die;
		return $selectedResult;
	}
	
	protected function getSelectedDiseaseRisk($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_id, risk_id, params');
		$query->from('#__costbenefitprojection_interventions');;
		$query->where('intervention_id = \''.$id.'\'');

		$db->setQuery($query);

		$result = $db->loadAssoc();
		
		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		
		if (!empty($result["disease_id"])){
			$selectedResult["disease_id"] = json_decode($result["disease_id"]);
		}
		if (!empty($result["risk_id"])){
			$selectedResult["risk_id"] = json_decode($result["risk_id"]);
		}
		if (!empty($result["params"])){
			$selectedResult["params"] = json_decode($result["params"]);
		}
				
		return $selectedResult;
	}
	
	protected function getDiseasename($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('disease_alias');
		$query->from('#__costbenefitprojection_diseases');;
		$query->where('disease_id = \''.$id.'\'');

		$db->setQuery($query);

		$name = $db->loadResult();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		$name = ucwords(str_replace('-',' ',$name));
		return $name;
	}
	
	protected function getRiskname($id)
	{
		$options = array();

		$db = JFactory::getDbo();
		$query = $db->getQuery(true);

		$query->select('risk_alias');
		$query->from('#__costbenefitprojection_risk');;
		$query->where('risk_id = \''.$id.'\'');

		$db->setQuery($query);

		$name = $db->loadResult();

		if ($db->getErrorNum()) {
			JError::raiseWarning(500, $db->getErrorMsg());
		}
		$name = ucwords(str_replace('-',' ',$name));
		return $name;
	}
	
	public function getUser()
	{
		// get user
		$user = JFactory::getUser();
		
		return $user = $this->userIs($user->id);
	}
	
	/**
	 * Get internal user info in relation to this application.
	 *
	 * @return an associative array
	 *
	 */
	public function userIs($id)
	{
		$userIs = array();
		$userIs['id'] = $id;
		$userIs['country'] = JUserHelper::getProfile($id)->gizprofile[country];
		$userIs['groups'] = JUserHelper::getUserGroups($id);
		$userIs['name'] = JFactory::getUser($id)->name;
		
		$AppGroups['admin'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('admin');
		$AppGroups['country'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('country');
		$AppGroups['service'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('service');
		$AppGroups['client'] = &JComponentHelper::getParams('com_costbenefitprojection')->get('client');

		$admin_user = (count(array_intersect($AppGroups['admin'], $userIs['groups']))) ? true : false;
		$country_user = (count(array_intersect($AppGroups['country'], $userIs['groups']))) ? true : false;
		$service_user = (count(array_intersect($AppGroups['service'], $userIs['groups']))) ? true : false;
		$client_user = (count(array_intersect($AppGroups['client'], $userIs['groups']))) ? true : false;
		
		if ($admin_user){
			$userIs['type'] = 'admin';
		} elseif ($country_user){
			$userIs['type'] = 'country';
		} elseif ($service_user){
			$userIs['type'] = 'service';
		} elseif ($client_user) {
			$userIs['type'] = 'client';
			$userIs['service'] = JUserHelper::getProfile($id)->gizprofile[serviceprovider];
		}
		
		return $userIs;
	}
}