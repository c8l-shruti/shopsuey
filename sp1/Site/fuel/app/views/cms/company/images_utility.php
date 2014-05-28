<div class="sidePad" id="imagesNavigation">
    <div class="fluid" id="img-wrapper">
        <div class="grid12">
            <img id="img-src" src="" alt="" />
        </div>
        <div class="grid12">
            <div class="widget">
                <form id="img-crop-form" method="post" action="<?=Uri::create('dashboard/company/image_crop')?>">
                    <input type="hidden" name="type" id="new-img-type" />
                    <input type="hidden" name="image" id="new-img" />
                    <input type="hidden" name="x1" id="new-img-x1" />
                    <input type="hidden" name="y1" id="new-img-y1" />
                    <input type="hidden" name="x2" id="new-img-x2" />
                    <input type="hidden" name="y2" id="new-img-y2" />
                    <input type="hidden" name="preview_width" id="new-img-preview-width" />
                    <input type="hidden" name="preview_height" id="new-img-preview-height" />
                    <fieldset>
        				<div class="formRow">
        				    <div class="grid12">
    				            <button class="buttonL bGreyish fluid" type="submit"><i class="iconb" data-icon="&#xe097;"></i> &nbsp; Resize</button>
				            </div>
				            <div class="clear"></div>
    			        </div>
    		        </fieldset>
    	        </form>
	        </div>
        </div>
    </div>
    
    <div class="fluid">
    	<div class="grid12">
    		<div class="formRow">
                <form class="upload-form" method="post" enctype="multipart/form-data" action="<?=Uri::create('dashboard/company/image_upload')?>">
                    <input type="hidden" name="type" value="logo">
                    <input type="hidden" name="aspect_ratio" value="<?=110/110?>">
        			<div class="grid10"><label for="image">Upload Logo:</label></div>
        			<div class="grid1">
        			    <?= Asset::img('elements/loaders/1s.gif', array('class' => 'image-loader')); ?>
        			</div>
        			<div class="clear"></div>
        			<div class="grid12">
        				<input type="file" name="image" class="image-input" />
        			</div>
        			<div class="image-progressbar"></div>
    			</form>
    		</div>
        </div>
    </div>
    
    <div class="fluid">
    	<div class="grid12">
    		<div class="formRow">
                <form class="upload-form" method="post" enctype="multipart/form-data" action="<?=Uri::create('dashboard/company/image_upload')?>">
                    <input type="hidden" name="type" value="landing">
                    <input type="hidden" name="aspect_ratio" value="<?=320/234?>">
                    <div class="grid10"><label for="image">Upload Landing Screen:</label></div>
        			<div class="grid1">
        			    <?= Asset::img('elements/loaders/1s.gif', array('class' => 'image-loader')); ?>
        			</div>
        			<div class="clear"></div>
        			<div class="grid12">
        				<input type="file" name="image" class="image-input" />
        			</div>
                    <div class="image-progressbar"></div>
    			</form>
    		</div>
    	</div>
    </div>
    
</div>
