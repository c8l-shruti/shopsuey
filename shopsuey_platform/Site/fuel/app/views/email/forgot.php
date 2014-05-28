<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=Config::get('theme.title', 'ShopSuey | Reset')?></title>
<?=Asset::css('email.css', array(), null, true)?>

</head>

<body>
	<div id="wrapper">
        <div id="header">
            <a href="<?=Config::get('base_url')?>"><?=Asset::img('logo.png')?></a>
        </div>
        <div id="content">
            <h3>Hey <?=$username?>,</h3>
            <p></p>
            <p>Looks like you requested a password reset. Click the link below to complete your request</p>
            <p></p>
            <p></p>
            <a href="<?=$link?>" class="buttonL bGold">Reset Password</a>
            <p></p>
            <p></p>
            <small>ShopSuey Admin</small>
        </div>
        <div id="footer">
        	<small>&copy; <?=date('Y')?> Shopping Made Mobile</small>
        </div>
    </div>
    <p></p>
</body>
</html>