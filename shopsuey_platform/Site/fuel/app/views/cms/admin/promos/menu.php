<div class="sidePad" id="searchEngines">
    <a class="bGold sideB" href="<?=Uri::create('admin/promos/add')?>">
        <span class="icon-plus-2"></span>
        <span>New Promo</span>
    </a>
    
    <div class="fluid mt10">
        <ul>
            <?php foreach ($promos as $promo) { ?>
            <li class="mt5">
                <?php if ($promo->enabled) { ?>
                    <a title="currently enabled" class="tablectrl_small bGreen tipS" href="<?=Uri::create('admin/promos/' . $promo->id . '/disable')?>"><span class="iconb" data-icon="&#10004;"></span></a>
                <?php } else { ?>
                    <a title="currently disabled" class="tablectrl_small bRed tipS" href="<?=Uri::create('admin/promos/' . $promo->id . '/enable')?>"><span class="iconb" data-icon="&#10006;"></span></a>
                <?php } ?>
                
                &nbsp;
                <a href="<?=Uri::create('admin/promos/' . $promo->id)?>"><b><?=$promo->name?></b></a>
                
                <a style="float: right" href="<?=Uri::create("admin/promos/{$promo->id}/edit/")?>" class="tablectrl_small bGold tipS" title="Edit"><span class="iconb" data-icon="&#xe04d;"></span></a>
            </li>
            <?php } ?>
        </ul>
    </div>
        
</div>