<div class="content">
	<div class="row">
		<div class="col-sm-8">
			<h4 class="page-title"><?=lang('notice_board')?></h4>
		</div>
        <div class="col-sm-4 text-right m-b-20">
            <a class="btn add-btn" href="<?=base_url()?>notice_board/add/" data-toggle="ajaxModal"><i class="fa fa-plus"></i> Add Notice Board</a>
        </div>
	</div>
    <div class="row">
        <!-- Project Tasks -->
        <div class="col-lg-12">
                <div class="table-responsive">
                    <table id="notice_board" class="table table-striped custom-table m-b-0 AppendDataTables">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>                                 
                                <th>Description</th>
                                <th class="col-options no-sort text-right"><?=lang('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php                                      
                                if (!empty($notice_boards)) 
                                {
                                    $j =1;
                                    foreach ($notice_boards as $key => $notice_board) 
                                    { ?>
                                        <tr>
                                            <td> <?php echo $j; ?> </td>
                                            <td> <?=$notice_board->title?> </td> 
                                            <td> <?=$notice_board->description?> </td>               
                                            <td class="text-right"> 
                                                <a href="<?=base_url()?>notice_board/edit/<?=$notice_board->id?>" class="btn btn-success btn-xs" data-toggle="ajaxModal">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="<?=base_url()?>notice_board/delete/<?=$notice_board->id?>" class="btn btn-danger btn-xs" data-toggle="ajaxModal" title="" data-original-title="Delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>                         
                                            </td>
                                        </tr>
                                        <?php $j++; 
                                    } 
                                } 
                                else
                                { ?>
                                    <tr>No Results</tr>
                                <?php 
                                } ?>
                        </tbody>
                    </table>
                </div>
        </div>
        <!-- End Project Tasks -->
    </div>
</div>