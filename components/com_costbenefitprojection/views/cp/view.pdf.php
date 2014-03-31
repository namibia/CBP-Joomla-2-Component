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
			$this->item = $this->get('Pdf');
			
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
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu();
		$pathway 	= $app->getPathway();
		$title		= null;
		// set image size to it A4 page
		list($width, $height, $type, $attr) = getimagesize($this->item['image']);
		if($height <= 670){
			$PDForientation = 'landscape';
			$setWidth = $width;
			$setHeight = $height;
		} else {
			$orientation = false;
			if($height <= 940){
				if($width <= 670){
					$setWidth = $width;
					$setHeight = $height;
				} else {
					$setWidth = 670;
					$setHeight = (670/$width)*$height;
				}
			} else {
				if($width <= 670){
					$setHeight = 940;
					$setWidth = (940/$height)*$width;
				} else {
					$testHeight = $height/940;
					$testWidth = $width/670;
					if ($testHeight > $testWidth){
						$setHeight = 940;
						$setWidth = (940/$height)*$width;
					} else {
						$setWidth = 670;
						$setHeight = (670/$width)*$height;
					}
				}
			}
		}
		// setup PDF values
		$PDFheading_one = JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_HEADING_ONE');
		$PDFheading_two = JText::_('COM_COSTBENEFITPROJECTION_CT_PDF_HEADING_TWO');
		$PDFname = str_replace(array( '(', ')'), '', $this->item['text']);
		$PDFname = preg_replace('/\s+/', '_', $PDFname).'_'.preg_replace('/\s+/', '_', $this->item['date']);
		$PDFcontent = '<!DOCTYPE HTML>
						<html lang="en-gb" dir="ltr" >
							<head>
								<meta charset="utf-8">
								<meta http-equiv="content-type" content="text/html; charset=utf-8" />
								<meta name="generator" content="Vast Development Metod" />
								<title>GIZ</title>
							</head>
							<body>
								<div id="header">
										<table>
											<tbody>
											<tr>
												<td>'.$this->item['user'].' | '.$PDFheading_one.' '.$this->item['text'].' '.$this->item['date'].'</td>
												<td style="text-align: right;">'.$PDFheading_two.'</td>
											</tr>
											</tbody>
										</table>
									</div>
									<div id="footer">
									<div class="page-number"></div>
								</div>
								<a href="'. JURI::base() . '" target="_blank" ><img src="'.$this->item['image'].'" width="'.$setWidth.'" height="'.$setHeight.'"/></a>
							</body>
						</html>';
		// set download file name
		$this->document->setName($PDFname);
		// set data for PDF
		$this->document->setData($PDFcontent,$PDForientation,$this->item['image']); 
	
		 
	}
}