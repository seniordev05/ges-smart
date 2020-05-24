<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-body">
			<?php echo form_open(base_url().'profile/delete'); ?>
				<div class="form-head">
					<h3>DELET ATENDANCE EMAIL</h3>
					<p>Please confirm again.</p>
					<p>Are you sure want to delete?</p>
				</div>
				<input type="hidden" name="act_id" value="<?=$act_id?>">
				<div class="modal-btn delete-action">
					<div class="row">
						<div class="col-xs-6">
							<button type="submit" class="btn continue-btn">Delete</button>
						</div>
						<div class="col-xs-6">
							<a href="javascript:void(0);" data-dismiss="modal" class="btn cancel-btn">Cancel</a>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>