<form id="wizard1" method="POST" enctype="multipart/form-data">

    <div class="fluid">
        
        <div class="grid9">
            <div class="widget">
                <div class="whead"><h6><span class="icon-tag"></span>Brand info</h6><div class="clear"></div></div>
                <fieldset>
                    <div class="formRow">
                        <div class="grid3"><label for="mname">Name:</label></div>
                        <div class="grid9">
                                <input type="text" id="name" name="name" placeholder="What's the brand name?" value="<?=$profiling_choice->name?>" class="required" />
                        </div>
                        <div class="clear"></div>
                    </div>
                </fieldset>
            </div>

            <div class="widget">
                <div class="whead"><h6><span class="icon-tag"></span>Category (Pick up to three)</h6><div class="clear"></div></div>
                <fieldset>
                    <div class="formRow">

                        <div class="searchDrop">
                            <?php $profiling_choice_categories = isset($profiling_choice->categories) ? array_keys($profiling_choice->categories) : array(); ?>
                            <select class="fullwidth" data-placeholder="Select..." name="category_ids[]" multiple="multiple">
                                <option></option>
                                <?php foreach(CMS::categories() as $category) : ?>
                                <option value="<?=$category->id?>" <?php echo (in_array($category->id, $profiling_choice_categories)) ? 'selected="selected"' : ''; ?>><?=$category->name?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        
        <div class="grid3">
            <div class="widget">
                <div class="whead">
                    <h6><span class="icon-cog"></span>Publish</h6>
                    <div class="clear"></div>
                </div>
                
                <?php if ($action == 'edit') : ?>
                <div class="formRow">
                    <select name="status" class="fullwidth" data-placeholder="Status">
                        <option value="0" selected="selected">Active</option>
                        <option value="1">Deleted</option>
                    </select>
                </div>
                <?php endif; ?>
                
                <fieldset class="formpart">

                    <div class="formRow">
                        <button class="buttonL bGreyish fluid" type="submit">
                            <?=(!isset($profiling_choice->id)) ? '<i class="iconb" data-icon="&#xe099;"></i> &nbsp; Add Profiling Choice' : '<i class="iconb" data-icon="&#xe097;"></i> &nbsp; Save Profiling Choice'?>
                        </button>
                    </div>

                </fieldset>
            </div>
            
            <div class="widget"><!-- Image -->
                <div class="whead">
                    <h6>
                        <span class="icon-picture"></span>Profiling Image
                    </h6>
                    <div class="clear"></div>
                </div>
                
                <fieldset>
                    <div class="formRow check">
                        <img src="<?=(isset($profiling_choice->url)) ? $profiling_choice->url : ''; ?>" id="img-preview"/>
                        <div>
                            <input type="file" name="image"/>
                        </div>
                        
                        <input type="hidden" name="x1_0" id="new-img-x1" />
                        <input type="hidden" name="y1_0" id="new-img-y1" />
                        <input type="hidden" name="x2_0" id="new-img-x2" />
                        <input type="hidden" name="y2_0" id="new-img-y2" />
                        <input type="hidden" name="preview_width_0" id="new-img-preview-width" />
                        <input type="hidden" name="preview_height_0" id="new-img-preview-height" />

                        <div class="clear"></div>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</form>

<style>
    #img-preview { width: 100% }
    
</style>
    
<script type="text/javascript">
var jcrop_api;

$('input[type="file"]').change(function(e) {

    if (jcrop_api) {
        jcrop_api.destroy();
    }

	if (window.File && window.FileReader && window.FileList && window.Blob) {
	    e.preventDefault();
               
	    var file = $(this)[0].files[0];

        if (!file.type.match('image/.*')) {
            alert("Only image files allowed");
            return;
        }

        var reader = new FileReader();
    
        reader.onload = function (event) {
		    $('#img-preview').replaceWith($('<img/>', {
			    id: "img-preview",
			    src: event.target.result,
			    width: 200
		    }));
            
		    $('#img-preview').load(function() {

		        $('#new-img-preview-height').val($('#img-preview').height());
		        $('#new-img-preview-width').val($('#img-preview').width());

		        initJCrop($('#img-preview'));
		    });
        };

        reader.readAsDataURL(file);
	}
});

function showPreview(coords)
{
    if (parseInt(coords.w) > 0)
    {
    	$('#new-img-x1').val(coords.x);
    	$('#new-img-y1').val(coords.y);
    	$('#new-img-x2').val(coords.x2);
    	$('#new-img-y2').val(coords.y2);
    }
}

function hidePreview()
{
	$('#offer_img_preview').stop().fadeOut('fast');
	$('#new-img-x1').val(0);
	$('#new-img-y1').val(0);
	$('#new-img-x2').val(0);
	$('#new-img-y2').val(0);
}

function initJCrop(selector) {
	var jcrop_options = {
        onChange: showPreview,
        onSelect: showPreview,
        onRelease: hidePreview,
        setSelect: [ 0, 0, 100, 100 ],
//        allowSelect: false
    }
	
	selector.Jcrop(jcrop_options, function() {
	    jcrop_api = this;
	});
}

</script>
