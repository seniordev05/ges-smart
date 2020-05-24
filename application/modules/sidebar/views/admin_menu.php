<?php

if($this->uri->segment(1) == 'chats'){

?>





<div class="sidebar sidebar-<?=config_item('sidebar_theme')?>" id="sidebar">

                <div class="sidebar-inner slimscroll">

                    <div class="sidebar-menu video-wrapper">

                        <?php //echo "<pre>"; print_r($group_chat_list); exit; ?>

                        <ul class="grp-chat-wrapper">

                            <li> 

                                <a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> <span>Back to Home</span></a>

                            </li>





                        </ul>

                        <ul class="single-chat-wrapper">

                            <li class="menu-title">

                                Direct Chats <a href="#" data-toggle="modal" data-target="#add_chat_user" class="menu-title-icon" title="Open a direct message"><i class="fa fa-plus"></i></a>

                                <?php //echo "<pre>"; print_r($single_chat_list); exit; ?>

                            </li>

                        </ul> 

                                <?php if(empty($single_chat_list)){ ?>

                                <ul>

                                    <li> <span>No Chat</span></li> 

                                </ul> 

                                <?php }else{ ?>



                                <ul class="UseRLisT">

                                    <?php foreach($single_chat_list as $single_chat){ if(!empty($single_chat['avatar'])){

                                        $pro_pic = $single_chat['avatar'];

                                    }else{

                                        $pro_pic = 'default_avatar.jpg';

                                    }

                                    ?>

                                    <li> 

                                                <?php if($single_chat['online_status'] == 0){ 

                                                            $status = 'offline';

                                                        }else{

                                                            $status = 'online';

                                                        }







                                                        $destination = $this->db->get_where('designation',array('id'=>$single_chat['designation_id']))->row_array();

                                                        // print_r($destination); exit;

                                                    ?>

                                        <a class="SingleChatList" data-name="<?php echo ucfirst($single_chat['fullname']); ?>" data-email="<?php echo $single_chat['email']; ?>" data-phone="<?php echo $single_chat['phone']; ?>" data-online="<?php echo $status; ?>" data-propic="<?php echo $pro_pic; ?>" data-username="<?php echo $single_chat['username']; ?>" data-baseurl="<?php echo base_url(); ?>" data-lastlogin="<?php echo $single_chat['last_login']; ?>" data-userid="<?php echo $single_chat['user_id']; ?>" data-destination ="<?php echo $destination['designation']; ?>" >

                                            <span class="user-img chat-avatar-sm">

                                                <img class="img-circle" src="<?php echo base_url(); ?>assets/avatar/<?php echo $pro_pic; ?>" width="32" alt="Admin">

                                                <span class="status <?php echo $status; ?>"></span>

                                            </span> 

                                            <span class="member-name-blk"><?php echo ucfirst($single_chat['fullname']); ?></span>

                                            <span class="badge bg-danger pull-right"></span>

                                        </a>

                                    </li>

                                <?php } ?>

                                </ul>

                            <?php }?>

                    </div>

                </div>

            </div>







<?php }elseif($this->uri->segment(1) =='all_tasks'){ ?>





<div class="sidebar sidebar-<?=config_item('sidebar_theme')?>" id="sidebar">

                <div class="sidebar-inner slimscroll">

                    <div class="sidebar-menu">

                        <ul>

                            <li> 

                                <a href="<?php echo base_url(); ?>"><i class="fa fa-home"></i> Back to Home</a>

                            </li>

                            <li class="menu-title"><a href="<?php echo base_url(); ?>projects">Projects</a> </li>



                            <?php $i=1; foreach($all_projects as $project){ ?>

                                <li <?php if($this->uri->segment(3) == ''){ if($i == count($all_projects)){ ?> class="active" <?php } }elseif($this->uri->segment(3) == $project['project_id']){ ?> class="active" <?php } ?> > 

                                    <a href="<?php echo base_url(); ?>all_tasks/view/<?php echo $project['project_id']; ?>"><?php echo $project['project_title']; ?></a>

                                </li>

                            <?php $i++; } ?>



                        </ul>

                    </div>

                </div>

            </div>









<?php }else{ ?>



    <div class="sidebar-<?=config_item('sidebar_theme')?> sidebar" id="nav">

    <div class="slimscroll">    

        <?php if(config_item('enable_languages') == 'TRUE'){  ?>

        <div class="language-menu">

            <div class="dropdown">

                <button type="button" class="btn btn-sm btn-default dropdown-toggle btn-block hidden-nav-xs" data-toggle="dropdown"><?=lang('languages')?> <span class="caret"></span></button>

                <ul class="dropdown-menu text-left">

                    <?php foreach ($languages as $lang) : if ($lang->active == 1) : ?>

                    <li>

                        <a href="<?=base_url()?>set_language?lang=<?=$lang->name?>" title="<?=ucwords(str_replace("_"," ", $lang->name))?>">

                            <img src="<?=base_url()?>assets/images/flags/<?=$lang->icon?>.gif" alt="<?=ucwords(str_replace("_"," ", $lang->name))?>"  /> <?=ucwords(str_replace("_"," ", $lang->name))?>

                        </a>

                    </li>

                    <?php endif; endforeach; ?>

                </ul>

            </div>

        </div>

        <?php  } ?>

        <div id="sidebar-menu" class="sidebar-menu">

            <ul class="nav">

                <?php

                $badge = array();

                $timer_on = App::counter('project_timer',array('status'=>'1'));

                if($timer_on > 0){ $badge['menu_projects'] = '<b class="badge bg-danger pull-right">'.$timer_on.'<i class="fa fa-refresh fa-spin"></i></b>'; }



                $unread = App::counter('messages',array('user_to'=>User::get_id(),'status' => 'Unread'));

                $open_tickets = App::counter('tickets',array('status !=' => 'closed'));



                if($unread > 0){ $badge['menu_messages'] = '<b class="badge bg-primary pull-right">'.$unread.'</b>'; }

                if($open_tickets > 0){ $badge['menu_tickets'] = '<b class="badge bg-primary pull-right">'.$open_tickets.'</b>'; }

                $role_id = $this->session->userdata('role_id');

                // echo $role_id; exit;
                $user_type = $this->session->userdata('user_type');
            //    echo $user_type; exit;

                // echo $this->session->userdata('user_type'); exit;

                if(($user_type == 0) && ($role_id == 1)){
                   $user_role = 1;
                }else{
                   $user_role = $role_id;
                }

                $role_details = $this->db->get_where('roles',array('r_id'=>$user_role))->row_array();

                $menus = $this->db->where('access',$user_role)->where('visible',1)->where('parent','')->where('hook','main_menu_'.$role_details['role'])->order_by('order','ASC')->get('hooks')->result();

                //  echo '<pre>'; print_r($menus); exit;

                //  echo "<pre>"; print_r($role_details); exit;
                //$all_routes = array();
                foreach ($menus as $menu) {

                    $all_menu_routes[] = $menu->route;

                    $sub = $this->db->where('access',$user_role)->where('visible',1)->where('parent',$menu->module)->where('hook','main_menu_'.$role_details['role'])->order_by('order','ASC')->get('hooks');

                ?>

                <?php if ($sub->num_rows() > 0) {

                $submenus = $sub->result(); 

            //    echo '<pre>'; print_r($submenus);

                ?>

                <li class="<?php

                    foreach ($submenus as $submenu) {

                        $all_sun_menu_routes[] = $submenu->route;

                        if(strtolower($page) == strtolower(lang($submenu->name))){echo  "active"; }

                    }
                    
                   

                ?>">

                    <a href="<?=base_url()?><?=$menu->route?>">

                        <i class="fa <?=$menu->icon?> icon"> <b class="bg-<?=config_item('theme_color');?>"></b></i>

                        <span class="pull-right"> <i class="fa fa-angle-down text"></i> <i class="fa fa-angle-up text-active"></i></span>

                        <span><?=lang($menu->name)?></span>

                    </a>

                    <ul class="nav lt">
                        <?php foreach ($submenus as $submenu) { 
                            $submain_menu = $this->db->where('access',1)->where('visible',1)->where('parent',$submenu->module)->where('hook','main_menu_admin')->order_by('order','ASC')->get('hooks');
                            if($submain_menu->num_rows() > 0){
                                $all_submain = $submain_menu->result();
                             ?>

                            <li class="submenu <?php if($page == lang($submenu->name)){echo  "active"; }?>">
                                <a href="<?=base_url()?><?=$submenu->route?>"><span><?=lang($submenu->name)?></span> <span class="menu-arrow"></span></a>
                                <ul style="display: none;">
                                    <?php foreach($all_submain as $all_submains){ ?>
                                    <li class="<?php if($page == lang($all_submains->name)){echo  "active"; }?>">
                                        <a href="<?=base_url()?><?=$all_submains->route?>"> <?php if (isset($badge[$all_submains->module])) { echo $badge[$menu->module]; } ?>
                                        <span><?=lang($all_submains->name)?></span></a>
                                    </li>
                                <?php } ?>
                                </ul>
                            </li>


                            <?php }else{ 

                                $performance_status = $this->db->get('performance_status')->row_array();
                                // echo "<pre>"; print_r($performance_status); exit;
                                if($submenu->route =='performance' || $submenu->route =='performance_three_sixty' || $submenu->route =='smartgoal'){
                                ?>
                                <?php if($performance_status['okr'] == 1 && $submenu->route =='performance') {?>
                                <li class="<?php if($page == lang($submenu->name)){echo  "active"; }?>" data-test="<?php echo $r; ?> ts">
                                    <a href="<?=base_url()?><?=$submenu->route?>">
                                        <?php if (isset($badge[$submenu->module])) { echo $badge[$menu->module]; } ?>
                                        <span><?=lang($submenu->name)?></span>
                                    </a>
                                </li>
                            <?php }?>
                            <?php if($performance_status['competency'] == 1 && $submenu->route =='performance_three_sixty') {?>
                                <li class="<?php if($page == lang($submenu->name)){echo  "active"; }?>" data-test="<?php echo $r; ?> ts">
                                    <a href="<?=base_url()?><?=$submenu->route?>">
                                        <?php if (isset($badge[$submenu->module])) { echo $badge[$menu->module]; } ?>
                                        <span><?=lang($submenu->name)?></span>
                                    </a>
                                </li>
                            <?php }?>

                            <?php if($performance_status['smart_goals'] == 1 && $submenu->route =='smartgoal') {?>
                                <li class="<?php if($page == lang($submenu->name)){echo  "active"; }?>" data-test="<?php echo $r; ?> ts">
                                    <a href="<?=base_url()?><?=$submenu->route?>">
                                        <?php if (isset($badge[$submenu->module])) { echo $badge[$menu->module]; } ?>
                                        <span><?=lang($submenu->name)?></span>
                                    </a>
                                </li>
                            <?php }?>

                                <?php } else  { ?>
                                     <?php 
                                        // if ($submenu->name!='balance_sheet' && $submenu->name!='budget_statement') {
                                        if ($submenu->name!='budget_statement') {
                                           
                                        ?>
                                    <li class="<?php if($page == lang($submenu->name)){echo  "active"; }?>" data-test="<?php echo $r; ?> t">
                                        <a href="<?=base_url()?><?=$submenu->route?>">
                                            <?php if (isset($badge[$submenu->module])) { echo $badge[$menu->module]; } ?>
                                           
                                              <span><?=lang($submenu->name)?></span>
                                          
                                            
                                        </a>
                                    </li>
                                     <?php
                                            }
                                            ?>


                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </ul>

                </li>

                <?php } elseif($menu->route != '#') { 

                if($menu->route =='settings'){

                    if($role_id == 100){
                        $user_id = $this->session->userdata('user_id');
                        $expense_approvers = $this->db->get_where('expense_approvers',array('approvers'=>$user_id))->num_rows();
                        $job_approvers = $this->db->where('appr_id',$user_id)->where_in('app_status',1,2)->get('candidate_assoc')->num_rows();
                        if($expense_approvers > 0 || $job_approvers > 0){ ?>
                            <li class="<?php if($page == lang($menu->name)){echo  "active"; }?>">

                                <a href="<?=base_url()?><?=$menu->route?>">

                                    <?php if (isset($badge[$menu->module])) { echo $badge[$menu->module]; } ?>

                                    <i class="fa <?=$menu->icon?> icon"> <b class="bg-<?=config_item('theme_color');?>"></b></i>

                                    <span><?=lang($menu->name)?></span>

                                </a>

                            </li>
                       <?php   }


                    } else{ ?>
                     <li class="<?php if($page == lang($menu->name)){echo  "active"; }?>">

                        <a href="<?=base_url()?><?=$menu->route?>">

                            <?php if (isset($badge[$menu->module])) { echo $badge[$menu->module]; } ?>

                            <i class="fa <?=$menu->icon?> icon"> <b class="bg-<?=config_item('theme_color');?>"></b></i>

                            <span><?=lang($menu->name)?></span>

                        </a>

                    </li>

                <?php    }

                } else { ?>

                    <li class="<?php if($page == lang($menu->name)){echo  "active"; }?>">

                        <a href="<?=base_url()?><?=$menu->route?>">

                            <?php if (isset($badge[$menu->module])) { echo $badge[$menu->module]; } ?>

                            <i class="fa <?=$menu->icon?> icon"> <b class="bg-<?=config_item('theme_color');?>"></b></i>
                            <?php
                            if ($menu->name=='all_assets') {
                                echo "string";
                            }
                            else
                            {
                                ?>
                                <span><?=lang($menu->name)?></span>
                                <?php
                            }
                             ?>
                            


                            

                        </a>

                    </li>

                <?php }

                    ?>

               
                <?php } ?>

                <?php }  $all_routes = array_unique(array_merge($all_menu_routes,$all_sun_menu_routes), SORT_REGULAR);
                        $all_routes_name= array_values($all_routes);  
                        $this->session->set_userdata('all_routes',$all_routes_name);
                    //echo "<pre>"; print_r( $all_menu_routes); exit;
                    // echo '<pre>'; print_r($all_routes); exit;
                    ?>

            </ul>

        </div>

    </div>

</div>

<?php } ?>