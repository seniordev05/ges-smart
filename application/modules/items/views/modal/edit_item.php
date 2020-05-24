<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="modal-title"><?=lang('edit_inventory')?></h4>
		</div>
		<?php $item = Item::view_item($id); ?>
		<?php $attributes = array('class' => 'bs-example form-horizontal','id'=>'itemsEditItem'); echo form_open(base_url().'items/edit_item',$attributes); ?>
			<input type="hidden" name="r_url" value="<?=base_url()?>items">
			<input type="hidden" name="item_id" value="<?=$item->item_id?>">
			<div class="modal-body">
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('inventory_name')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?=$item->inventory_name?>" name="item_name">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('inventory_description')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
					<textarea class="form-control ta" name="item_desc"><?=$item->item_desc?></textarea>
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('quantity')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?=$item->quantity?>" name="quantity">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('unit_cost')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" value="<?=$item->unit_cost?>" name="unit_cost">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('selling_price')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<input type="text" class="form-control" placeholder="350.00" name="selling_price" value="<?=$item->selling_price?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-lg-4 control-label"><?=lang('tax_rate')?> <span class="text-danger">*</span></label>
					<div class="col-lg-8">
						<select name="item_tax_rate" class="form-control m-b">
							<!-- <option value="<?=$item->item_tax_rate?>"><?=$item->item_tax_rate?></option> -->
							<option value="0.00" <?php if($item->item_tax_rate == '0.00'){ echo "selected"; } ?>><?=lang('none')?></option>
							<?php foreach ($rates as $key => $tax) { ?>
							<option value="<?=$tax->tax_rate_percent?>" <?php if($item->item_tax_rate == $tax->tax_rate_percent){ echo "selected"; } ?>><?=$tax->tax_rate_name?></option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
			<div class="modal-footer"> <a href="#" class="btn btn-danger" data-dismiss="modal"><?=lang('close')?></a>
				<button type="submit" class="btn btn-success" id="items_edit_item"><?=lang('save_changes')?></button>
			</div>
		</form>
	</div>
</div>