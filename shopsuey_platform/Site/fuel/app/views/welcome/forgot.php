<body>
<div class="wrapper<?=(isset($notice)) ? ' slideDown' : ' hide'?>">
<?=CMS::note(@$notice['message'], @$notice['type'])?>
</div>
<!-- Forgot wrapper begins -->
<div class="loginWrapper">
	<!-- Forgot password form -->
	<?=Form::open(array('id'=>'login', 'action'=>Uri::create('login/forgot/'), 'method'=>'post'))?>    
        <?=CMS::create_nonce_field('forgot', 'nonce', 'forgot_nonce')?>
        <div class="loginPic">
            <a href="#" title=""><?=Asset::img('userLogin.png')?></a>
            <span><h3>Forgot Password?</h3></span>
            <div class="loginActions">
                <div><a href="<?=Uri::create('login/')?>" title="Back to login" class="logleft"></a></div>
            </div>
        </div>
        
        <?=Form::input(array('type'=>'text', 'name'=>'u', 'placeholder'=>'Email address', 'class'=> 'loginEmail'))?>
        
        <div class="logControl">
            <div class="memory">
            </div>
            <input type="submit" name="submit" value="Reset" class="buttonM bGold" />
            <div class="clear"></div>
        </div>
	<?=Form::close()?>
</div>
<!-- Forgot wrapper ends -->
</body>
