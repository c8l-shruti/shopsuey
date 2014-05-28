<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=Config::get('theme.title', 'ShopSuey | You won!')?></title>
<?=Asset::css('email.css', array(), null, true)?>

</head>

<body>
	<div id="wrapper">
        <div id="header">
            <a href="<?=Config::get('base_url')?>"><?=Asset::img('logo.png')?></a>
        </div>
        <div id="content">
            <h3>Aloha <?=$user->get_meta_field_value('real_name')?>,</h3>
            <p></p>
            <p>OMG! You're a ShopSuey winner!</p>
            <p></p>
            <p></p>
            <p>Mahalo!</p>
            <p class="signature">Jason Ho'opai</p>
            <p class="signature">CEO / Co-Founder</p>
        </div>
        <div id="footer">
        	<small>&copy; <?=date('Y')?> Shopping Made Mobile</small>
        </div>
    </div>
    <p></p>
</body>
</html>