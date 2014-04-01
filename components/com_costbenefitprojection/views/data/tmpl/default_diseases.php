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

// Set Disease
$diseases = $this->item["data"]["diseasedata"];

?>
<?php if ($this->item["noData"]["disease"]["nr"] > 0):?>

<div class="uk-alert uk-alert-warning" data-uk-alert>
    <a href="" class="uk-alert-close uk-close"></a>
    <strong><?php echo JText::_('COM_COSTBENEFITPROJECTION_WARNING');  ?> </strong> 
        <?php $i = 0; foreach ($this->item["noData"]["disease"]["name"] as $id => $name): ?>
            <?php if ($i == 0): ?>
                <?php echo '( '.$name; ?>                        
            <?php else: ?>
                <?php echo '- '.$name; ?>
            <?php endif; ?>
        <?php  $i++; endforeach; ?>
    )<strong> <?php echo JText::_('COM_COSTBENEFITPROJECTION_WARNING_DOES_NOT_HAVE_DATA');  ?></strong>
</div>
<?php endif; ?>
<nav class="uk-navbar">
    <div class="uk-navbar-content uk-navbar-flip">
    <form class="uk-form uk-margin-remove uk-display-inline-block form-validate" action="index.php?option=com_costbenefitprojection&view=diseasedata&layout=edit<?php echo $this->temp; ?>" method="post" name="adminForm">
        <button type="button" class="uk-button uk-button-small" style="margin-bottom: 10px;" onclick="Joomla.submitbutton('diseasedata.add')" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_ADD_DISEASEDATA');  ?></button>
        <input type="hidden" name="task" value="" />
    </form>
	</div>
</nav>
<div class="uk-width-1-1">
    <div class="uk-float-left">
        <p>
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_SEARCH');  ?>: <input id="filter1" type="text"/>
            <a class="uk-button uk-button-small clear-filter" href="#clear" title="clear filter"><?php echo JText::_('COM_COSTBENEFITPROJECTION_CLEAR');  ?></a>
        </p>
    </div>
    <div class="uk-float-right">
        <p>
            <?php echo JText::_('COM_COSTBENEFITPROJECTION_STATUS');  ?>: <select id="status1" class="filter-status">
            <option value=""><?php echo JText::_('COM_COSTBENEFITPROJECTION_ALL');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED');  ?></option>
            <option value="<?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED_LOWERCASE');  ?>"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED');  ?></option>
            </select>
        </p>
    </div>
</div>

<div class="uk-clearfix"></div>

<table class="table data one metro-blue" data-filter="#filter1" data-page-size="12">
    
    <thead>
    <tr>
        <th data-toggle="true"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_NAME_LABEL'); ?></th>
        <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_CATEGORY_LABEL'); ?></th>
        <th data-hide="phone"><?php echo JText::_('COM_COSTBENEFITPROJECTION_OWNER'); ?></th>
        
        <th data-hide="all">
                <?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_IRM_LABEL');?>
        </th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_IRF_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_IRF_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_RPIO_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_RPIO_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_MRM_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_MRM_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_MRF_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_MRF_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_ISFM_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_ISFM_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_ISFF_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_ISFF_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_PSFM_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_PSFM_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_PSFF_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_PSFF_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_HSFM_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_HSFM_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_HSFF_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_HSFF_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_AMFM_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_AMFM_DESC');
                        
                        ?></th>
        <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_AMFF_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_AMFF_DESC');
                        
                        ?></th>
       <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_DURATION_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_AMFF_DESC');
                        
                        ?></th>
       <th data-hide="all"><?php echo JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_HS_LABEL'); 
                        $td = JText::_('COM_COSTBENEFITPROJECTION_FIELD_DISEASESDATA_AMFF_DESC');
                        
                        ?></th>
        <th data-hide="phone"><?php echo JText::_('JSTATUS'); ?></th>
    </tr>
    </thead>
    
    <tbody>
	<?php if ($diseases) : ?>
		<?php foreach ($diseases as $i => $item): ?>
            <tr id="itemlink" class="row<?php echo $i % 2 ?>">
                <td>
                	<?php if ( $item["owner_id"] == $this->user['id'] ) : ?>
                        <?php if ($item["checked_out"]) : ?>
                            <?php
                                $d 	= '<i class="uk-icon-lock"></i>';
                                $tt = 'Checked out';
                                $td = 'by '.JFactory::getUser($item["checked_out"])->name;
                                echo JHTML::tooltip($td, $tt, '', $d );
                            ?>
                            <?php if ($item["checked_out"] == $this->user['id']) : ?>
                                <a href="index.php?option=com_costbenefitprojection&task=diseasedata.edit&id=<?php echo $item["id"]; ?><?php echo $this->temp; ?>" title="edit">
                                    <?php echo $this->escape($item["disease_name"]); ?>
                                </a>
                            <?php else: ?>
                                <?php echo $this->escape($item["disease_name"]); ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <a href="index.php?option=com_costbenefitprojection&task=diseasedata.edit&id=<?php echo $item["id"]; ?><?php echo $this->temp; ?>" title="edit">
                                <?php echo $this->escape($item["disease_name"]); ?>
                            </a>
                        <?php endif; ?>
                    <?php else: ?>
                    	<?php echo $this->escape($item["disease_name"]); ?>
                    <?php endif; ?>
                </td>
                <td class="center"><?php echo $this->escape($item["diseasecategory_name"]); ?></td>
                <td class="center"><?php echo $item["owner"]; ?></td>
                <td class="center"><?php echo $item["incidence_rate_male"]; ?></td>
                <td class="center"><?php echo $item["incidence_rate_female"]; ?></td>
                <td class="center"><?php echo $item["relative_proportion"]; ?></td>
                <td class="center"><?php echo $item["mortality_rate_male"]; ?></td>
                <td class="center"><?php echo $item["mortality_rate_female"]; ?></td>
                <td class="center"><?php echo $item["incidence_scaling_factor_male"]; ?></td>
                <td class="center"><?php echo $item["incidence_scaling_factor_female"]; ?></td>
                <td class="center"><?php echo $item["presenteeism_scaling_factor_male"]; ?></td>
                <td class="center"><?php echo $item["presenteeism_scaling_factor_female"]; ?></td>
                <td class="center"><?php echo $item["hospital_scaling_factor_male"]; ?></td>
                <td class="center"><?php echo $item["hospital_scaling_factor_female"]; ?></td>
                <td class="center"><?php echo $item["absenteeism_multiplication_factor_male"]; ?></td>
                <td class="center"><?php echo $item["absenteeism_multiplication_factor_female"]; ?></td>
                <td class="center"><?php echo $item["duration"]; ?></td>
                <td class="center"><?php echo $item["hospital_stay"]; ?></td>
                <?php if ($item["published"] == 1):?>
                <td class="center"  data-value="1">
                    <span class="status-metro status-published" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED');  ?>">
                        <span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_PUBLISHED');  ?></span>
                        <i class="uk-icon-check"></i>
                    </span>
                </td>
                <?php elseif ($item["published"] == 0): ?>
                <td class="center"  data-value="2">
                    <span class="status-metro status-inactive" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE');  ?>">
                        <span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_INACTIVE');  ?></span>
                        <i class="uk-icon-ban"></i>
                    </span>
                </td>
                <?php elseif ($item["published"] == 2): ?>
                <td class="center"  data-value="3">
                    <span class="status-metro status-archived" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED');  ?>">
                        <span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_ARCHIVED');  ?></span>
                        <i class="uk-icon-archive"></i>
                    </span>
                </td>
                <?php elseif ($item["published"] == -2): ?>
                <td class="center"  data-value="4">
                    <span class="status-metro status-trashed" title="<?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED');  ?>">
                        <span style="display:none;"><?php echo JText::_('COM_COSTBENEFITPROJECTION_TRASHED');  ?></span>
                        <i class="uk-icon-trash-o"></i>
                    </span>
                </td>
                <?php endif; ?>      
            </tr>
        <?php endforeach ?>
	<?php else: ?>
        <tr>
            <td colspan="19" ><?php echo JText::_('COM_COSTBENEFITPROJECTION_NO_DATA'); ?></td>
        </tr>
    <?php endif ?>
    </tbody>
    
    <tfoot class="hide-if-no-paging">
        <tr>
          <td colspan="19">
            <div class="pagination pagination-centered"></div>
          </td>
        </tr>
     </tfoot>
</table>