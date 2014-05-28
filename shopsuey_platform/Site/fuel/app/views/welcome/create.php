<body class="background with-logo">
    <div class="content">
        <div class="login-wrapper">
            <h1 style="margin-bottom: 20px">
            <?php if (! @$existing_user): ?>
            Create an Account
            <?php else: ?>
            Update your Account
            <?php endif; ?>
            </h1>
            <p class="mb25">
                <?php if (! @$existing_user): ?>
                <a href="<?=Uri::create('login')?>">Already have an account? Sign In</a>
                <?php else: ?>
                <a href="<?=Uri::create('logout')?>">Not you? Logout</a>
                <?php endif; ?>
            </p>
            <?=CMS::field_error(@$notice, null)?>
            <?=Form::open(array('id'=>'create', 'action'=>Uri::create('login/create/'), 'method'=>'post'))?>
                <?=CMS::create_nonce_field('create', 'nonce', 'create_nonce')?>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'name_of_business')?>>
                    <?=Form::input(array('type'=>'text', 'name'=>'name_of_business', 'placeholder'=>'Name of Business', 'value' => @$form['name_of_business']))?>
                    <?=CMS::field_error(@$notice, 'name_of_business')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'real_name')?>>
                    <?=Form::input(array('type'=>'text', 'name'=>'real_name', 'placeholder'=>'Your Name', 'value' => @$form['real_name']))?>
                    <?=CMS::field_error(@$notice, 'real_name')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'role')?>>
                    <?=Form::input(array('type'=>'text', 'name'=>'role', 'placeholder'=>'Position w/Company', 'value' => @$form['role']))?>
                    <?=CMS::field_error(@$notice, 'role')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'email')?>>
                    <?=Form::input(array('type'=>'email', 'name'=>'email', 'placeholder'=>'Email', 'value' => @$form['email']))?>
                    <?=CMS::field_error(@$notice, 'email')?>
                </div>
                <?php if (! @$existing_user): ?>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'password')?>>
                    <?=Form::input(array('type'=>'password', 'name'=>'password', 'placeholder'=>'Password'))?>
                    <?=CMS::field_error(@$notice, 'password')?>
                </div>
                <div <?=CMS::field_error_wrapper_class(@$notice, 'confirmPassword')?>>
                    <?=Form::input(array('type'=>'password', 'name'=>'confirmPassword', 'placeholder'=>'Confirm Password'))?>
                    <?=CMS::field_error(@$notice, 'confirmPassword')?>
                </div>
                <?php endif; ?>
                <div class="terms-checkbox-wrapper">
                    <input type="checkbox" id="terms" name="terms" <?php if (isset($form['terms'])) echo 'checked="on"'; ?>>
                    <label for="terms">I agree to <a href="http://www.thesuey.com/assets/static/tos.html" target="_blank">ShopSuey's Terms of Service</a></label>
                    <?=CMS::field_error(@$notice, 'terms')?>
                </div>

                <div class="actionsWrapper">
                    <div class="mt25">
                        <input class="big-text" type="submit" value="Submit" name="submit">
                    </div>
                </div>
        	<?=Form::close()?>
        </div>
    </div>
</body>
