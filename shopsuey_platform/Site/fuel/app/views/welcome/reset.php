<body>
<div class="wrapper<?=(isset($notice)) ? ' slideDown' : ' hide'?>">
<?=CMS::note(@$notice['message'], @$notice['type'])?>
</div>
<!-- New Pass wrapper begins -->
<div class="loginWrapper">
	<!-- New password form -->
	<?=Form::open(array('id'=>'reset', 'action'=>Uri::create('login/reset/'), 'method'=>'post'))?>    
        <?=CMS::create_nonce_field('update', 'nonce', 'update_nonce')?>
        <div class="loginPic">
            <a href="#" title=""><?=Asset::img('userLogin.png')?></a>
            <span><h3>Change Password</h3></span>
        </div>
        
        <?=Form::input(array('type'=>'password', 'name'=>'new_password', 'placeholder'=>'New Password', 'class'=> ''))?>
		<?=Form::input(array('type'=>'password', 'name'=>'new_password_confirm', 'placeholder'=>'Confirm Password', 'class'=> ''))?>
        <?=Form::input(array('type'=>'hidden', 'name'=>'hash', 'placeholder'=>'', 'class'=> '', 'value' => $hash))?>
        
        <div class="logControl">
            <input type="submit" name="submit" value="Submit" class="buttonM bGold" />
            <div class="clear"></div>
        </div>
	<?=Form::close()?>
</div>
<!-- New Pass wrapper ends -->
</body>
