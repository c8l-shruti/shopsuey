<script>
    var base_url = '<?=Config::get('base_url')?>';
    var login_hash = '<?=$login_hash?>';

    $(function() {
        $(".fancybox").fancybox({
            
        });
    });
</script>

<div class="fluid">
    <div class="grid8"><!-- Column Left -->
        <div class="widget"><!-- Offer Info -->
            <div class="whead">
                <h6><span class="icon-info-3"></span>Import Info</h6>
                <div class="clear"></div>
            </div>

            <fieldset class="formpart">

                <div class="formRow">

                    <div class="grid2"><label for="name">Provider:</label></div>

                    <div class="grid10">

                        <select id="provider" name="provider">
                            <option value="-1"> - Select a deals provider - </option>
                            <?php foreach ($providers as $providerName) : ?>
                                <option value="<?= $providerName ?>"><?= $providerName ?></option>
                            <?php endforeach; ?>
                        </select>

                    </div>

                    <div class="clear"></div>

                </div>

                <div class="formRow">

                    <div class="grid2"><label for="name">City:</label></div>

                    <div class="grid10">

                        <select id="queryFilter" name="queryFilter">
                            <option value="-1"> - Select a city - </option>
                            <?php foreach ($cities as $cityArray) : ?>
                            
                                <?php /* error_log(var_export($cityArray["city"], true)); */ ?>
                            
                                <option value="<?=$cityArray["city"]?>"><?=$cityArray["city"]?></option>
                            <?php endforeach; ?>
                        </select>
                        
                    </div>

                    <div class="clear"></div>

                </div>
                
            </fieldset>
        </div>

        <div class="widget" id="importStatus">
            <div class="whead">
                <h6><span class="icon-document"></span>Import status</h6>
                <div class="clear"></div>
            </div>
            <div style="padding: 10px;" id="importStatusText">
                <div>
                    <div class="dealsImportedLabel">Deals imported:</div>
                    <div id="importStatusDealsSaved"></div>
                </div>
                <div id="dealsAlreadyImportedRow">
                    <div class="dealsImportedLabel">Deals already imported:</div>
                    <div id="importStatusDealsAlreadyImported"></div>
                </div>
            </div>
        </div>
        
        
        <div class="widget" id="importStatusError">
            <div class="whead">
                <h6><span class="icon-document"></span>Import errors</h6>
                <div class="clear"></div>
            </div>
            <div style="padding: 10px;" id="importStatusText">
                <div>
                    <div id="importStatusErrorText"></div>
                </div>
            </div>
        </div>

    </div>
    
    <div class="grid4">
        <div class="widget">
            <div class="whead">
                    <h6><span class="icon-search"></span>Get deals</h6>
                    <div class="clear"></div>
            </div>
            <div class="formRow">
                <button class="buttonL bGreyish fluid" id="importBttn" onclick="Import.doImport()"><i class="icon-search" data-icon="&#xe099;"></i> &nbsp; Import deals</button>
            </div>
        </div>
    </div>
    
</div>