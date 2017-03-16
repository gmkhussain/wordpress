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

$order = false;
//order=asc&orderby=name
if(isset($_GET['orderby']) && isset($_GET['order'])){
	$order = array();
	switch($_GET['orderby']){
		case 'shortcode':
			$order['handle'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
		case 'name':
		default:
			$order['name'] = ($_GET['order'] == 'asc') ? 'ASC' : 'DESC';
		break;
	}
}

$grids = Essential_Grid::get_essential_grids($order);

$limit = (@intval($_GET['limit']) > 0) ? @intval($_GET['limit']) : 10;
$otype = 'reg';
$total = 0;

$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$offset = ( $pagenum - 1 ) * $limit;

$cur_offset = 0;

?>
	<h2 class="topheader"><?php _e('Overview', EG_TEXTDOMAIN); ?></h2>
	<div id="eg-global-settings-wrap">
		<a target="_blank" class="button-secondary" href="http://themepunch.com/essential/documentation/"><?php _e('Help', EG_TEXTDOMAIN); ?></a>
		<a class="button-secondary" id="button-general-settings" href="#"><?php _e('Global Settings', EG_TEXTDOMAIN); ?></a>
	</div>
	
	<div id="eg-grid-overview-wrapper">
		<?php
		
		if(!empty($grids) && is_array($grids)){
            
			?>
			<table class='wp-list-table widefat fixed eg-postbox'>
				<thead>
					<tr>
						<th width="20px"><?php _e('ID', EG_TEXTDOMAIN); ?></th>
						<th width="20%">
							<?php _e('Name', EG_TEXTDOMAIN); ?>
							<a href="?page=essential-grid&orderby=name&order=asc" class="eg-icon-down-dir"></a>
							<a href="?page=essential-grid&orderby=name&order=desc" class="eg-icon-up-dir"></a>
						</th>
						<th width="22%">
							<?php _e('Shortcode', EG_TEXTDOMAIN); ?>
							<a href="?page=essential-grid&orderby=shortcode&order=asc" class="eg-icon-down-dir"></a>
							<a href="?page=essential-grid&orderby=shortcode&order=desc" class="eg-icon-up-dir"></a>
						</th>
						<th width="39%"><?php _e('Actions', EG_TEXTDOMAIN); ?> </th>
						<th width="23%"><?php _e('Settings', EG_TEXTDOMAIN); ?> </th>
					</tr>
				</thead>
				<tbody>
				<?php
				foreach($grids as $grid){
					$total++;
					$cur_offset++;
					if($cur_offset <= $offset) continue; //if we are lower then the offset, continue;
					if($cur_offset > $limit + $offset) continue; // if we are higher then the limit + offset, continue
					
					$cur_grid = Essential_Grid::get_essential_grid_by_id($grid->id);
					$skin_id = @$cur_grid['params']['entry-skin'];
					?>
					<tr>
						<td><?php echo $grid->id; ?></td>
						<td><?php echo $grid->name; ?></td>
						<td>[ess_grid alias="<?php echo $grid->handle; ?>"]</td>
						<td>
							<div class="btn-wrap-overview-<?php echo $grid->id; ?>">
								<a class="button-primary revgreen" href="<?php echo Essential_Grid_Base::getViewUrl(Essential_Grid_Admin::VIEW_GRID_CREATE, 'create='.$grid->id); ?>"><i class="eg-icon-cog"></i><?php _e("Settings", EG_TEXTDOMAIN); ?></a>
								<a class="button-primary revblue" href="<?php echo Essential_Grid_Base::getViewUrl(Essential_Grid_Admin::VIEW_ITEM_SKIN_EDITOR, 'create='.$skin_id); ?>" target="_blank"><i class="eg-icon-palette"></i><?php _e("Edit Active Skin", EG_TEXTDOMAIN); ?></a>
								<a class="button-primary revred eg-btn-delete-grid" id="eg-delete-<?php echo $grid->id; ?>" href="javascript:void(0)"><i class="eg-icon-trash"></i></a>
								<a class="button-primary revyellow eg-btn-duplicate-grid" id="eg-duplicate-<?php echo $grid->id; ?>" href="javascript:void(0)"><i class="eg-icon-picture"></i></a>
							</div>
						</td>
						<td>
						<?php
						echo ucfirst($cur_grid['params']['layout']);
						if($cur_grid['params']['layout'] == 'even')
							echo ', '.$cur_grid['params']['x-ratio'].':'.$cur_grid['params']['y-ratio'];
						
						if(isset($cur_grid['postparams']['source-type']))
							echo ', '.ucfirst($cur_grid['postparams']['source-type']);
							
						if(isset($cur_grid['params']['layout-sizing']))
							echo ', '.ucfirst($cur_grid['params']['layout-sizing']);
						?>
						</td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
			<?php
		}else{
			_e('No Essential Grids found!', EG_TEXTDOMAIN);
		}
		?>
	</div>
	
	<a class='button-primary revblue' href='<?php echo $this->getViewUrl(Essential_Grid_Admin::VIEW_GRID_CREATE, 'create=true'); ?>'><?php _e('Create New Ess. Grid', EG_TEXTDOMAIN); ?></a>
	
	<?php
	$num_of_pages = ceil( $total / $limit );
	
	$page_links = paginate_links( array(
		'base' => add_query_arg( 'pagenum', '%#%' ),
		'format' => '',
		'add_args' => array('limit' => $limit),
		'prev_text' => __( '&laquo;', EG_TEXTDOMAIN ),
		'next_text' => __( '&raquo;', EG_TEXTDOMAIN ),
		'total' => $num_of_pages,
		'current' => $pagenum
	) );

	if ( $page_links ) {
		echo '<div class="ess-pagination-wrap"><div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div></div>';
	}
	?>
	<form id="ess-pagination-form" action="?page=essential-grid&pagenum=1" method="GET">
		<input type="hidden" name="page" value="essential-grid" />
		<input type="hidden" name="pagenum" value="1" />
		<select name="limit" onchange="this.form.submit()">
			<option <?php echo ($limit == 10) ? 'selected="selected"' : ''; ?> value="10">10</option>
			<option <?php echo ($limit == 25) ? 'selected="selected"' : ''; ?> value="25">25</option>
			<option <?php echo ($limit == 50) ? 'selected="selected"' : ''; ?> value="50">50</option>
			<option <?php echo ($limit == 9999) ? 'selected="selected"' : ''; ?> value="9999"><?php _e('All', EG_TEXTDOMAIN); ?></option>
		</select>
	</form>
	<?php
	require_once('elements/grid-info.php');
	?>
	
	<?php Essential_Grid_Dialogs::globalSettingsDialog(); ?>
	
	
	<script type="text/javascript">
		AdminEssentials.initOverviewGrid();
	</script>