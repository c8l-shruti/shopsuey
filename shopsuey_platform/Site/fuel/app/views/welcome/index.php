<body class="background with-logo">
<script>
	<?php if (!isset($user)) : ?>
	$(document).ready(function() {
		$('.logleft').click();
		$('.logback').hide();
	});
	<?php endif; ?>
</script>
<div class="wrapper<?=(isset($notice)) ? ' slideDown' : ' hide'?>">
<?=CMS::note(@$notice['message'], @$notice['type'])?>
</div>
<div class="content">
    <!-- Login wrapper begins -->
    <div class="login-wrapper">
        <?=Asset::img('logo-notext.png', array('class' => 'logo', 'alt' => 'Shopsuey Logo'))?>
    	<!-- Current user form -->
    	<?=Form::open(array('id'=>'login', 'action'=>Uri::create('login'), 'method'=>'post'))?>    
            <?=CMS::create_nonce_field('login', 'nonce', 'login_nonce')?>
            
            <?=Form::input(array('type'=>'email', 'name'=>'u', 'placeholder'=>'Email', 'class'=> 'loginEmail', 'value' => (isset($notice['form'])) ? $notice['form']['u'] : $user['email']))?>
            <?=Form::input(array('type'=>'password', 'name'=>'p', 'placeholder'=>'Password', 'class' => 'loginPassword'))?>
            
            <div class="logControl">
                <div class="memory">
                	<?=Form::checkbox(array('id' => 'remember1', 'name'=>'remember', 'value'=>1, 'class' => '', 'checked' => (isset($user['remember'])) ? 'checked' : ''))?>
                    <?=Form::label('&nbsp; Remember me', null, array('for' => 'remember1'))?>
                </div>
            </div>
            <div class="actionsWrapper">
                <div class="forgotPass"><a href="<?=Uri::create('login/forgot')?>">Forgot your password?</a></div>

                <div class="mt25">
                    <input class="big-text" type="submit" value="Login" name="login">
                </div>
                <div class="medium-text mt25">
                    - or -
                </div>
                <div class="mt25">
                    <a class="big-text create-account" href="<?=Uri::create('setup/profile/signup')?>">Create an Account</a>
                </div>
            </div>
    	<?=Form::close()?>    
    </div>
    <!-- Login wrapper ends -->
</div>

</body>
