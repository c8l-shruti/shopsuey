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
            <h3>Aloha <?=$username?>,</h3>
            <p></p>
            <p>Thanks for downloading ShopSuey. We're here to help you search local and buy local wherever you are.</p>
            <p></p>
            <p>We love local shops and are here to support local communities so you'll start seeing our neighborhood maps to help you discover cool shops all around you, in addition to marketplaces (some people call them malls).</p>
            <p>We're growing fast - look out for us in Hawaii, California, and some major cities throughout the US like New York, Chicago, and even globally in cool shopping places like Japan and Singapore. </p>
            <p>I hope you enjoy using ShopSuey and if you don't see it in your neck of the woods yet, give me a shout-I'll make it happen. </p>
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