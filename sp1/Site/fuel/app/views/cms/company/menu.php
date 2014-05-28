<div class="sidePad" id="searchEngines">
    <?php if (!empty($logo)): ?>
        <div class="fluid">
    	    <div class="grid12">
            <?= Asset::img(Config::get('cms.logo_images_path').DS.'small_'.$logo); ?>
    		</div>
        </div>
	<?php endif; ?>
	
	<div class="formRow">
		<div class="grid3">
		    No. of merchants: <span><?php echo (isset($merchant_count) ? $merchant_count : 0 ) ?></span>
		</div>
	</div>

    <?=$search_engines?>
</div>
