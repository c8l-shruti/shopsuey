<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=Config::get('theme.title', 'ShopSuey | New Password')?></title>
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
            <p>Here's your new password:</p>
            <p></p>
            <h5 style="font-family: Georgia, 'Times New Roman', Times, serif"><?=$password?></h5>
            <p></p>
            <p></p>
            <p></p>
            <a href="<?=Uri::create('login/')?>" class="buttonL bGold">Log In</a>
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