<div class="panel panel-white">
	<div class="panel-heading font-bold">
		<h3 class="panel-title"><?=ucfirst(str_replace('_',' ', $role_name)).' - '.lang('mods_privileges')?></h3>
		<a href="<?php echo base_url(); ?>settings/?settings=menu" title="Back to Menu List">Back</a>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-sm-12 col-xs-12 col-md-12"> 
				<?php //echo "<pre>"; print_r($menus); exit; ?>
				<div class="table-responsive">
			        <table  class="table table-striped custom-table m-b-0 AppendDataTables">
			            <thead>
			                <tr>
			                    <th>#</th>
			                    <th>Menu Icon</th>
			                    <th>Module</th>
			                    <th class="col-options no-sort text-center"><?=lang('action')?></th>
			                </tr>
			            </thead>
			            <tbody>
			            	<?php $e = 1; 
			            	foreach($menus as $menu){ 
			            		if($menu['visible'] == 1)
			                    {
			                        $record_visible = "success";
			                    }else{
			                        $record_visible = "default";
			                    }
			            		?>
			            		<tr class="sortable" data-module="<?php echo $menu['module']; ?>" data-access="1">
		            				<td ><?php echo $e; ?></td>
		            				<td>
		            					<div class="btn-group"><button class="btn btn-default iconpicker-component" type="button"><i class="fa <?php echo $menu['icon']; ?> fa-fw"></i></button><button data-toggle="dropdown" data-selected="<?php echo $menu['icon']; ?>" class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button" aria-expanded="false" data-role="<?php echo $menu['access']; ?>" data-href="<?php echo base_url(); ?>settings/hook/icon/<?php echo $menu['module']; ?>"><span class="caret"></span></button><div class="dropdown-menu iconpicker-container"></div></div>
		            				</td>
		            				<td><?php echo lang($menu['name']); ?></td>
		            				<td class="text-center">
		            					<a data-rel="tooltip" data-original-title="Toggle" class="menu-view-toggle btn btn-xs btn-<?php echo $record_visible; ?>" href="#" data-role="<?php echo $menu['access']; ?>" data-href="<?php echo base_url(); ?>settings/hook/visible/<?php echo $menu['module']; ?>"><i class="fa fa-eye"></i></a>
		            				</td>
		            			</tr>
			            	<?php $e++; } ?>
			            </tbody>
			        </table>
			    </div>
			</div>
		</div>
	</div>
</div>