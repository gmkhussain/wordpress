<?php
/**
 * Plugin Update Class For Essential Grid
 * Enables automatic updates on the Plugin
 *
 * @package Essential_Grid_Plugin_Update
 * @author  ThemePunch <info@themepunch.com>
 * @since 1.1.0
 */
class Essential_Grid_Plugin_Update {
	
	private $version;
	
	public function __construct($version) {
		
		$this->set_version($version);
		
	}
	
	
	/**
	 * update the version
	 */
	public function update_version($new_version){
	
		update_option("tp_eg_grids_version", $new_version);
		
	}
	
	
	/**
	 * set the version in class
	 */
	public function set_version($new_version){
	
		$this->version = $new_version;
		
	}
	
	
	/**
	 * update routine, do updates depending on what version we currently are
	 */
	public function do_update_process(){
	
		if(version_compare($this->version, '1', '<=')){
			$this->update_to_110();
		}
		
	}
	
	
	public function update_to_110(){
		
		//update navigation skins to support dropdowns
		$nav = new Essential_Grid_Navigation();
		
		$navigation_skins = array(
			array('handle' => 'flat-light','css' => '/* FLAT LIGHT SKIN DROP DOWN 1.1.0 */
.flat-light .esg-filterbutton 								{ 	color:#000;color:rgba(0,0,0,0.5);}

.flat-light	.esg-selected-filterbutton						{	background:#fff; padding:10px 20px 10px 30px; color:#000; border-radius: 4px;font-weight:700;}

.flat-light .esg-cartbutton,
.flat-light .esg-cartbutton a,
.flat-light .esg-cartbutton a:visited,
.flat-light .esg-cartbutton a:hover,
.flat-light .esg-cartbutton i,
.flat-light .esg-cartbutton i.before								{	font-weight:700; color:#000; }
.flat-light .esg-selected-filterbutton .eg-icon-down-open	{	 margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top;}

.flat-light .esg-selected-filterbutton:hover .eg-icon-down-open,
.flat-light .esg-selected-filterbutton.hoveredfilter .eg-icon-down-open	{	 color:rgba(0,0,0,1); }

.flat-light .esg-dropdown-wrapper							{	border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;}
.flat-light .esg-dropdown-wrapper .esg-filterbutton			{	line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:700; text-align: left}
.flat-light .esg-dropdown-wrapper .esg-filter-checked		{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.flat-light .esg-dropdown-wrapper .esg-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'flat-dark','css' => '/* FLAT DARK SKIN DROP DOWN 1.1.0 */
.flat-dark .esg-filterbutton 								{ 	color:#fff !important}

.flat-dark .esg-selected-filterbutton						{	background: #3A3A3A; background: rgba(0, 0, 0, 0.2); padding:10px 20px 10px 30px; color:#fff; border-radius: 4px;font-weight:600; }

.flat-dark .esg-cartbutton,
.flat-dark .esg-cartbutton a,
.flat-dark .esg-cartbutton a:visited,
.flat-dark .esg-cartbutton a:hover,
.flat-dark .esg-cartbutton i,
.flat-dark .esg-cartbutton i.before						{	font-weight:600; color:#fff; }
.flat-dark .esg-selected-filterbutton .eg-icon-down-open	{	margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top;}

.flat-dark .esg-selected-filterbutton:hover .eg-icon-down-open,
.flat-dark .esg-selected-filterbutton.hoveredfilter .eg-icon-down-open		{	 color:rgba(255,255,255,1); }
.flat-dark .esg-cartbutton:hover,
.flat-dark .esg-selected-filterbutton:hover, 
.flat-dark .esg-selected-filterbutton.hoveredfilter		{	background: rgba(0, 0, 0, 0.5); }

.flat-dark .esg-dropdown-wrapper							{	background:#222; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;}
.flat-dark .esg-dropdown-wrapper .esg-filterbutton			{	background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#fff; color:rgba(255,255,255,0.5) !important;}
.flat-dark .esg-dropdown-wrapper .esg-filterbutton:hover,
.flat-dark .esg-dropdown-wrapper .esg-filterbutton.selected	{	background:transparent !important; color:#fff; color:rgba(255,255,255,1) !important;}
.flat-dark .esg-dropdown-wrapper .esg-filter-checked		{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.flat-dark .esg-dropdown-wrapper .esg-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'minimal-dark','css' => '/* MINIMAL DARK SKIN DROP DOWN 1.1.0 */
.minimal-dark .esg-filterbutton 								{ 	color:#fff !important}

.minimal-dark .esg-selected-filterbutton						{	background: transparent; border: 1px solid rgba(255, 255, 255, 0.1);background: rgba(0, 0, 0, 0); padding:10px 20px 10px 30px; color:#fff; border-radius: 4px;font-weight:600;}

.minimal-dark .esg-cartbutton									{	border: 1px solid rgba(255, 255, 255, 0.1) !important; border-radius:5px !important; -moz-border-radius:5px !important;-webkit-border-radius:5px !important;}
.minimal-dark .esg-cartbutton,
.minimal-dark .esg-cartbutton a,
.minimal-dark .esg-cartbutton a:visited,
.minimal-dark .esg-cartbutton a:hover,
.minimal-dark .esg-cartbutton i,
.minimal-dark .esg-cartbutton i.before						{	font-weight:600; color:#fff; }
.minimal-dark .esg-selected-filterbutton .eg-icon-down-open	{	margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top; color:#fff;}

.minimal-dark .esg-cartbutton:hover,
.minimal-dark .esg-selected-filterbutton:hover, 
.minimal-dark .esg-selected-filterbutton.hoveredfilter		{	border-color: rgba(255,255,255,0.2); background: rgba(255,255,255,0.1); }

.minimal-dark .esg-dropdown-wrapper								{	background:#333; background:rgba(0,0,0,0.95);border: 1px solid rgba(255, 255, 255, 0.1);border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;}
.minimal-dark .esg-dropdown-wrapper .esg-filterbutton			{	border:none !important; box-shadow:none !important; background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#fff; color:rgba(255,255,255,0.5) !important;}
.minimal-dark .esg-dropdown-wrapper .esg-filterbutton:hover,
.minimal-dark .esg-dropdown-wrapper .esg-filterbutton.selected	{	background:transparent !important; color:#fff; color:rgba(255,255,255,1) !important; }
.minimal-dark .esg-dropdown-wrapper .esg-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid rgba(255, 255, 255, 0.2)}
.minimal-dark .esg-dropdown-wrapper .esg-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'minimal-light','css' => '/* MINIMAL LIGHT SKIN DROP DOWN 1.1.0 */
.minimal-light .esg-filterbutton 								{ 	color:#999 !important}

.minimal-light .esg-selected-filterbutton						{	 border: 1px solid #E5E5E5;background: #fff; padding:10px 20px 10px 30px; color:#999; border-radius: 4px;font-weight:700;  }

.minimal-light .esg-selected-filterbutton .eg-icon-down-open	{	margin-left:5px;font-size:12px; line-height: 20px; vertical-align: top; color:#999;}

.minimal-light .esg-filter-wrapper .esg-filterbutton span i 			{ color: #fff !important;  }
.minimal-light .esg-filter-wrapper .esg-filterbutton:hover span, 
.minimal-light .esg-filter-wrapper .esg-filterbutton.selected span		{ color: #000 !important;  }
.minimal-light .esg-filter-wrapper .esg-filterbutton:hover span i, 
.minimal-light .esg-filter-wrapper .esg-filterbutton.selected span i		{ color: #fff !important;  }

.minimal-light .esg-selected-filterbutton:hover .eg-icon-down-open,
.minimal-light .esg-selected-filterbutton.hoveredfilter .eg-icon-down-open		{	 color:rgba(0,0,0,1) !important; }
.minimal-light .esg-cartbutton:hover, 							
.minimal-light .esg-selected-filterbutton:hover, 
.minimal-light .esg-selected-filterbutton.hoveredfilter		{	border-color: #bbb; color: #333; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); }

.minimal-light .esg-dropdown-wrapper							{	background:#fff; border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px; border: 1px solid #bbb; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13);}
.minimal-light .esg-dropdown-wrapper .esg-filterbutton			{	border:none !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:700; text-align: left; color:#999; }
.minimal-light .esg-dropdown-wrapper .esg-filterbutton:hover,
.minimal-light .esg-dropdown-wrapper .esg-filterbutton.selected	{	background:transparent !important; color:#000 !important; box-shadow: none !important}
.minimal-light .esg-dropdown-wrapper .esg-filter-checked		{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.minimal-light .esg-dropdown-wrapper .esg-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'simple-light','css' => '/* SIMPLE LIGHT SKIN DROP DOWN 1.1.0 */
.simple-light .esg-filterbutton 								{ 	color:#999 !important}

.simple-light .esg-selected-filterbutton						{	 border: 1px solid #E5E5E5;background: #eee; padding:5px 5px 5px 10px; color:#000; font-weight:400;}

.simple-light .esg-selected-filterbutton .eg-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color:#000;}

.simple-light .esg-cartbutton:hover,
.simple-light .esg-selected-filterbutton:hover, 
.simple-light .esg-selected-filterbutton.hoveredfilter		{	background-color: #fff; border-color: #bbb; color: #333; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); }

.simple-light .esg-filter-wrapper .esg-filterbutton span		{ color: #000;  }
.simple-light .esg-filter-wrapper .esg-filterbutton:hover span, 
.simple-light .esg-filter-wrapper .esg-filterbutton.selected span		{ color: #000 !important;  }
.simple-light .esg-filter-wrapper .esg-filterbutton:hover span i, 
.simple-light .esg-filter-wrapper .esg-filterbutton.selected span i		{ color: #fff !important;  }

.simple-light .esg-dropdown-wrapper								{	background:#fff; border: 1px solid #bbb; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13);}
.simple-light .esg-dropdown-wrapper .esg-filterbutton			{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:400; text-align: left; }
.simple-light .esg-dropdown-wrapper .esg-filterbutton span { color:#777; }
.simple-light .esg-dropdown-wrapper .esg-filterbutton:hover,
.simple-light .esg-dropdown-wrapper .esg-filterbutton.selected	{	color:#000 !important; box-shadow: none !important}
.simple-light .esg-dropdown-wrapper .esg-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important;}
.simple-light .esg-dropdown-wrapper .esg-filter-checked span	{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'simple-dark','css' => '/* SIMPLE DARK SKIN DROP DOWN */
.simple-dark .esg-filterbutton 									{ 	color:#fff !important}

.simple-dark .esg-selected-filterbutton							{	 border: 1px solid rgba(255, 255, 255, 0.15);background:rgba(255, 255, 255, 0.08);padding:5px 5px 5px 10px; color:#fff; font-weight:600;}

.simple-dark .esg-cartbutton									{	border: 1px solid rgba(255, 255, 255, 0.1) !important; }
.simple-dark .esg-cartbutton,
.simple-dark .esg-cartbutton a,
.simple-dark .esg-cartbutton a:visited,
.simple-dark .esg-cartbutton i,
.simple-dark .esg-cartbutton i.before						{	font-weight:600; color:#fff; }

.simple-dark .esg-cartbutton:hover a, 
.simple-dark .esg-cartbutton:hover i 							{ color: #000; }

.simple-dark .esg-selected-filterbutton:hover .eg-icon-down-open,
.simple-dark .esg-selected-filterbutton.hoveredfilter .eg-icon-down-open		{	 color:#000; }
.simple-dark .esg-cartbutton:hover, 							
.simple-dark .esg-selected-filterbutton:hover, 
.simple-dark .esg-selected-filterbutton.hoveredfilter			{	border-color: #fff; color: #000; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); background: #fff; }

.simple-dark .esg-selected-filterbutton .eg-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color:#fff;}

.simple-dark .esg-filter-wrapper .esg-filterbutton:hover span, 
.simple-dark .esg-filter-wrapper .esg-filterbutton.selected span		{ color: #000 !important;  }

.simple-dark .esg-dropdown-wrapper								{	background:#fff; border: 1px solid #bbb; box-shadow: 0px 3px 5px 0px rgba(0,0,0,0.13); }

.simple-dark .esg-dropdown-wrapper .esg-filterbutton			{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#777 !important; }
.simple-light .esg-dropdown-wrapper .esg-filterbutton span { color:#777; }
.simple-dark .esg-dropdown-wrapper .esg-filterbutton:hover,
.simple-dark .esg-dropdown-wrapper .esg-filterbutton.selected	{	color:#000 !important; box-shadow: none !important; font-weight: 600;}
.simple-dark .esg-dropdown-wrapper .esg-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid #444;}
.simple-dark .esg-dropdown-wrapper .esg-filter-checked span		{	vertical-align: middle; line-height:20px;}'),
			array('handle' => 'text-dark','css' => '/* TEXT DARK SKIN DROP DOWN 1.1.0 */
.text-dark .esg-filterbutton 									{ 	color: #FFF;color: rgba(255, 255, 255, 0.4) !important}
	
.text-dark .esg-selected-filterbutton							{	padding:5px 5px 5px 10px; color: #FFF;color: rgba(255, 255, 255, 0.4);  font-weight:600;}

.text-dark .esg-cartbutton										{	 }
.text-dark .esg-cartbutton,
.text-dark .esg-cartbutton a,
.text-dark .esg-cartbutton a:visited,
.text-dark .esg-cartbutton a:hover,
.text-dark .esg-cartbutton i,
.text-dark .esg-cartbutton i.before							{	font-weight:600; color: #FFF; color: rgba(255, 255, 255, 0.4); }

.text-dark .esg-cartbutton:hover a, 
.text-dark .esg-cartbutton:hover i 								{ color: rgba(255, 255, 255, 1); }

.text-dark .esg-selected-filterbutton:hover .eg-icon-down-open,
.text-dark .esg-selected-filterbutton.hoveredfilter .eg-icon-down-open		{	 color: rgba(255, 255, 255, 1); }
.text-dark .esg-cartbutton:hover, 							
.text-dark .esg-selected-filterbutton:hover, 
.text-dark .esg-selected-filterbutton.hoveredfilter				{	color: rgba(255, 255, 255, 1); }

.text-dark .esg-selected-filterbutton .eg-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color: #FFF;color: rgba(255, 255, 255, 0.4); }

.text-dark .esg-filter-wrapper .esg-filterbutton:hover span, 
.text-dark .esg-filter-wrapper .esg-filterbutton.selected span	{ color: rgba(255, 255, 255, 1);  }

.text-dark .esg-dropdown-wrapper								{	border: 1px solid rgba(0, 0, 0, 0.15); background:#000; background:rgba(0, 0, 0, 0.95); }
.text-dark .esg-dropdown-wrapper .esg-filterbutton				{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#999 !important; }
.text-dark .esg-dropdown-wrapper .esg-filterbutton span  		{   text-decoration: none !important; }
.text-dark .esg-dropdown-wrapper .esg-filterbutton:hover,
.text-dark .esg-dropdown-wrapper .esg-filterbutton.selected		{	color:#fff !important; box-shadow: none !important; }
.text-dark .esg-dropdown-wrapper .esg-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid #444;}
.text-dark .esg-dropdown-wrapper .esg-filterbutton.selected .esg-filter-checked,
.text-dark .esg-dropdown-wrapper .esg-filterbutton:hover .esg-filter-checked	{	color:#fff;}

.text-dark .esg-dropdown-wrapper .esg-filter-checked span		{	vertical-align: middle; line-height:20px; color:#000;}'),
			array('handle' => 'text-light','css' => '/* TEXT LIGHT SKIN DROP DOWN 1.1.0 */
.text-light .esg-filterbutton 									{ 	color: #999}

.text-light .esg-selected-filterbutton							{	padding:5px 5px 5px 10px; color: #999; font-weight:600;}

.text-light .esg-cartbutton										{	 }
.text-light .esg-cartbutton,
.text-light .esg-cartbutton a,
.text-light .esg-cartbutton a:visited,
.text-light .esg-cartbutton a:hover,
.text-light .esg-cartbutton i,
.text-light .esg-cartbutton i.before							{	font-weight:600; color: #999; }

.text-light .esg-cartbutton:hover a, 
.text-light .esg-cartbutton:hover i 							{ color: #444; }

.text-light .esg-selected-filterbutton:hover .eg-icon-down-open,
.text-light .esg-selected-filterbutton.hoveredfilter .eg-icon-down-open		{	 color: #444; }
.text-light .esg-cartbutton:hover, 							
.text-light .esg-selected-filterbutton:hover, 
.text-light .esg-selected-filterbutton.hoveredfilter			{	color: #444; }

.text-light .esg-selected-filterbutton .eg-icon-down-open		{	margin-left:5px;font-size:9px; line-height: 20px; vertical-align: top; color: #999; }

.text-light .esg-filter-wrapper .esg-filterbutton:hover span, 
.text-light .esg-filter-wrapper .esg-filterbutton.selected span	{ text-decoration: none !important; }

.text-light .esg-dropdown-wrapper								{	border: 1px solid rgba(255, 255, 255, 0.15); background:#fff; background:rgba(255, 255, 255, 0.95); }
.text-light .esg-dropdown-wrapper .esg-filterbutton				{	border:none !important;background:transparent !important;line-height: 25px; white-space: nowrap; padding:0px 10px; font-weight:600; text-align: left; color:#999 !important; }
.text-light .esg-dropdown-wrapper .esg-filterbutton span  		{   text-decoration: none !important; }
.text-light .esg-dropdown-wrapper .esg-filterbutton:hover,
.text-light .esg-dropdown-wrapper .esg-filterbutton.selected	{	color:#000 !important; box-shadow: none !important; }
.text-light .esg-dropdown-wrapper .esg-filter-checked			{	display:inline-block; margin-left:0px !important;margin-right:7px; margin-top:-2px !important; line-height: 15px !important; border: 1px solid #ddd;}
.text-light .esg-dropdown-wrapper .esg-filterbutton.selected .esg-filter-checked,
.text-light .esg-dropdown-wrapper .esg-filterbutton:hover .esg-filter-checked	{	color:#000;}

.text-light .esg-dropdown-wrapper .esg-filter-checked span		{	vertical-align: middle; line-height:20px; color:#000;}')
		);
		
		foreach($navigation_skins as $skin){
			$old_skin = $nav->get_essential_navigation_skin_by_handle($skin['handle']);
			
			if($old_skin !== false){
				$old_skin['css'] .= "\n\n\n".$skin['css'];
				
				//modify variables to meet requirement for update function
				$old_skin['skin_css'] = $old_skin['css'];
				$old_skin['sid'] = $old_skin['id'];
				unset($old_skin['name']);
				unset($old_skin['css']);
				unset($old_skin['id']);
				
				$nav->update_create_navigation_skin_css($old_skin);
				
			}
			
		}
		
		$this->update_version('1.1.0');
		$this->set_version('1.1.0');
		
	}
	
}