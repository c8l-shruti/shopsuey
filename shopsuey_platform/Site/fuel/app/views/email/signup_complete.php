<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=Config::get('theme.title', 'ShopSuey | New CMS User')?></title>
<?=Asset::css('email.css', array(), null, true)?>

</head>

<body>
	<div id="wrapper">
        <div id="header">
            <a href="<?=Config::get('base_url')?>"><?=Asset::img('logo.png')?></a>
        </div>
        <div id="content">
            <h3>The user <?=$user->get_meta_field_value('real_name')?> just completed signup</h3>
            <p></p>
            <?php if (\Fuel::$env != 'production'): ?>
            <h5 style="color: red;">This mail was generated on a dev environment, not on production!</h5>
            <?php endif; ?>
            <p>Click here to edit the user&nbsp;&nbsp;
                <a href="<?=Uri::create("dashboard/user/{$user->id}/edit")?>" class="buttonL bGold">Edit</a>
            </p>
            <p></p>
            <h5>The user is configured to manage the following locations:</h5>
            <p></p>
            <ul>
                <?php foreach($user->location_managers as $location_manager): ?>
                <li>
                    <?php $location = $location_manager->location; ?>
                    <p><?=Helper_Api::get_location_friendly_name($location)?>
                    <?php if (in_array($location->id, $signup_location_ids)): ?>
                        &nbsp;&nbsp;<strong style="font-size: 1.5em; color: red;">*</strong>
                    <?php endif; ?>
                    &nbsp;&nbsp;<a href="<?=CMS::get_cms_location_url($location)?>" class="buttonL bGold">Edit</a>
                    </p>
                </li>
                <?php endforeach; ?>
            </ul>
            <p></p>
            <p><strong>Note:</strong> Entries marked with <strong style="font-size: 1.5em; color: red;">*</strong> were created during signup and need to be updated accordingly</p>
            <p></p>
        </div>
        <div id="footer">
        	<small>&copy; <?=date('Y')?> Shopping Made Mobile</small>
        </div>
    </div>
    <p></p>
</body>
</html>