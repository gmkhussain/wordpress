if(jQuery('#esg-vc-input-alias').is(':visible')){ //only open if edit window is visible

	esg_tiny_reset_all();

	var ess_grid_is_vc = true; //set for the saving that we are visual composer

	jQuery('.wpb-element-edit-modal').hide(); //hide the normal VC window and use own (old vc version)
	jQuery('#vc_properties-panel').hide(); //hide the normal VC window and use own (new vc version)
	
	var ess_grid_vc_variables = {};
	
	ess_grid_vc_variables['alias'] = jQuery('#esg-vc-input-alias').val();
	ess_grid_vc_variables['settings'] = jQuery('#esg-vc-input-settings').val().replace(/\'/g, '"');
	ess_grid_vc_variables['layers'] = jQuery('#esg-vc-input-layers').val().replace(/\'/g, '"');
	ess_grid_vc_variables['special'] = jQuery('#esg-vc-input-special').val();
	
	jQuery('#ess-grid-tiny-dialog-step-1').show();
	jQuery('#ess-grid-tiny-dialog-step-2').hide();
	jQuery('#ess-grid-tiny-dialog-step-3').hide();
	
	jQuery('#ess-grid-tiny-mce-dialog').dialog({
		id       : 'ess-grid-tiny-mce-dialog',
		title	 : eg_lang.shortcode_generator,
		width    : 720,
		height   : 'auto'
	});
	
	if(ess_grid_vc_variables['special'] !== ''){ //special
		
		esg_create_by_predefined = ess_grid_vc_variables['special'];
		
		//special stuff here
		if(ess_grid_vc_variables['alias'] !== ''){
			jQuery('select[name="ess-grid-tiny-existing-settings"] option').each(function(){
				if(jQuery(this).val() == ess_grid_vc_variables['alias']) jQuery(this).attr('selected', true);
			});
			
			if(ess_grid_vc_variables['settings'] !== ''){
				var sett = jQuery.parseJSON(ess_grid_vc_variables['settings']);
				
				if(typeof(sett['max-entries']) !== 'undefined')
					jQuery('input[name="ess-grid-tiny-max-entries"]').val(sett['max-entries']);
			}
		}
		
		jQuery('#eg-goto-step-2').click();
		
	}else if(ess_grid_vc_variables['layers'] != '' && ess_grid_vc_variables['settings'] != '' || ess_grid_vc_variables['layers'] != '' && ess_grid_vc_variables['alias'] != ''){
		
		var ess_shortcode = '[ess_grid ';
		
		if(ess_grid_vc_variables['alias'] !== '')
			ess_shortcode += ' alias="'+ess_grid_vc_variables['alias']+'"';
			
		if(ess_grid_vc_variables['settings'] !== '')
			ess_shortcode += " settings='"+ess_grid_vc_variables['settings']+"'";
			
		if(ess_grid_vc_variables['layers'] !== '')
			ess_shortcode += " layers='"+ess_grid_vc_variables['layers']+"'";
			
		ess_shortcode += '][/ess_grid]';
		
		jQuery('input[name="eg-shortcode-analyzer"]').val(ess_shortcode);
		jQuery('#eg-shortcode-do-analyze').click();
		
	}else if(ess_grid_vc_variables['alias'] !== '' && ess_grid_vc_variables['special'] == ''){ //only grid with alias
		
		jQuery('select[name="ess-grid-existing-grid"] option').each(function(){
			if(jQuery(this).val() == ess_grid_vc_variables['alias']){
				jQuery(this).attr('selected', true);
			}
		});
		
	}else{ /*seems like a new grid  */ }
	

}