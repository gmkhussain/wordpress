<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Essential_Grid
 * @author    ThemePunch <info@themepunch.com>
 * @link      http://www.themepunch.com/essential/
 * @copyright 2014 ThemePunch
 */
	$grid = false;

	$base = new Essential_Grid_Base();
	$nav_skin = new Essential_Grid_Navigation();
	$wa = new Essential_Grid_Widget_Areas();
	$meta = new Essential_Grid_Meta();

	$isCreate = $base->getGetVar('create', 'true');

	$title = __('Create New Ess. Grid', EG_TEXTDOMAIN);
	$save = __('Save Grid', EG_TEXTDOMAIN);

	$layers = false;

	if(intval($isCreate) > 0){ //currently editing
		$grid = Essential_Grid::get_essential_grid_by_id(intval($isCreate));
		if(!empty($grid)){
			$title = __('Settings', EG_TEXTDOMAIN);

			$layers = $grid['layers'];
		}
	}

	$postTypesWithCats = $base->getPostTypesWithCatsForClient();
	$jsonTaxWithCats = $base->jsonEncodeForClientSide($postTypesWithCats);

	$base = new Essential_Grid_Base();

	$pages = get_pages(array('sort_column' => 'post_name'));

	$post_elements = $base->getPostTypesAssoc();

	$postTypes = $base->getVar($grid['postparams'], 'post_category', 'post');
	$categories = $base->setCategoryByPostTypes($postTypes, $postTypesWithCats);

	$selected_pages = explode(',', $base->getVar($grid['postparams'], 'selected_pages', '-1', 's'));

	$columns = $base->getVar($grid['params'], 'columns', '');
	$columns = $base->set_basic_colums($columns);

	$columns_width = $base->getVar($grid['params'], 'columns-width', '');
	$columns_width = $base->set_basic_colums_width($columns_width);

	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-0', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-1', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-2', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-3', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-4', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-5', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-6', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-7', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-8', '');
	$columns_advanced[] = $base->getVar($grid['params'], 'columns-advanced-rows-9', '');

	$nav_skin_choosen = $base->getVar($grid['params'], 'navigation-skin', 'minimal-light');
	$navigation_skins = $nav_skin->get_essential_navigation_skins();
	$navigation_skin_css = $base->jsonEncodeForClientSide($navigation_skins);

	$entry_skins = Essential_Grid_Item_Skin::get_essential_item_skins();
	$entry_skin_choosen = $base->getVar($grid['params'], 'entry-skin', '0');

	$grid_animations = $base->get_grid_animations();
	$hover_animations = $base->get_hover_animations();
	$grid_animation_choosen = $base->getVar($grid['params'], 'grid-animation', 'fade');
	$hover_animation_choosen = $base->getVar($grid['params'], 'hover-animation', 'fade');
	
	if(intval($isCreate) > 0) //currently editing, so default can be empty
		$media_source_order = $base->getVar($grid['postparams'], 'media-source-order', '');
	else
		$media_source_order = $base->getVar($grid['postparams'], 'media-source-order', array('featured-image'));

	$media_source_list = $base->get_media_source_order();
	
	$custom_elements = $base->get_custom_elements_for_javascript();
	
	$all_image_sizes = $base->get_all_image_sizes(); 
	
	$meta_keys = $meta->get_all_meta_handle();
	
	?>

	<!--
	LEFT SETTINGS
	-->
	<h2 class="topheader"><?php echo $title; ?></h2>
	<div class="postbox eg-postbox" style="width:100%;min-width:500px"><h3><span><?php _e('Layout Composition', EG_TEXTDOMAIN); ?></span><div class="postbox-arrow"></div></h3>
        <div class="inside" style="padding:0px !important;margin:0px !important;height:100%;position:relative;background:#e1e1e1">

			<!--
			MENU
			-->
			<div id="eg-create-settings-menu">
			  	<ul>
			  		<li style="width:150px; background:#E1e1e1;position:absolute;height:100%;top:0px;left:0px;box-sizing:border-box;
			  		-moz-box-sizing:border-box;
			  		-webkit-box-sizing:border-box;
			  		"></li>
					<li class="selected-esg-setting" data-toshow="eg-create-settings"><i class="eg-icon-cog"></i><p><?php _e('Naming', EG_TEXTDOMAIN); ?></p></li>
					<li class="selected-source-setting" data-toshow="esg-settings-posts-settings"><i class="eg-icon-folder"></i><p><?php _e('Source', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-grid-settings"><i class="eg-icon-menu"></i><p><?php _e('Grid Settings', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-filterandco-settings"><i class="eg-icon-shuffle"></i><p><?php _e('Nav-Filter-Sort', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-skins-settings"><i class="eg-icon-droplet"></i><p><?php _e('Skins', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-animations-settings"><i class="eg-icon-tools"></i><p><?php _e('Animations', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-video-settings"><i class="eg-icon-video"></i><p><?php _e('Video/Audio', EG_TEXTDOMAIN); ?></p></li>					
					<li class="" data-toshow="esg-settings-lightbox-settings"><i class="eg-icon-search"></i><p><?php _e('Lightbox', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-ajax-settings"><i class="eg-icon-ccw-1"></i><p><?php _e('Ajax', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-spinner-settings"><i class="eg-icon-back-in-time"></i><p><?php _e('Spinner', EG_TEXTDOMAIN); ?></p></li>
					<li class="" data-toshow="esg-settings-api-settings"><i class="eg-icon-magic"></i><p><?php _e('API/JavaScript', EG_TEXTDOMAIN); ?></p></li>
					<div class="clear"></div>
				</ul>
			 </div>

			<!--
			NAMING
			-->
			<div id="eg-create-settings" class="esg-settings-container active-esc">
				<div class="eg-creative-settings">
					<div class="eg-cs-tbc-left">
						<h3><span><?php _e('Naming', EG_TEXTDOMAIN); ?></span></h3>
					</div>
					<div class="eg-cs-tbc">
						<?php if($grid !== false){ ?>
						<input type="hidden" name="eg-id" value="<?php echo $grid['id']; ?>" />
						<?php } ?>

						<p><label for="name"><?php _e('Title', EG_TEXTDOMAIN); ?></label> <input type="text" name="name" class="eg-tooltip-wrap" title="<?php _e('Name of the grid', EG_TEXTDOMAIN); ?>" value="<?php echo $base->getVar($grid, 'name', '', 's'); ?>" /> *</p>
						<p><label for="handle"><?php _e('Alias', EG_TEXTDOMAIN); ?></label> <input type="text" name="handle" class="eg-tooltip-wrap" title="<?php _e('Technical alias without special chars and white spaces', EG_TEXTDOMAIN); ?>" value="<?php echo $base->getVar($grid, 'handle', '', 's'); ?>" /> *</p>
						<p><label for="shortcode"><?php _e('Shortcode', EG_TEXTDOMAIN); ?></label> <input type="text" name="shortcode" class="eg-tooltip-wrap" title="<?php _e('Copy this shortcode to paste it to your pages or posts content', EG_TEXTDOMAIN); ?>" value="" readonly="readonly" /></p>
						<p><label for="id"><?php _e('CSS ID', EG_TEXTDOMAIN); ?></label> <input type="text" name="css-id" id="esg-id-value" class="eg-tooltip-wrap" title="<?php _e('Add a unique ID to be able to add CSS to certain Grids', EG_TEXTDOMAIN); ?>" value="<?php echo $base->getVar($grid['params'], 'css-id', '', 's'); ?>" /></p>
					</div>
				</div>
			</div>

			<!--
			SOURCE
			-->
			<div id="esg-settings-posts-settings" class="esg-settings-container">
				<div class="">

					<form id="eg-form-create-posts">
						<div class="eg-creative-settings">
							<div class="eg-cs-tbc-left">
								<h3><span><?php _e('Source', EG_TEXTDOMAIN); ?></span></h3>
							</div>
							<div class="eg-cs-tbc">
								<p>
									<label for="shortcode" class="eg-tooltip-wrap" title="<?php _e('Choose source of grid items', EG_TEXTDOMAIN); ?>"><?php _e('Based on', EG_TEXTDOMAIN); ?></label>
								</p>
								<p id="esg-source-choose-wrapper">
									<input type="radio" name="source-type" value="post" class="firstinput eg-tooltip-wrap" title="<?php _e('Items from Posts, Custom Posts', EG_TEXTDOMAIN); ?>" <?php checked($base->getVar($grid['postparams'], 'source-type', 'post'), 'post'); ?>> <?php _e('Post, Pages, Custom Posts', EG_TEXTDOMAIN); ?>
									<input type="radio" name="source-type" value="custom" class="eg-tooltip-wrap" title="<?php _e('Items from the Media Gallery (Bulk Selection, Upload Possible), ', EG_TEXTDOMAIN); ?>"<?php echo checked($base->getVar($grid['postparams'], 'source-type', 'post'), 'custom'); ?>> <?php _e('Custom Grid', EG_TEXTDOMAIN); ?>
								</p>
							</div>
						</div>
						<div class="divider1"></div>
						<div id="custom-sorting-wrap" style="display: none;">
							<ul id="esg-custom-li-sorter">
							
							</ul>
						</div>
						<div id="post-pages-wrap">
							<div class="eg-creative-settings">
								<div class="eg-cs-tbc-left">
									<h3><span><?php _e('Type and Category', EG_TEXTDOMAIN); ?></span></h3>
								</div>
								<div class="eg-cs-tbc">
									<p>
										<label for="shortcode" ><?php _e('Post Types', EG_TEXTDOMAIN); ?></label>
										<select name="post_types" size="5" multiple="multiple" class="eg-tooltip-wrap" title="<?php _e('Select Post Types (multiple selection possible)', EG_TEXTDOMAIN); ?>">
											<?php
											$selectedPostTypes = array();
											$post_types = $base->getVar($grid['postparams'], 'post_types', 'post');
											if(!empty($post_types))
												$selectedPostTypes = explode(',',$post_types);
											else
												$selectedPostTypes = array('post');

											if(!empty($post_elements)){
												foreach($post_elements as $handle => $name){
													?>
													<option value="<?php echo $handle; ?>"<?php selected(in_array($handle, $selectedPostTypes), true); ?>><?php echo $name; ?></option>
													<?php
												}
											}
											?>
										</select>
									</p>

									<p id="eg-post-cat-wrap">
										<label for="shortcode"><?php _e('Post Categories', EG_TEXTDOMAIN); ?></label>
										<?php
										$postTypes = (strpos($postTypes, ",") !== false) ? explode(",",$postTypes) : $postTypes = array($postTypes);
										if(empty($postTypes)) $postTypes = array($postTypes);
										//change $postTypes to corresponding IDs depending on language
										//$postTypes = $base->translate_base_categories_to_cur_lang($postTypes);
										?>
										<select name="post_category" size="7" multiple="multiple" class="eg-tooltip-wrap" title="<?php _e('Select Categories and Tags (multiple selection possible)', EG_TEXTDOMAIN); ?>">
											<?php
											if($grid !== false){ //set the values
												if(!empty($categories)){
													
													foreach($categories as $handle => $cat){
														?>
														<option value="<?php echo $handle; ?>"<?php selected(in_array($handle, $postTypes), true); ?><?php echo (strpos($handle, 'option_disabled_') !== false) ? ' disabled="disabled"' : ''; ?>><?php echo $cat; ?></option>
														<?php
													}
												}
											}else{
												if(!empty($postTypesWithCats['post'])){
													
													foreach($postTypesWithCats['post'] as $handle => $cat){
														?>
														<option value="<?php echo $handle; ?>"<?php selected(in_array($handle, $postTypes), true); ?><?php echo (strpos($handle, 'option_disabled_') !== false) ? ' disabled="disabled"' : ''; ?>><?php echo $cat; ?></option>
														<?php
													}
												}
											}
											?>
										</select>
									</p>
									<div id="eg-additional-post">
										<div style="float: left"><label for="shortcode"><?php _e('Additional Parameters', EG_TEXTDOMAIN); ?></label></div>
										<div style="float: left">
											<input type="text" name="additional-query" class="eg-tooltip-wrap" title="<?php _e('Please use it like \'year=2012&monthnum=12\'', EG_TEXTDOMAIN); ?>" value="<?php echo $base->getVar($grid['postparams'], 'additional-query', ''); ?>" />
											<p><?php _e('Please use it like \'year=2012&monthnum=12\' or \'post__in=array(1,2,5)&post__not_in=array(25,10)\'', EG_TEXTDOMAIN); ?>&nbsp;-&nbsp;
											<?php _e('For a full list of parameters, please visit <a href="https://codex.wordpress.org/Class_Reference/WP_Query" target="_blank">this</a> link', EG_TEXTDOMAIN); ?></p>
										</div>
										<div style="clear: both"></div>
									</div>
								</div>
							</div>
						</div>
						<div class="divider1"></div>
						<div id="set-pages-wrap">
							<div class="eg-creative-settings">
								<div class="eg-cs-tbc-left">
									<h3><span><?php _e('Pages', EG_TEXTDOMAIN); ?></span></h3>
								</div>
								<div class="eg-cs-tbc">
									<p>
									<label for="pages" class="eg-tooltip-wrap" title="<?php _e('Additional filtering on pages', EG_TEXTDOMAIN); ?>"><?php _e('Select Pages', EG_TEXTDOMAIN); ?></label>
									<input type="text" id="pages" value="" name="search_pages" class="eg-tooltip-wrap" title="<?php _e('Start to type a page title for pre selection', EG_TEXTDOMAIN); ?>"> <a class="button-secondary" id="button-add-pages" href="javascript:void(0);">+</a>
									</p>
									<div id="pages-wrap">
										<?php
										if(!empty($pages)){
											foreach($pages as $page){
												if(in_array($page->ID, $selected_pages)){
													?>
													<div data-id="<?php echo $page->ID; ?>"><?php echo str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')'; ?> <i class="eg-icon-trash del-page-entry"></i></div>
													<?php
												}
											}
										}
										?>
									</div>
									<select name="selected_pages" multiple="true" style="display: none;">
										<?php
										if(!empty($pages)){
											foreach($pages as $page){
												?>
												<option value="<?php echo $page->ID; ?>"<?php selected(in_array($page->ID, $selected_pages), true); ?>><?php echo str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')'; ?></option>
												<?php
											}
										}
										?>
									</select>
								</div>
							</div>
						</div>
						<div class="divider1"></div>
						<div id="aditional-pages-wrap">
							<div class="eg-creative-settings">
								<div class="eg-cs-tbc-left">
									<h3><span><?php _e('Options', EG_TEXTDOMAIN); ?></span></h3>
								</div>
								<div class="eg-cs-tbc">
									<?php
									$max_entries = intval($base->getVar($grid['postparams'], 'max_entries', '-1'));
									?>
									<p>
										<label for="pages" class="eg-tooltip-wrap" title="<?php _e('Defines a entry limit, use only numbers', EG_TEXTDOMAIN); ?>"><?php _e('Maximum Entries', EG_TEXTDOMAIN); ?></label>
										<input type="text" value="<?php echo $max_entries; ?>" name="max_entries" class="eg-tooltip-wrap" title="<?php _e('-1 will disable this option, use only numbers', EG_TEXTDOMAIN); ?>">
									</p>
								</div>
							</div>
						</div>
						<div class="divider1"></div>
						<div id="media-source-order-wrap">
							<div class="eg-creative-settings">
								<div class="eg-cs-tbc-left">
									<h3><span><?php _e('Media Source', EG_TEXTDOMAIN); ?></span></h3>
								</div>
								<div class="eg-cs-tbc" style="padding-top:15px">
									<div  style="float:left">
										<label class="eg-tooltip-wrap" title="<?php _e('Set default order of used media', EG_TEXTDOMAIN); ?>"><?php _e('Set Source Order', EG_TEXTDOMAIN); ?></label>
									</div>
									<div style="float:left">
										<div class="eg-media-source-order-wrap">
											<?php
											if(!empty($media_source_order)){
												foreach($media_source_order as $media_handle){
													if(!isset($media_source_list[$media_handle])) continue;
													?>
													<div class="eg-media-source-order revblue button-primary">
														<i style="float:left; margin-right:10px;" class="eg-icon-<?php echo $media_source_list[$media_handle]['type']; ?>"></i>
														<span style="float:left"><?php echo $media_source_list[$media_handle]['name']; ?></span>														
														<input style="float:right;margin: 5px 4px 0 0;" class="eg-get-val" type="checkbox" name="media-source-order[]" checked="checked" value="<?php echo $media_handle; ?>" />
														<div style="clear:both"></div>
													</div>
													<?php
													unset($media_source_list[$media_handle]);
												}
											}
											
											if(!empty($media_source_list)){
												foreach($media_source_list as $media_handle => $media_set){
													?>
													<div class="eg-media-source-order revblue button-primary">
														<i style="float:left; margin-right:10px;" class="eg-icon-<?php echo $media_set['type']; ?>"></i>
														<span style="float:left"><?php echo $media_set['name']; ?></span>
														<input style="float:right;margin: 5px 4px 0 0;" class="eg-get-val" type="checkbox" name="media-source-order[]" value="<?php echo $media_handle; ?>" />
														<div style="clear:both"></div>
													</div>
													<?php
												}
											}
											?>
										</div>
										<p>
											<?php _e('First Media Source will be loaded as default. In case one source does not exist, next available media source in this order will be used', EG_TEXTDOMAIN); ?>
										</p>

									</div>
									<div style="clear:both"></div>
									<div  style="float:left">
										<label class="eg-tooltip-wrap" title="<?php _e('Image will be used if no criteria are matching so a default image will be shown', EG_TEXTDOMAIN); ?>"><?php _e('Default Image', EG_TEXTDOMAIN); ?></label>
									</div>
									<div style="float:left; margin-bottom: 10px;">
										<div>
											<?php
											$default_img = $base->getVar($grid['postparams'], 'default-image', 0, 'i');
											$var_src = '';
											if($default_img > 0){
												$img = wp_get_attachment_image_src($default_img, 'full');
												if($img !== false){
													$var_src = $img[0];
												}
											}
											?>
											<img id="eg-default-image-img" class="image-holder-wrap-div" src="<?php echo $var_src; ?>" <?php echo ($var_src == '') ? 'style="display: none;"' : ''; ?> />
										</div>
										<a class="button-primary revblue eg-default-image-add" href="javascript:void(0);" data-setto="eg-default-image"><?php _e('Choose Image', EG_TEXTDOMAIN); ?></a>
										<a class="button-primary revred eg-default-image-clear" href="javascript:void(0);" data-setto="eg-default-image"><?php _e('Remove Image', EG_TEXTDOMAIN); ?></a>
										<input type="hidden" name="default-image" value="<?php echo $default_img; ?>" id="eg-default-image" />
									</div>
									<div style="clear:both"></div>
									<div  style="float:left">
										<label class="eg-tooltip-wrap" title="<?php _e('Set Image Source Type', EG_TEXTDOMAIN); ?>"><?php _e('Set Image Source Type', EG_TEXTDOMAIN); ?></label>
									</div>
									<div style="float:left; margin-bottom: 10px;">
										<?php
										$image_source_type = $base->getVar($grid['postparams'], 'image-source-type', 'full');
										?>
										<select name="image-source-type">
											<?php
											foreach($all_image_sizes as $handle => $name){
												?>
												<option <?php selected($image_source_type, $handle); ?> value="<?php echo $handle; ?>"><?php echo $name; ?></option>
												<?php
											}
											?>
										</select>
									</div>
									<div style="clear:both"></div>
								</div>
							</div>
						</div>
						
						<div id="external-streamers-wrap"></div>
						<div id="gallery-wrap"></div>
					</form>
				</div>
			</div>

			<?php
				require_once('elements/grid-settings.php');
			?>

			<div class="save-wrap-settings">
				<div style="width:150px; background:#E1e1e1;position:absolute;height:100%;top:0px;left:0px;"></div>
				<div style="float:left"><a class="button-primary revgreen" href="javascript:void(0);" id="eg-btn-save-grid"><i class="eg-icon-cog"></i><?php echo $save; ?></a></div>
				<div style="float:left;line-height: 30px;"><a class="button-primary revblue" class="esg-refresh-preview-button"><i class="eg-icon-arrows-ccw"></i><?php _e('Refresh Preview', EG_TEXTDOMAIN); ?></a></div>
				<div style="float:left"><a class="button-primary revyellow" href="<?php echo self::getViewUrl(Essential_Grid_Admin::VIEW_OVERVIEW); ?>"><i class="eg-icon-cancel"></i><?php _e('Close', EG_TEXTDOMAIN); ?></a></div>
				<div style="float:right"><?php if($grid !== false){ ?>
					<a class="button-primary revred" href="javascript:void(0);" id="eg-btn-delete-grid"><i class="eg-icon-trash"></i><?php _e('Delete Grid', EG_TEXTDOMAIN); ?></a>
				<?php } ?></div>
				<div class="esg-clear"></div>
			</div>
        </div>
	</div>

	<div class="clear"></div>

	<?php
	if(intval($isCreate) == 0){ //currently editing
		echo '<div id="eg-create-step-3">';
	}
	?>
	<div style="width:100%;height:20px"></div>
	<h2><?php _e('Preview', EG_TEXTDOMAIN); ?></h2>
	<form id="eg-custom-elements-form-wrap">
		<div id="eg-live-preview-wrap">
			<?php
			wp_enqueue_script($this->plugin_slug . '-essential-grid-script', EG_PLUGIN_URL.'public/assets/js/jquery.themepunch.essential.min.js', array('jquery'), Essential_Grid::VERSION );
			
			Essential_Grid_Global_Css::output_global_css_styles_wrapped();
			?>
			<div id="esg-preview-wrapping-wrapper">
				<?php
				if($base->getVar($grid['postparams'], 'source-type', 'post') == 'custom'){
					$layers = $base->getVar($grid, 'layers', array());
					if(!empty($layers)){
						foreach($layers as $layer){
							?>
							<input class="eg-remove-on-reload" type="hidden" name="layers[]" value="<?php echo htmlentities(stripslashes($layer)); ?>" />
							<?php
						}
					}
				}
				?>
			</div>
		</div>
	</form>
	<?php
	if(intval($isCreate) == 0){ //currently editing
		echo '</div>';
	}
	
	Essential_Grid_Dialogs::post_meta_dialog(); //to change post meta informations
	Essential_Grid_Dialogs::edit_custom_element_dialog(); //to change post meta informations
	Essential_Grid_Dialogs::custom_element_image_dialog(); //to change post meta informations
	
	?>
	<script type="text/javascript">
		var eg_jsonTaxWithCats = <?php echo $jsonTaxWithCats; ?>;
		var pages = [
			<?php
			if(!empty($pages)){
				$first = true;
				foreach($pages as $page){
					echo (!$first) ? ",\n" : "\n";
					echo '{ value: '.$page->ID.', label: "'.str_replace('"', '', $page->post_title).' (ID: '.$page->ID.')" }';
					$first = false;
				}
			}
			?>
		];


		jQuery(function(){
			
			AdminEssentials.setInitMetaKeysJson(<?php echo $base->jsonEncodeForClientSide($meta_keys); ?>);
			
			AdminEssentials.initCreateGrid(<?php echo ($grid !== false) ? '"update_grid"' : ''; ?>);

			AdminEssentials.set_default_nav_skin(<?php echo $navigation_skin_css; ?>);

			AdminEssentials.initAccordion('eg-create-settings-general-tab');

			AdminEssentials.initSlider();

			AdminEssentials.initAutocomplete();

			AdminEssentials.initTabSizes();

			AdminEssentials.set_navigation_layout();
			
			setTimeout(function() {
				AdminEssentials.createPreviewGrid();
			},500);


			AdminEssentials.initSpinnerAdmin();
			
			AdminEssentials.setInitCustomJson(<?php echo $base->jsonEncodeForClientSide($custom_elements); ?>);
		});
	</script>

	<?php
	
	echo '<div id="navigation-styling-css-wrapper">'."\n";
	$skins = Essential_Grid_Navigation::output_navigation_skins();
	echo $skins;
	echo '</div>';
	
	?>
	
	<div id="esg-template-wrapper" style="display: none;">
	
	</div>