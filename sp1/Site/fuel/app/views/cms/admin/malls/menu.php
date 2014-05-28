<div class="sidePad" id="searchEngines">
    <div class="fluid">
        <a class="sideB bGold" href="<?=Uri::create('admin/mall/add')?>">
            <span class="icon-plus-2"></span>
            <span>New Marketplace</span>
        </a>
        <?php if (isset($_GET['backs']) && isset($_GET['backp']) && preg_match('/^[0-9]+$/', $_GET['backp'])) { ?>
            <?php $sort = $_GET['backsort'] ? '?sort=' . urlencode($_GET['backsort']) : ''; ?>
            <a class="sideB bGreen mt5" href="<?=htmlspecialchars(Uri::create('admin/malls/' . $_GET['backp'] . '/' . rawurlencode($_GET['backs']))) . $sort?>">
                <span class="icon-back-2"></span>
                <span>Back to Search</span>
            </a>
        <?php } ?>
    </div>
        
    <?php if (isset($search_engines)): ?>
        <?=$search_engines?>
    <?php endif; ?>  
</div>