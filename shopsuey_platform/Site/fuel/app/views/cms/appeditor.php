<!-- START new app form #form -->
<?php if (@$edit) : ?>

<!-- delete form -->
<?=Form::open(array('id'=>'delete', 'class'=>'main', 'action'=>Config::get('base_url').'developer/delete/', 'method'=>'post'))?>
<?=Form::hidden(array('id' => 'app-id', 'name' => 'id', 'value' => $data['id']))?>
<?=Form::hidden(array('id' => 'app-name', 'name' => 'name', 'value' => $data['name']))?>
<?=Form::close()?>

<!-- refresh secret form -->
<?=Form::open(array('id'=>'refresh', 'class'=>'main', 'action'=>Config::get('base_url').'developer/refresh/', 'method'=>'post'))?>
<?=Form::hidden(array('id' => 'app-id', 'name' => 'id', 'value' => $data['id']))?>
<?=Form::hidden(array('id' => 'app-name', 'name' => 'name', 'value' => $data['name']))?>
<?=Form::close()?>

<!-- editor form -->
<?=Form::open(array('id'=>'form', 'class'=>'main', 'action'=>Config::get('base_url').'developer/update/', 'method'=>'post'))?>
<?=Form::hidden(array('id' => 'app-id', 'name' => 'id', 'value' => $data['id']))?>
<?=Form::hidden(array('id' => 'app-name', 'name' => 'name', 'value' => $data['name']))?>

<?php else : ?>
<?=Form::open(array('id'=>'form', 'class'=>'main', 'action'=>Config::get('base_url').'developer/create/', 'method'=>'post'))?>
<?php endif; ?>
	<?=Form::fieldset_open()?>
    	<div class="widget fluid">
        	<div class="whead">
            	<h6>Application Information</h6>
                <?php if (@$edit) : ?>
                <div class="on_off">
                <span class="floatR"><?=Form::checkbox(array('id' => 'app-delete', 'name'=>'delete', 'value'=>1))?></span>
                <span class="floatR">Delete:&nbsp; &nbsp;</span>
                </div>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
            <div class="fields">
            	<?php if (@$edit) : ?>
                <div class="formRow">
                    <div>
                        <div class="grid2"><?=Form::label('App ID:')?></div>
                        <div class="grid10"><?=@$data['token']?></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <div class="grid2"><?=Form::label('App Secret:')?></div>
                        <div class="grid10"><?=@$data['secret']?> &nbsp; &nbsp; <a href="#" class="secret-refresh"><span class="iconb" data-icon="&#xe162;"></span> &nbsp; reset</a></div>
                        <div class="clear"></div>                
                    </div>
                </div>
                <?php else : ?>
                <div class="formRow">
                    <div><?=Form::label('App Name:', 'app-name')?></div>
                    <div><?=Form::input(array('type'=>'text', 'name'=>'name', 'id'=>'app-name', 'placeholder'=>'My Awesome App', 'class'=>'validate[required]', 'value'=>@$data['name']))?></div>
                </div>
                <?php endif; ?>
                
                <div class="formRow">
                    <div><?=Form::label('Description:', 'app-description')?></div>
                    <div><?=Form::textarea(array('name'=>'description', 'id'=>'app-description', 'placeholder'=>'About My Awesome App!', 'class'=>'validate[required]', 'value'=> stripslashes(@$data['description'])))?></div>                
                </div>
                
                <div class="formRow">
                    <div><?=Form::label('Contact Email:', 'app-email')?></div>
                    <div><?=Form::input(array('type'=>'text', 'name'=>'contact', 'id'=>'app-email', 'placeholder'=>'me@email.com', 'class'=>'validate[required,custom[email]]', 'value'=>@$data['contact']))?></div>                
                </div>
                
                <div class="formRow">
                    <div><?=Form::label('Domains:', 'app-domains')?></div>
                    <div><?=Form::input(array('type'=>'text', 'name'=>'domains', 'id'=>'app-domains', 'placeholder'=>'add domain', 'class'=>'tags', 'value'=>@$data['domains']))?></div>
                </div>
                
                <div class="formRow">
                    <div><?=Form::label('Tags:', 'app-tags')?></div>
                    <div><?=Form::input(array('type'=>'text', 'name'=>'tags', 'id'=>'app-tags', 'placeholder'=>'add tag', 'class'=>'tags', 'value'=>@$data['tags']))?></div>
                </div>
            </div>
        </div>
    <?=Form::fieldset_close()?>
    
    <p></p>
    
    <!-- #form submit -->
    <div class="textR">
    <a class="buttonL bGreyish save-button" href="#">
        <span class="icon-checkmark"></span>
        <span class="text"><?=@$save_title?></span>
    </a>
    </div>
    <div class="clear"></div>
    
<?=Form::close()?>
<!-- END new app form #form -->

<div id="dialog-delete" title="Confirm Delete">
    <p align="center">Deleting an application cannot be undone.<br><strong>Are you sure?</strong></p>
</div>

<div id="dialog-refresh" title="Warning">
	<p align="center">Refreshing the secret key cannot be undone.<br>Any usage of the current secret key will not be allowed by the API.<br><strong>Are you sure?</strong></p>
</div>
