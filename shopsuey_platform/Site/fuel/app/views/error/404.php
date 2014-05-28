<body>
<!-- Top line begins -->
<div id="top">
	<div class="wrapper">
    	<button class="buttonM bRed logo" onClick="history.go(-1);return false;"><span class="icon-undo"></span> &nbsp; Back</button>
    </div>
</div>

<!-- Main content wrapper begins -->
<div class="errorWrapper">
	<?=Asset::img('logo_big.png')?>
    <span class="errorNum">404</span>
	<div class="errorContent">
        <span class="errorDesc">
            <span class="icon-warning"></span>
            Oops! Sorry, an error has occured. Page not found!
            <br/>
            Requested page: <?=$_SERVER["REQUEST_URI"]?>
            
        </span>
    </div>
</div>    
<!-- Main content wrapper ends -->
</body>
</html>

