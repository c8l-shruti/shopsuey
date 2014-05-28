<body class="background with-logo">
    <div class="content">
        <p>You are logged in: <?=$business_name?>. <a class="floatR" href="<?= Uri::create('logout') ?>">Not you? Logout</a></p>
        <div class="login-wrapper">
            <form method="POST" enctype="multipart/form-data">
                <?= CMS::create_nonce_field('create', 'nonce', 'create_nonce') ?>

                <div class="step-counter">Step 2 of 3</div>

                <?=CMS::field_error(@$notice, null)?>
                
                <div class="image-selectors">
                    <div class="floatL">
                        <strong>Landing Image on File</strong>
                        <p class="mt15 mb15">
                            <input type="checkbox" name="landing_instagram" id="landing-instagram" value="1" <?=$instagram_set ? 'checked="checked"' : ''?>>
                            &nbsp;<strong>Use my Instagram feed</strong>
                        </p>
                        <div>
                            <?php if ($instagram_set) : ?>
                                <?=\Html::img($instagram_latest_post->images->standard_resolution->url, array('width' => 200, 'id' => 'landing-img-preview'))?>
                                <div class="no-image" id="landing-no-image" style="display: none;">No image</div>
                            <?php elseif ($location->landing_screen_img) : ?>
                                <?= Asset::img(Config::get('cms.landing_images_path') . DS . 'small_' . $location->landing_screen_img, array('width' => 200, 'id' => 'landing-img-preview')); ?>
                            <?php else: ?>
                                <img alt="" src="" id="landing-img-preview" />
                                <div class="no-image" id="landing-no-image">No image</div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="img-actions-wrapper">
                                <div style="position: relative; overflow:hidden">
                                    <input type="file" name="landing" class="fake-file-input" />
                                    <a href="#"> + Choose New File</a>
                                </div>
                                <div style="width:200px;<?php if (!$instagram_set): ?> display:none;<?php endif; ?>" class="mt10">
                                    <input type="checkbox" name="replace_landing_in_all_stores" id="replace_landing_in_all_stores" value="1">
                                    <label for="replace_landing_in_all_stores">Replace landing image in all stores with this image</label>
                                </div>
                                <input type="hidden" name="x1_landing" id="landing-img-x1" />
                                <input type="hidden" name="y1_landing" id="landing-img-y1" />
                                <input type="hidden" name="x2_landing" id="landing-img-x2" />
                                <input type="hidden" name="y2_landing" id="landing-img-y2" />
                                <input type="hidden" name="preview_width_landing" id="landing-img-preview-width" />
                                <input type="hidden" name="preview_height_landing" id="landing-img-preview-height" />
                            </div>
                        </div>
                    </div>

                    <div class="floatL" style="margin-left:98px">
                        <strong>Logo Image on File</strong>
                        <div style="padding-top:51px;">
                            <?php if ($location->logo) : ?>
                                <?= Asset::img(Config::get('cms.logo_images_path') . DS . 'small_' . $location->logo, array('width' => 200, 'id' => 'logo-img-preview')); ?>
                            <?php else: ?>
                                <img alt="" src="" id="logo-img-preview" />
                                <div class="no-image" id="logo-no-image">No image</div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <div class="img-actions-wrapper">
                                <div style="position: relative; overflow: hidden">
                                    <input type="file" name="logo" class="fake-file-input" />
                                    <a href="#"> + Choose New File</a>
                                </div>
                                <div style="display:none; width:200px;" class="mt10">
                                    <input type="checkbox" name="replace_logo_in_all_stores" id="replace_logo_in_all_stores" value="1">
                                    <label for="replace_logo_in_all_stores">Replace logo in all stores with this image</label>
                                </div>
                                <input type="hidden" name="x1_logo" id="logo-img-x1" />
                                <input type="hidden" name="y1_logo" id="logo-img-y1" />
                                <input type="hidden" name="x2_logo" id="logo-img-x2" />
                                <input type="hidden" name="y2_logo" id="logo-img-y2" />
                                <input type="hidden" name="preview_width_logo" id="logo-img-preview-width" />
                                <input type="hidden" name="preview_height_logo" id="logo-img-preview-height" />
                            </div>
                        </div>
                    </div>

                    <div class="clear"></div>
                </div>

                <div class="mt30" style="position: relative">
                    <?= Asset::img('ios-devices.png', array('width' => 500)) ?>

                    <div style="position: absolute; left:331px; top:234px; width: 30px; height:30px; overflow: hidden">
                        <?php if ($location->logo) : ?>
                            <?= Asset::img(Config::get('cms.logo_images_path') . DS . 'small_' . $location->logo, array('width' => 30, 'height' => 30, 'id' => 'logo-img-preview-ios')); ?>
                        <?php else: ?>
                            <?= Asset::img('logo_default.jpg', array('width' => 29, 'height' => 30, 'id' => 'logo-img-preview-ios')); ?>
                        <?php endif; ?>
                    </div>

                    <div style="position: absolute; left:17px; top:87px; width: 153px; height:113px; overflow: hidden">
                        <?php if ($instagram_set) : ?>
                            <?=\Html::img($instagram_latest_post->images->standard_resolution->url, array('width' => 153, 'height' => 113, 'id' => 'landing-img-preview-ios'))?>
                        <?php elseif ($location->landing_screen_img) : ?>
                            <?= Asset::img(Config::get('cms.landing_images_path') . DS . 'small_' . $location->landing_screen_img, array('width' => 153, 'height' => 113, 'id' => 'landing-img-preview-ios')); ?>
                        <?php else: ?>
                            <?= Asset::img('logo_big.png', array('width' => 153, 'id' => 'landing-img-preview-ios')); ?>
                        <?php endif; ?>
                    </div>

                    <?= Asset::img('ios-devices-map.png', array('width' => 42, 'height' => 42, 'style' => "position: absolute; left:426px; top:178px;")) ?>
                    <span class="ios-device-title" style="position: absolute; left: 38px; top: 69px;text-align: center;width: 109px;"><?= $location->name ?></span>
                    <span class="ios-device-list-title" style="position: absolute; left:364px; top:238px;"><?= $location->name ?></span>
                </div>

                <div class="mt30" style="text-align: center">
                    <strong>Select categories that apply to your business</strong>
                    <div class="categories-wrapper">
                        <select name="categories[0]">
                            <option disabled selected>Select a category</option>
                            <?php foreach (CMS::categories() as $category) : ?>
                                <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="categories[1]">
                            <option disabled selected>Select a category</option>
                            <?php foreach (CMS::categories() as $category) : ?>
                                <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <select name="categories[2]">
                            <option disabled selected>Select a category</option>
                            <?php foreach (CMS::categories() as $category) : ?>
                                <option value="<?= $category->id ?>"><?= $category->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="actionsWrapper">
                    <div class="mt25 mb25">
                        <input class="big-text" type="submit" value="Next" name="submit">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="setup-instagram-dialog">
        <p>
            Uploaded images will be lost, are you sure you want to proceed?
        </p>
    </div>
</body>


<script type="text/javascript">
    var jcrop_api = {
        "logo": null,
        "landing": null
    };
    var image_uploaded = false;

    $('input[type="file"]').change(function(e) {
        var name = $(this).attr('name');

        if (jcrop_api[name]) {
            jcrop_api[name].destroy();
        }

        $('#' + name).val('');

        if (window.File && window.FileReader && window.FileList && window.Blob) {
            e.preventDefault();

            var file = $(this)[0].files[0];

            if (!file.type.match('image/.*')) {
                alert("Only image files allowed");
                return;
            }

            var reader = new FileReader();

            reader.onload = function(event) {
                $('#' + name + '-img-preview').replaceWith($('<img/>', {
                    id: name + "-img-preview",
                    src: event.target.result,
                    width: 200
                }));

                $('#' + name + '-no-image').hide();

                $('#replace_' + name + '_in_all_stores').parent().show();

                $('#' + name + '-instagram').prop('checked', false);
                
                $('#' + name + '-img-preview-ios').attr('src', event.target.result)

                $('#' + name + '-img-preview').load(function() {

                    $('#' + name + '-img-preview-height').val($('#' + name + '-img-preview').height());
                    $('#' + name + '-img-preview-width').val($('#' + name + '-img-preview').width());

                    initJCrop($('#' + name + '-img-preview'), name);
                });

                image_uploaded = true;
            };

            reader.readAsDataURL(file);
        }
    });

    function getShowPreviewFunction(name) {
        return function(coords) {
            if (parseInt(coords.w) > 0)
            {
                $('#' + name + '-img-x1').val(coords.x);
                $('#' + name + '-img-y1').val(coords.y);
                $('#' + name + '-img-x2').val(coords.x2);
                $('#' + name + '-img-y2').val(coords.y2);


                if (name == 'logo') {
                    var rx = 30 / coords.w;
                    var ry = 30 / coords.h;
                } else if (name == 'landing') {
                    var rx = 153 / coords.w;
                    var ry = 113 / coords.h;
                }

                $('#' + name + '-img-preview-ios').css({
                    width: Math.round(rx * $('#' + name + '-img-preview').width()) + 'px',
                    height: Math.round(ry * $('#' + name + '-img-preview').height()) + 'px',
                    marginLeft: '-' + Math.round(rx * coords.x) + 'px',
                    marginTop: '-' + Math.round(ry * coords.y) + 'px'
                });
            }
        };
    }

    function getHidePreviewFunction(name) {
        return function() {
            $('#' + name + '-img-preview').stop().fadeOut('fast');
            $('#' + name + '-img-x1').val(0);
            $('#' + name + '-img-y1').val(0);
            $('#' + name + '-img-x2').val(0);
            $('#' + name + '-img-y2').val(0);
        };
    }

    function initJCrop(selector, name) {
        var jcrop_options = {
            onChange: getShowPreviewFunction(name),
            onSelect: getShowPreviewFunction(name),
            onRelease: getHidePreviewFunction(name),
            aspectRatio: name == "logo" ? 1 : 320 / 235,
            setSelect: [0, 0, 100, 100],
        };

        selector.Jcrop(jcrop_options, function() {
            jcrop_api[name] = this;
        });
    }

    function set_default_landing() {
        <?php if ($location->landing_screen_img): ?>
        $('#landing-img-preview').attr('src', "<?=Asset::get_file(Config::get('cms.landing_images_path') . DS . 'small_' . $location->landing_screen_img, 'img')?>");
        $('#landing-img-preview-ios').attr('src', "<?=Asset::get_file(Config::get('cms.landing_images_path') . DS . 'small_' . $location->landing_screen_img, 'img')?>");
        <?php else: ?>
        $('#landing-img-preview').attr('src', "");
        $('#landing-img-preview').attr('alt', "");
        $('#landing-img-preview-ios').attr('src', "<?=Asset::get_file('logo_big.png', 'img')?>");
        $('#landing-no-image').show();
        $('#replace_landing_in_all_stores').parent().hide();
        <?php endif; ?>
        $('#landing-img-preview-ios').css({
            width: '153px',
            height: '113px',
            marginLeft: '0px',
            marginTop: '0px'
        });
    };
    
    var instagram_set = <?=$instagram_set ? 'true' : 'false'?>;
    var instagram_url = "<?=$instagram_auth_url?>";

    $('#landing-instagram').change(function() {
        if ($(this).prop('checked') && !instagram_set) {
            if (image_uploaded) {
                $( "#setup-instagram-dialog" ).dialog( "open" );
            } else {
            	window.location.replace(instagram_url);
            }
        <?php if ($instagram_set): ?>        	
        } else if($(this).prop('checked')) {
            if (jcrop_api['landing']) {
                jcrop_api['landing'].destroy();
            }
            $('#landing-img-preview').replaceWith($('<img/>', {
                id: "landing-img-preview",
                src: "<?=$instagram_latest_post->images->standard_resolution->url?>",
                width: 200
            }));
            $('#landing-no-image').hide();
            $('#replace_landing_in_all_stores').parent().show();
            $('#landing-img-preview-ios').attr('src', "<?=$instagram_latest_post->images->standard_resolution->url?>");
            $('#landing-img-preview-ios').css({
                width: '153px',
                height: '113px',
                marginLeft: '0px',
                marginTop: '0px'
            });
        <?php endif; ?>
        } else {
            set_default_landing();
        }
    });

    $( "#setup-instagram-dialog" ).dialog({
        autoOpen: false,
        width: 500,
        height: 175,
        modal: true,
        buttons: {
            "Ok": function() {
            	window.location.replace(instagram_url);
            },
            "Cancel": function() {
            	$('#landing-instagram').prop('checked', false);
                $( this ).dialog( "close" );
            }
        },
        title: "Instagram Integration",
        resizable: false
    });
        
</script>