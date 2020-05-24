<div class="content">
                    <div class="row">
                        <div class="col-xs-3">
							<h4 class="page-title m-b-20"><?=lang('settings')?></h4>
						</div>
                        <div class="col-xs-9 m-b-0 text-right">
                            <?php if($load_setting == 'templates'){  ?>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-primary" title="Filter" data-toggle="dropdown"><i class="fa fa-cogs"></i> <?=lang('choose_template')?> <span class="caret"></span></button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=user"><?=lang('account_emails')?></a></li>
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=bugs"><?=lang('bug_emails')?></a></li>
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=project"><?=lang('project_emails')?></a></li>
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=task"><?=lang('task_emails')?></a></li>
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=invoice"><?=lang('invoicing_emails')?></a></li>
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=ticket"><?=lang('ticketing_emails')?></a></li>
                                        <li class="divider"></li>
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=extra"><?=lang('extra_emails')?></a></li>
                                        <li><a href="<?=base_url()?>admin_settings/?settings=templates&group=signature"><?=lang('email_signature')?></a></li>
                                    </ul>
                                </div>
                            <?php }
                            $set = array('theme','customize');
                            if( in_array($load_setting, $set)){  ?>
                                <?php /*<a href="<?=base_url()?>settings/?settings=customize" class="btn btn-danger btn-sm"><i class="fa fa-code text"></i>
                                    <span class="text"><?=lang('custom_css')?></span>
                                </a> */ ?>
                            <?php } ?>
                            <?php $set = array('payments');
                            if(in_array($load_setting, $set)){ $views = $this->input->get('view'); if($views != 'currency'){ ?>

                             <a href="<?=base_url()?>admin_settings/?settings=payments&view=currency" class="btn btn-primary btn-sm">
                                        <?=lang('currencies')?></a>

                            <?php } }
                            $set = array('system', 'validate');
                            if( in_array($load_setting, $set)){  ?>
                            <a href="<?=base_url()?>admin_settings/?settings=system&view=categories" class="btn btn-primary btn-sm"><?=lang('category')?>
                            </a>
                            <a href="<?=base_url()?>admin_settings/?settings=system&view=slack" class="btn btn-warning btn-sm">Slack</a>
                            <a href="<?=base_url()?>admin_settings/?settings=system&view=project" class="btn btn-info btn-sm"><?=lang('project_settings')?>
                            </a>

                    




                                <a href="<?=base_url()?>admin_settings/database" class="btn btn-success btn-sm"><i class="fa fa-cloud-download text"></i>
                                    <span class="text"><?=lang('database_backup')?></span>
                                </a>
                            <?php } ?>

                            <?php if($load_setting == 'email'){  ?>
                                <a href="<?=base_url()?>admin_settings/?settings=email&view=alerts" class="btn btn-success btn-sm"><i class="fa fa-inbox text"></i>
                                    <span class="text"><?=lang('alert_settings')?></span>
                                </a>
                            <?php } ?>

                        </div>
                    </div>
				<div class="row">
				<div class="col-sm-4 col-md-4 col-lg-3 col-xs-12">
					<a class="btn btn-default visible-xs-inline-block m-r-xs m-b-20" data-toggle="class:show" data-target="#setting-nav"><i class="fa fa-reorder"></i></a>
					<div id="setting-nav" class="card-box settings-menu hidden-xs">

					<ul>
                        <?php 
                        $email_menu = $this->db->get_where('hooks',array('name'=>email_settings))->result();
                        $template_menu = $this->db->get_where('hooks',array('name'=>email_templates))->result(); 
                        foreach ($email_menu as $v) { 
                        foreach ($template_menu as $temp) { ?>
                        <li>
                        <a data-toggle="collapse" data-parent="#setting-nav" href="#email_menu">
                        <i class="fa fa-fw fa-envelope-o"></i> Email Settings
                        </a>
                        <ul id="email_menu" class="collapse">
                        <li class="<?php echo ($load_setting == $v->route) ? 'active' : '';?>">
                            <a href="<?=base_url()?>admin_settings/?settings=<?=$v->route?>">
                                <i class="fa fa-fw <?=$v->icon?>"></i>
                                <?=lang($v->name)?>
                            </a>
                        </li>
                        <li class="<?php echo ($load_setting == $temp->route) ? 'active' : '';?>">
                            <a href="<?=base_url()?>admin_settings/?settings=<?=$temp->route?>">
                                <i class="fa fa-fw <?=$temp->icon?>"></i>
                                <?=lang($temp->name)?>
                            </a>
                        </li>  
                        </ul>
                        </li><?php } } ?>
					<?php                
					$menus = $this->db->where('hook','settings_menu_admin')->where('visible',1)->order_by('order','ASC')->get('hooks')->result();

                    $approval_menu = $this->db->get_where('hooks',array('name'=>'approval_settings'))->result();
                    
                    // if(User::is_admin()) {
					foreach ($menus as $menu) { 

                        if($menu->name != 'email_settings' && $menu->name != 'email_templates') {
                        ?>
						<li class="<?php echo ($load_setting == $menu->route) ? 'active' : '';?>">
							<a href="<?=base_url()?>admin_settings/?settings=<?=$menu->route?>">
								<i class="fa fa-fw <?=$menu->icon?>"></i>
								<?=lang($menu->name)?>
							</a>
						</li>
                       
                    <?php } } ?>
                                        
					</ul>
				</div>
				</div>
				<div class="col-sm-8 col-md-8 col-lg-9 col-xs-12">
                    <!-- Load the settings form in views -->
                    <?=$this->load->view($load_setting)?>
                    <!-- End of settings Form -->
				</div>
				</div>
</div>

 