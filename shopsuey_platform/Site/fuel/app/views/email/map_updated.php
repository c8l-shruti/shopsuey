<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=Config::get('theme.title', 'ShopSuey | Micello map updated')?></title>
<?=Asset::css('email.css', array(), null, true)?>

</head>

<body>
	<div id="wrapper">
        <div id="header">
            <a href="<?=Config::get('base_url')?>"><?=Asset::img('logo.png')?></a>
        </div>
        <div id="content">
            <h3>Micello Map changed!</h3>
            
            <?php if (\Fuel::$env != 'production'): ?>
            <h5 style="color: red;">This mail was generated on a dev environment, not on production!</h5>
            <?php endif; ?>
        
            <p><?=Helper_Api::get_location_friendly_name($mall)?> has been updated and needs to be merged or overwritten with the existing marketplace data. The biggest impact will be retail locations and names.</p>
            <p></p>
            <p>Go to <a href="<?=Uri::create("admin/mall/{$mall->id}/edit")?>" class="buttonL bGold">Edit</a> and perform a marketplace update to stay in sync.</p>
            <p></p>
            <p>Thanks,</p>
            <p></p>
            <p>Lehua</p>
        </div>
        <div id="footer">
        	<small>&copy; <?=date('Y')?> Shopping Made Mobile</small>
        </div>
    </div>
    <p></p>
</body>
</html>
