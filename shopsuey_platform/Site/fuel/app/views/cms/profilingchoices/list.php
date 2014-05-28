<div class="widget fluid">
    <?php if (count($profiling_choices) > 0): ?>
    <div id="dataTableBody" class="profilingChoices">
        <?php foreach ($profiling_choices as $profiling_choice): ?>
        <div class="dataTableDataRow gallery-box">
            <input type="hidden" value="<?php echo $profiling_choice->id; ?>" class="profilingChoiceId"/>
            
            <a href="<?=Uri::create("dashboard/profilingchoices/{$profiling_choice->id}/edit")?>">
                <div class="gallery-img-wrapper">
                    <img src="<?= $profiling_choice->url ?>" alt=""/>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
    $(window).load(function() {
        $("#dataTableBody").sortable({
            placeholder: "ui-state-highlight",
            tolerance: "pointer",
            update : function(event, ui) {
                var newOrders = [];
                $("#dataTableBody .dataTableDataRow").each(function(idx, element) {
                    var id = $(element).find('.profilingChoiceId:first').val();
                    
                    newOrders.push({id : id, order : idx});
                });
                
                $.post('<?php echo Uri::create('dashboard/profilingchoices/update_order') ?>?login_hash=<?php echo $login_hash; ?>', {
                    order : newOrders
                }, function(r) {
                }, 'json');
            }
        });
        $("#dataTableBody").disableSelection();
    });
</script>