<?php
$this->load->helper('app'); 
$template_group = isset($_GET['group'])?$_GET['group']:'user';
switch ($template_group) {
    case "bugs": $default = "bug_assigned"; break;
    case "extra": $default = "estimate_email"; break;
    case "invoice": $default = "invoice_message"; break;
    case "project": $default = "project_assigned"; break;
    case "task": $default = "task_created"; break;
    case "ticket": $default = "ticket_client_email"; break;
    case "user": $default = "activate_account"; break;
    case "signature": $default = "email_signature"; break;
}
$setting_email = isset($_GET['email'])?$_GET['email']:$default;

$email['bugs'] = array("bug_assigned","bug_status","bug_comment","bug_file","bug_reported");
$email['extra'] = array("estimate_email","message_received");
$email['invoice'] = array("invoice_message","invoice_reminder","payment_email");
$email['project'] = array("project_created","project_assigned","project_comment","project_complete","project_file","project_updated");
$email['task'] = array("task_created","task_assigned","task_updated","task_comment");

$email['ticket'] = array("ticket_client_email","ticket_closed_email","ticket_reply_email","ticket_staff_email","auto_close_ticket","ticket_reopened_email");
$email['user'] = array("activate_account","change_email","forgot_password","registration","reset_password");
$email['signature'] = array("email_signature");

$attributes = array('class' => 'bs-example form-horizontal');
echo form_open('settings/templates?settings=templates&group='.$template_group.'&email='.$setting_email, $attributes);
?>
    <div class="p-0">
        <div class="col-lg-12 p-0">
            <div class="panel panel-white">
                <div class="panel-heading">
					<h3 class="panel-title p-5"><?=lang('email_templates')?></h3>
				</div>
                <div class="panel-body">
					<ul class="nav nav-tabs nav-tabs-solid m-b-20">
						<?php foreach ($email[$template_group] as $temp) :
						$lang = $temp;
						switch($temp) {
							case "registration": $lang = 'register_email'; break;
							case "bug_comment": $lang = 'bug_comments'; break;
							case "project_file": $lang = 'project_files'; break;
							case "project_comment": $lang = 'project_comments'; break;
							case "project_assigned": $lang = 'project_assignment'; break;
							case "task_assigned": $lang = 'task_assignment'; break;
							case "email_signature": $lang = 'email_signature'; break;
						} ?>
						<li class="<?php if($setting_email == $temp){ echo "active"; } ?> ">
							<a href="<?=base_url()?>settings/?settings=templates&group=<?=$template_group;?>&email=<?=$temp;?>"><?=lang($lang)?></a>
						</li>
					<?php endforeach; ?>
					</ul>
                    <input type="hidden" name="email_group" value="<?=$setting_email;?>">
                    <input type="hidden" name="return_url" value="<?=base_url()?>settings/?settings=templates&group=<?=$template_group;?>&email=<?=$setting_email;?>">
                    <?php if ($template_group != 'signature') : ?>
                    <div class="form-group">
                        <label class="col-lg-12"><?=lang('subject')?></label>
                        <div class="col-lg-12">
                            <input class="form-control" name="subject" value="<?php echo App::email_template($setting_email,'subject');?>" />
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="col-lg-12"><?=lang('message')?></label>
						<div class="col-lg-12">
							<textarea class="form-control foeditor-550" name="email_template"><?php echo App::email_template($setting_email,'template_body');?></textarea>
						</div>
                    </div>
					<div class="text-center m-t-30">
                        <button type="submit" class="btn btn-primary btn-lg"><?=lang('save_changes')?></button>
					</div>
                </div>
				<div class="panel-footer">
					<strong><?=lang('template_tags')?></strong>
					<ul>
						<?php $tags = get_tags($setting_email); foreach ($tags as $key => $value) { echo '<li>{'.$value.'}</li>'; } ?>
					</ul>
				</div>
            </div>
        </div>
    </div>
</form>