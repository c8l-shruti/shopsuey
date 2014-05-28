<body>
<div class="wrapper<?=(isset($notice)) ? ' slideDown' : ' hide'?>">
<?=CMS::note(@$notice['message'], @$notice['type'])?>
</div>
<!-- New Pass wrapper begins -->
<div class="loginWrapper">
	<!-- New password form -->
	<?=Form::open(array('id'=>'login', 'action'=>Uri::create('login/update/'), 'method'=>'post'))?>    
        <?=CMS::create_nonce_field('update', 'nonce', 'update_nonce')?>
        <div class="loginPic">
            <a href="#" title=""><?=Asset::img('userLogin.png')?></a>
            <span><h3>Change Password</h3></span>
        </div>
        
        <?=Form::input(array('type'=>'password', 'name'=>'old_password', 'placeholder'=>'Current Password', 'class'=> ''))?>
		<?=Form::input(array('type'=>'password', 'name'=>'password', 'placeholder'=>'New Password', 'class'=> ''))?>
        <?=Form::input(array('type'=>'password', 'name'=>'confirm', 'placeholder'=>'Confirm', 'class'=> ''))?>
        
        <div class="logControl">
            <input type="submit" name="submit" value="Submit" class="buttonM bGold" />
            <div class="clear"></div>
        </div>
	<?=Form::close()?>
</div>
<!-- New Pass wrapper ends -->
</body>
