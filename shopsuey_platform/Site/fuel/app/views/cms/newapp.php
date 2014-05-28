<!-- START new app form #form -->
<?php if (@$edit) : ?>

<?=Form::open(array('id'=>'delete', 'class'=>'main', 'action'=>Config::get('base_url').'developer/delete/', 'method'=>'post'))?>
<?=Form::hidden(array('id' => 'app-id', 'name' => 'id', 'value' => $data['id']))?>
<?=Form::hidden(array('id' => 'app-name', 'name' => 'name', 'value' => $data['name']))?>
<?=Form::close()?>

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
                <?=Form::label('Delete:&nbsp; &nbsp; ')?>
                <?=Form::checkbox(array('id' => 'app-delete', 'name'=>'delete', 'value'=>1))?>
                </div>
                <?php endif; ?>
                <div class="clear"></div>
            </div>
            <div class="fields">
            	<?php if (@$edit) : ?>
                <div class="formRow">
                    <div>
                        <div class="grid2"><?=Form::label('App ID:')?></div>
                        <div class="grid10"><?=@$data['appid']?></div>
                        <div class="clear"></div>
                    </div>
                    <div>
                        <div class="grid2"><?=Form::label('App Secret:')?></div>
                        <div class="grid10"><?=@$data['secret']?></div>
                        <div class="clear"></div>                
                    </div>
                </div>
                <?php else : ?>
                <div class="formRow">
                    <div><?=Form::label('App Name:', 'app-name')?></div>
                    <div><?=Form::input(array('type'=>'text', 'name'=>'name', 'id'=>'app-name', 'placeholder'=>'My Awesome App', 'class'=>'validate[required]'))?></div>
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

<div id="dialog-modal" title="Confirm Delete">
    <p align="center">Deleting an application can not be undone.<br><strong>Are you sure?</strong></p>
</div>
