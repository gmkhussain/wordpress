<?php
global $EssentialAsTheme;

$dir = plugin_dir_path(__FILE__).'../../../';

$validated = get_option('tp_eg_valid', 'false');
$username = get_option('tp_eg_username', '');
$api_key = get_option('tp_eg_api-key', '');
$code = get_option('tp_eg_code', '');
$latest_version = get_option('tp_eg_latest-version', Essential_Grid::VERSION);
if(version_compare($latest_version, Essential_Grid::VERSION, '>')){
	//new version exists
}else{
	//up to date
}
?>

<!-- 
THE INFO ABOUT EMBEDING OF THE SLIDER 			
-->
<div class="title_line" style="margin-top:45px"><div class="view_title"><?php _e("How To Use Essential Grid", EG_TEXTDOMAIN)?></div></div>		

<div style="border:1px solid #e5e5e5; padding:15px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background:#FFFFFF">		
	<div class="revyellow" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-arrows-ccw"></i></div>
	<p><?php _e('From the <b>page and/or post editor</b> insert the shortcode from the sliders table', EG_TEXTDOMAIN)?></p>
	<p><?php _e('From the <b>widgets panel</b> drag the "Essential Grid" widget to the desired sidebar', EG_TEXTDOMAIN); ?></p>
</div>

<?php
if(!$EssentialAsTheme){
	?>
	<div style="width:100%;height:50px"></div>
	<!-- 
	THE CURRENT AND NEXT VERSION		
	-->
	<div class="title_line"><div class="view_title"><?php _e("Version Information", EG_TEXTDOMAIN)?></div></div>		

	<div style="border:1px solid #e5e5e5; padding:15px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background:#FFFFFF">		
		<div class="revgray" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-info-circled"></i></div>
		<p style="margin-top:5px; margin-bottom:5px;">
				<?php _e("Installed Version", EG_TEXTDOMAIN)?>: <span  class="slidercurrentversion"><?php echo Essential_Grid::VERSION; ?></span><br>
				<?php _e("Available Version", EG_TEXTDOMAIN)?>: <span class="slideravailableversion"><?php echo $latest_version; ?></span>
		</p>
	</div>


	<!--
	ACTIVATE THIS PRODUCT 
	-->
	<a name="activateplugin"></a>
	<div style="width:100%;height:50px"></div>

	<div class="title_line">
		<div class="view_title"><span style="margin-right:10px"><?php _e("Need Premium Support and Auto Updates ?", EG_TEXTDOMAIN) ?></span><a style="vertical-align:middle" class='button-primary revblue' href='#' id="benefitsbutton"><?php _e("Why is this Important ?", EG_TEXTDOMAIN)?> </a></div>
	</div>

	<div id="benefitscontent" style="margin-top:10px;margin-bottom:10px;display:none;border:1px solid #e5e5e5; padding:15px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background-color:#fff;">		
		<div class="revblue" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-doc"></i></div>
		<h3> <?php _e("Benefits", EG_TEXTDOMAIN)?>:</h3>
		<p>
		<strong><?php _e("Get Premium Support", EG_TEXTDOMAIN); ?></strong><?php _e(" - We help you in case of Bugs, installation problems, and Conflicts with other plugins and Themes ", EG_TEXTDOMAIN); ?><br>
		<strong><?php _e("Auto Updates", EG_TEXTDOMAIN); ?></strong><?php _e(" - Get the latest version of our Plugin.  New Features and Bug Fixes are available regularly !", EG_TEXTDOMAIN); ?>
		</p>
	</div>

	<!-- 
	VALIDATION
	-->
	<div id="tp-validation-box" style="cursor:pointer;border:1px solid #e5e5e5; margin-top:10px;padding:15px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background:#FFFFFF">		
		
		<!-- 
		  CONTENT BEFORE ACTIVATION, BASED OF VALIDATION 
		-->
		<?php if($validated === 'true') {
				$displ = "block";
			?> 
			<div class="revgreen" style="left:0px;top:0px;position:absolute;height:100%;padding:30px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-check"></i></div>
			<?php 	
		} else {
				$displ = "none";
			?> 
			<div class="revcarrot"   style="left:0px;top:0px;position:absolute;height:100%;padding:22px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-cancel"></i></div>
			<?php 
		}
		?>

		<div id="rs-validation-wrapper" style="display:<?php echo $displ; ?>">
			
			<div class="validation-label"><?php _e('Username:', EG_TEXTDOMAIN); ?></div> 
			<div class="validation-input"> 
				<input type="text" name="eg-validation-username" <?php echo ($validated === 'true') ? ' readonly="readonly"' : ''; ?> value="<?php echo $username; ?>" />
				<p class="validation-description"><?php _e('Your Envato username.', EG_TEXTDOMAIN); ?></p>
			</div>
			<div class="clear"></div>
			
			
			<div class="validation-label"><?php _e('Envato API Key:', EG_TEXTDOMAIN); ?> </div> 
			<div class="validation-input">
				<input type="text" name="eg-validation-api-key" value="<?php echo $api_key; ?>" <?php echo ($validated === 'true') ? ' readonly="readonly"' : ''; ?> style="width: 350px;" />
				<p class="validation-description"><?php _e('You can find API key by visiting your Envato Account page, then clicking the My Settings tab. At the bottom of he page you will find your accounts API key.', EG_TEXTDOMAIN); ?></p>
			</div>
			<div class="clear"></div>
			
			<div class="validation-label"><?php _e('Purchase code:', EG_TEXTDOMAIN); ?></div> 
			<div class="validation-input">
				<input type="text" name="eg-validation-token" value="<?php echo $code; ?>" <?php echo ($validated === 'true') ? ' readonly="readonly"' : ''; ?> style="width: 350px;" />
				<p class="validation-description"><?php _e('Please enter your ', EG_TEXTDOMAIN); ?><strong style="color:#000"><?php _e('CodeCanyon Essential Grid purchase code / license key', EG_TEXTDOMAIN); ?></strong><?php _e('. You can find your key by following the instructions on', EG_TEXTDOMAIN); ?><a target="_blank" href="http://www.themepunch.com/home/plugins/wordpress-plugins/revolution-slider-wordpress/where-to-find-the-purchase-code/"><?php _e(' this page.', EG_TEXTDOMAIN); ?></a></p>
			</div>
			<div class="clear"></div>
			
			<span style="display:none" id="rs_purchase_validation" class="loader_round"><?php _e('Please Wait...', EG_TEXTDOMAIN); ?></span>

			<a href="javascript:void(0);" <?php echo ($validated !== 'true') ? '' : 'style="display: none;"'; ?> id="eg-validation-activate" class="button-primary revgreen"><?php _e('Activate', EG_TEXTDOMAIN); ?></a>
			
			<a href="javascript:void(0);" <?php echo ($validated === 'true') ? '' : 'style="display: none;"'; ?> id="eg-validation-deactivate" class="button-primary revred"><?php _e('Deactivate', EG_TEXTDOMAIN); ?></a>
			

			<?php
			if($validated === 'true'){
				?>
				<a href="update-core.php" id="eg-check-updates" class="button-primary revpurple"><?php _e('Search for Updates', EG_TEXTDOMAIN); ?></a>
				<?php
			}
			?>
			
		</div>

		<!-- 
		  CONTENT AFTER ACTIVATION, BASED OF VALIDATION 
		-->
		<?php if($validated === 'true') {
			?> 
			<div style="height:15px"></div>
			<h3> <?php _e("How to get Support ?", EG_TEXTDOMAIN)?>:</h3>				
			<p>
			<?php _e("Please feel free to contact us via our ", EG_TEXTDOMAIN)?><a href='http://themepunch.ticksy.com'><?php _e("Support Forum ", EG_TEXTDOMAIN)?></a><?php _e("and/or via the ", EG_TEXTDOMAIN)?><a href='http://codecanyon.net/item/tp_eg_pro-responsive-teaser-wordpress-plugin/4720988'><?php _e("Item Disscussion Forum", EG_TEXTDOMAIN)?></a><br />
			</p> 	
			<?php 	
		} else {
			?> 
			<p style="margin-top:10px; margin-bottom:10px;" id="tp-before-validation">

			<?php _e("Click Here to get ", EG_TEXTDOMAIN); ?><strong><?php _e("Premium Support and Auto Updates", EG_TEXTDOMAIN); ?></strong><br />
		
			</p> 
			<?php 
		}
		?>
	</div>
	<?php
}else{
	?>
	<div style="width:100%;height:50px"></div>
	<!-- INFORMATIONS -->
	<div class="title_line"><div class="view_title"><?php _e("Information", EG_TEXTDOMAIN)?></div></div>		

	<div style="border:1px solid #e5e5e5; padding:15px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background:#FFFFFF">		
		<div class="revgray" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:25px" class="eg-icon-info-circled"></i></div>
		<p style="margin-top:5px; margin-bottom:5px;">
			<?php _e("Please note that this plugin came bundled with a theme. The use of the Essential Grid is limited to this theme only.<br>If you need support from the plugin author ThemePunch or you want to use Essential Grid with an other theme you will need an extra single license available at CodeCanyon.", EG_TEXTDOMAIN); ?>
		</p>
	</div>
	<?php
}
?>

<!-- THE UPDATE HISTORY OF ESSENTIAL GRID -->
<div style="width:100%;height:50px"></div>	

<div class="title_line">
	<div class="view_title"><span style="margin-right:10px"><?php _e("Update History", EG_TEXTDOMAIN) ?></span></div>
</div>

<div style="border:1px solid #e5e5e5;  height:500px;padding:25px 15px 15px 80px; border-radius:0px;-moz-border-radius:0px;-webkit-border-radius:0px;position:relative;overflow:hidden;background:#FFFFFF">		
	<div class="revpurple" style="left:0px;top:0px;position:absolute;height:100%;padding:27px 10px;"><i style="color:#fff;font-size:27px" class="eg-icon-back-in-time"></i></div>
	<div style="height:485px;overflow:scroll;width:100%;"><?php echo file_get_contents($dir."release_log.html"); ?></div>							
</div>


<script type="text/javascript">
jQuery(document).ready(function(){

	jQuery('#benefitsbutton').hover(function() {
		jQuery('#benefitscontent').slideDown(200);
	}, function() {
		jQuery('#benefitscontent').slideUp(200);				
	});
	
	jQuery('#tp-validation-box').click(function() {
		jQuery(this).css({cursor:"default"});
		if (jQuery('#rs-validation-wrapper').css('display')=="none") {
			jQuery('#tp-before-validation').hide();
			jQuery('#rs-validation-wrapper').slideDown(200);
		}
	})
	
	AdminEssentials.initUpdateRoutine();
	
});
</script>