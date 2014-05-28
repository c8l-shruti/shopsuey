<form method="post" action="<?=Uri::create('dashboard/company/images')?>">

<div class="fluid">
    <div class="grid12">
        <div class="grid3">
    		<div class="widget"><!-- Logo -->
    			<div class="whead"><h6><span class="icon-picture"></span>Logo Preview</h6><div class="clear"></div></div>
    			<fieldset>
    				<div class="formRow">
    				    <div class="logo-img-preview-container">
                            <?php if (!empty($location->logo)): ?>
                    	        <?= Asset::img(Config::get('cms.logo_images_path').DS.'large_'.$location->logo, array('class' => 'logo-img-preview')); ?>
                	        <?php else: ?>
                    	        <?= Asset::img('logo_default.jpg', array('class' => 'logo-img-preview')); ?>
                	        <?php endif; ?>
            	        </div>
    				</div>
    			</fieldset>
    		</div>
        </div>
    
        <div class="grid5">
    		<div class="widget"><!-- Logo -->
    			<div class="whead"><h6><span class="icon-picture"></span>Landing Image preview</h6><div class="clear"></div></div>
    			<fieldset>
    				<div class="formRow">
        	            <div class="landing-img-preview-container">
                            <?php if (!empty($location->landing_screen_img)): ?>
                    	        <?= Asset::img(Config::get('cms.landing_images_path').DS.'large_'.$location->landing_screen_img, array('class' => 'landing-img-preview')); ?>
                            <?php else: ?>
                    	        <?= Asset::img('landing_image_default.jpg', array('class' => 'landing-img-preview')); ?>
                            <?php endif; ?>
                        </div>
    				</div>
    			</fieldset>
    		</div>

    		<div class="widget">
    			<div class="whead"><h6><span class="icon-picture"></span>Instagram integration</h6><div class="clear"></div></div>
    			<fieldset>
    				<div class="formRow">
    					<div class="grid10"><label for="use_instagram">Use instagram feed for landing image:</label></div>
    					<div class="grid2">
    						<input type="checkbox" id="use_instagram" name="use_instagram" value="1" <?php if ($location->use_instagram): ?>checked="checked"<?php endif; ?> <?php if (!$instagram_set): ?>disabled="disabled"<?php endif; ?>/>
    						<?php if ($instagram_set && $instagram_callback):?>
    						<input type="hidden" id="set_user_account" name="set_user_account" value="1"/>
    						<?php endif;?>
    						
						</div>
    					<div class="clear"></div>
    				</div>
    				<div class="formRow">
    					<div class="grid12">
				            <?php if ($instagram_set):?>
    					    <?=\Html::img($instagram_latest_post->images->standard_resolution->url, array('class' => 'instagram_preview', 'id' => 'instagram_preview'))?>
    					    <?php else: ?>
    					    <img src="" class="instagram_preview" id="instagram_preview"/>
    					    <?php endif; ?>
    					</div>
					    <div class="clear"></div>
    				</div>
					<?php if (!$instagram_set):?>
    				<div class="formRow">
    					<div class="grid12">
    					    <a class="buttonL bBlue fluid instagram_button" title="" href="<?=$instagram_auth_url?>"><i class="iconb" data-icon="&#xe097;"></i> &nbsp; Setup Instagram</a>
					    </div>
					    <div class="clear"></div>
    				</div>
				    <?php else: ?>
    				<div class="formRow">
    					<div class="grid12">
    					    <a class="buttonL bGreen fluid instagram_button" title="" href="<?=$instagram_auth_url?>"><i class="iconb" data-icon="&#xe097;"></i> &nbsp;Using <?=$instagram_username?>. Want to change it?</a>
					    </div>
					    <div class="clear"></div>
    				</div>
				    <?php endif; ?>
				</fieldset>
    		</div>
        </div>
    
        <div class="grid3">
    		<div class="widget"><!-- Logo -->
    			<div class="whead"><h6><span class="icon-picture"></span>Explore Icon preview</h6><div class="clear"></div></div>
    			<fieldset>
    				<div class="formRow">
    				    <div class="logo-img-preview-container">
                            <?php if (!empty($location->logo)): ?>
                    	        <?= Asset::img(Config::get('cms.logo_images_path').DS.'large_'.$location->logo, array('class' => 'logo-img-preview')); ?>
                	        <?php else: ?>
                    	        <?= Asset::img('logo_default.jpg', array('class' => 'logo-img-preview')); ?>
                	        <?php endif; ?>
            	        </div>
    				</div>
    			</fieldset>
    		</div>
        
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="fluid">
    <div class="grid12">
        <div class="grid4">
        &nbsp;
        </div>
        <div class="grid4">
        &nbsp;
        </div>
        <div class="grid4">
    		<div class="widget"><!-- Publish -->
    			<fieldset class="formpart">
    				<div class="formRow">
    				    <button class="buttonL bGreyish fluid" type="submit"><i class="iconb" data-icon="&#xe097;"></i> &nbsp; Done</button>
    				</div>
    			</fieldset>
    		</div>
        </div>
    </div>
</div>

<div id="instagram_logout"></div>

</form>

<script type="text/javascript">

var image_type = null;
var logo;
var jcrop_api;
var imgHeight;
var imgWidth = 200;

$('input[type="file"].image-input').change(function() {
	var parent_form = $(this).parents("form");
    // submit the form 
    parent_form.ajaxSubmit({
	    dataType: "json",
	    error: function() {
		    alert("Error while storing the file. Make sure you are trying to upload a valid image format");
		    parent_form.find('.image-loader').hide();
	    	parent_form.find('.image-progressbar').progressbar( "option", "value", 0 );
	    },
	    beforeSubmit: function(arr, $form, options) {
	    	parent_form.find('.image-loader').show();
	    	parent_form.find('.image-progressbar').progressbar( "option", "value", 0 );
	    },
	    success: function(response) {
		    if (jcrop_api) {
		        jcrop_api.destroy();
		    }
		    
		    image_type = parent_form.children('input[name="type"]').val();
		    
		    $("#img-src").replaceWith($('<img/>', {
			    id: "img-src",
			    src: response.url,
			    width: 200
		    }));
		    $("." + image_type + "-img-preview").attr("src", response.url);
		    $('#new-img').val(response.path);
		    $('#new-img-type').val(image_type);
		    
		    $("#img-src").load(function() {
		        $('#img-wrapper').show();
			    imgHeight = $("#img-src").height();
			    $('#new-img-preview-height').val($("#img-src").height());
			    $('#new-img-preview-width').val($("#img-src").width());
			    initJCrop($('#img-src'));
		    });

		    parent_form.find('.image-loader').hide();
	    	parent_form.find('.image-progressbar').progressbar( "option", "value", 0 );
        },
	    uploadProgress: function(event, position, total, percentComplete) {
	    	parent_form.find('.image-progressbar').progressbar( "option", "value", percentComplete );
		    console.log("Percent complete: " + percentComplete);
	    }
    }); 
});

function showPreview(coords)
{
    if (parseInt(coords.w) > 0)
    {
    	var rx = $("." + image_type + "-img-preview-container").width() / coords.w;
    	var ry = $("." + image_type + "-img-preview-container").height() / coords.h;

    	$("." + image_type + "-img-preview").css({
    		width: Math.round(rx * imgWidth) + 'px',
    		height: Math.round(ry * imgHeight) + 'px',
    		marginLeft: '-' + Math.round(rx * coords.x) + 'px',
    		marginTop: '-' + Math.round(ry * coords.y) + 'px'
    	}).show();

    	$('#new-img-x1').val(coords.x);
    	$('#new-img-y1').val(coords.y);
    	$('#new-img-x2').val(coords.x2);
    	$('#new-img-y2').val(coords.y2);
    }
}

function hidePreview()
{
	$('#logo_img_preview').stop().fadeOut('fast');
}

function initJCrop(selector) {
	var jcrop_options = {
        onChange: showPreview,
        onSelect: showPreview,
        onRelease: hidePreview,
        aspectRatio: $("." + image_type + "-img-preview-container").width() / $("." + image_type + "-img-preview-container").height(),
        setSelect: [ 0, 0, $("." + image_type + "-img-preview-container").width(), $("." + image_type + "-img-preview-container").height() ],
        allowSelect: false
    }
	
	selector.Jcrop(jcrop_options, function() {
	    jcrop_api = this;
	});

}

$(function() {
    $('#img-wrapper').hide();
    $('.image-loader').hide();
    $('.image-progressbar').progressbar({
    	value: 0
    });
});

$('a.instagram_button').click(function(event) {
	$('#instagram_logout').html('<iframe id="logout_iframe" src="<?=$instagram_logout_url?>" width="0" height="0">');

    $('#logout_iframe').load(function() {
	    window.location.replace("<?=$instagram_auth_url?>");
    });
    
	event.preventDefault();
});

</script>
