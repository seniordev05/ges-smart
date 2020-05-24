<div class="page-wrapper">
			
	<!-- Content -->
    <div class="content container">
		
		<div class="row">
			<div class="col-sm-8">
				<h4 class="page-title">Onboarding Configuration</h4>
			</div>
		</div>
		
		<div class="row">
			<div class="col-md-12">
				<div class="card-box">
					<p class="m-b-20"><b>Departments or Individuals that need to be notified upon Candidates Acceptance</b></p>
					<form action="<?=base_url()?>offers/onboarding" method="POST">
						<div class="form-group">
							<input type="hidden" class="form-control" value="<?php echo $offer_id;?>" name="offer_id">
							<select class="select2-option form-control" multiple="multiple" style="width:260px" name="boarders_id[]" > 
								<optgroup label="Staff">
									<?php foreach (User::team() as $user): ?>
										<option value="<?=$user->id?>">
											<?=ucfirst(User::displayName($user->id))?>
										</option>
									<?php endforeach ?>
								</optgroup> 
							</select>
							<span class="help-block">Separated with a comma or enter</span>
						</div>
						<div class="submit-section">
							<button class="btn btn-primary submit-btn" type="submit">Save</button>
						</div>
					</form>
				</div>
			</div>
		</div>
		
	</div>
c</div>