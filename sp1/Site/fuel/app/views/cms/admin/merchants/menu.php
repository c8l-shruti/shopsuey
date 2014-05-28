<div class="sidePad" id="searchEngines">
    <div class="fluid">
        <a class="sideB bGold" href="<?=Uri::create('admin/merchant/add')?>">
            <span class="icon-plus-2"></span>
            <span>New Merchant</span>
        </a>
        <?php if (isset($_GET['backs']) && isset($_GET['backp']) && preg_match('/^[0-9]+$/', $_GET['backp'])) { ?>
            <?php $sort = $_GET['backsort'] ? '?sort=' . urlencode($_GET['backsort']) : ''; ?>
            <a class="sideB bGold mt5" href="<?=Uri::create('admin/merchant/'.$merchantId.'/delete')?>" onclick="return confirm('Are you sure?')">
                <i class="iconb" data-icon="&#xe094;"></i> &nbsp;
                <span>Delete Merchant</span>
            </a>
            <a class="sideB bGreen mt5" href="<?=htmlspecialchars(Uri::create('admin/merchants/' . $_GET['backp'] . '/' . rawurlencode($_GET['backs']))) . $sort?>">
                <span class="icon-back-2"></span>
                <span>Back to Search</span>
            </a>
        <?php } ?>
    </div>
    
    <?php if (isset($search_engines)): ?>
        <?=$search_engines?>
    <?php endif; ?>  
</div>