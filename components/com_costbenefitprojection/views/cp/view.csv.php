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

jimport( 'joomla.application.component.view' );
jimport( 'joomla.application.component.helper' );

class CostbenefitprojectionViewCp extends JView
{
	protected $item;

	public function display($tpl = null)
	{
		if (!JFactory::getUser()->id) {
			throw new Exception(JText::_('COM_COSTBENEFITPROJECTION_ACCESS_DENIED'), 403);
		} else {
			// Get data from the model
			$this->item = $this->get('Excel');
			
			// Check for errors.
			if (count($errors = $this->get('Errors'))) {
				JError::raiseError(500, implode("\n", $errors));
				return false;
			}
			
			
			$this->_prepareDocument();
		}
	}
		
	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{		
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();
		
		// Set document properties
		$objPHPExcel->getProperties()->setCreator("GIZ")
									 ->setLastModifiedBy($this->item['user'])
									 ->setTitle($this->item['title'])
									 ->setSubject($this->item['tabName'])
									 ->setDescription("Cost Benefit Projection Tool - Table Result")
									 ->setKeywords("GIZ")
									 ->setCategory("Results");
		
		// Some styles
		$headerStyles = array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '1171A3'),
				'size'  => 12,
				'name'  => 'Verdana'
		));
		$sideStyles = array(
			'font'  => array(
				'bold'  => true,
				'color' => array('rgb' => '444444'),
				'size'  => 12,
				'name'  => 'Verdana'
		));
		$normalStyles = array(
			'font'  => array(
				'color' => array('rgb' => '444444'),
				'size'  => 11,
				'name'  => 'Verdana'
		));
		
		// Add some data
		$rows = $this->item['rows'];
		$i = 1;
		foreach ($rows as $array){
			$a = 'A';
			foreach ($array as $value){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue($a.$i, $value);
				if ($i == 1){
					$objPHPExcel->getActiveSheet()->getColumnDimension($a)->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getStyle($a.$i)->applyFromArray($headerStyles);
					$objPHPExcel->getActiveSheet()->getStyle($a.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				} elseif ($a == 'A'){
					$objPHPExcel->getActiveSheet()->getStyle($a.$i)->applyFromArray($sideStyles);
				} else {
					$objPHPExcel->getActiveSheet()->getStyle($a.$i)->applyFromArray($normalStyles);
					$objPHPExcel->getActiveSheet()->getStyle($a.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
				}
				$a++;
			}
			$i++;
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle($this->item['tabName']);
		
		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);
		
		
		// Redirect output to a client's web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$this->item['fileName'].'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');
		
		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
		 
	}
}

?>