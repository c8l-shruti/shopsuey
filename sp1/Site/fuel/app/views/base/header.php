<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<link rel="shortcut icon" href="/assets/images/favicon.ico" type="image/x-icon">
<link rel="icon" href="/assets/images/favicon.ico" type="image/x-icon">
<title><?=Config::get('theme.title', 'ShopSuey | CMS')?></title>

<script>
	var redir = '<?=(!Session::get('timezone')) ? Uri::create('dashboard/timezone') : ''?>';
    var timespinner = "<?=Uri::base(false)?>assets/images/elements/ui/spinner.png";
</script>

<?=Asset::css($style)?>
<?php if (isset($styles)) { echo Asset::css($styles); }?>
<!--[if IE]><?=Asset::css($ie)?><![endif]-->

<?php if (isset($scripts)) { echo Asset::js($scripts); }?>

</head>