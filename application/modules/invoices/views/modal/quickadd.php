<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button> <h4 class="modal-title"><?=lang('add_item')?></h4>
		</div>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'invoiceAddItem'); echo form_open(base_url().'invoices/items/insert',$attributes); ?>
			<input type="hidden" name="invoice" value="<?=$invoice?>">
			<div class="modal-body">
				<div class="form-group">
					<div>
						<label class="col-lg-4 control-label"><?=lang('item_name')?> <span class="text-danger">*</span></label>
						<div class="col-lg-8">
						<select name="item" class="form-control item_selected" required="required">
							<option value=""><?=lang('choose_template')?></option>
							<?php foreach (Invoice::saved_items_where() as $key => $item) { ?>
							<option value="<?=$item->item_id?>"><?=$item->item_name?> - <?=$item->unit_cost?></option>
							<?php } ?>					
						</select>
						<br>
						</div>

					</div>
					<div>
						<label class="col-lg-4 control-label">Add Quantity <span class="text-danger available_qty_rest">*</span></label>
						<div class="col-lg-8">
							<input type="number" required="required" name="add_qty" class="form-control">
						</div>
					</div><br>

					


				</div>
				<div class="modal-footer">
						<a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a> 
						<button class="btn btn-success" id="inview_add_item"><?=lang('add_item')?></button>
					</div>


				</div>

				

			</div>
			
		</form>
	</div>
</div>


<script type="text/javascript">	
//for items ajax
$(document).on("change", ".item_selected", function() {
	var item_id=$(this).val();
	$.ajax({
			type: "POST",
			url: base_url + 'invoices/items/get_item_data',
			data:  {item_id:item_id},
			success: function (data) {                                    
				$('.available_qty_rest').html('Available '+data);                           
			}
	});
});
</script>