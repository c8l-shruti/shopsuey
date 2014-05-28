<?php
function friendly_number($number) {
    if ($number > 1000000) {
        return round($number / 1000000, 0) . "m";
    } elseif ($number > 1000) {
        return round($number / 1000, 0) . "k";
    }
    return $number;
}
?>
<div class="fluid" id="healthMetrics">

    <div class="m15">
        <div class="healthWidget pink">
            <div class="healthWidgetNumber"><?=friendly_number(isset($current->favorites_count) ? $current->favorites_count : 0)?></div>
            <div class="healthWidgetDescription">Favorites</div>
        </div>
        
        <div class="healthWidget salmon">
            <div class="healthWidgetNumber">
                <?php if ($super_admin) { ?>
                <a style="color:black" href="<?=Uri::create('dashboard/offer')?>/?gallery=1&include_merchants=1&location_id=<?= $location_id ?>">
                <?php } ?>

                <?=friendly_number(isset($current->offers_count) ? $current->offers_count : 0)?>

                <?php if ($super_admin) { ?>
                </a>
                <?php } ?>
            </div>
            <div class="healthWidgetDescription">Offers</div>
        </div>
        
        <div class="healthWidget orange">
            <div class="healthWidgetNumber">
                <?php if ($super_admin) { ?>
                <a style="color:black" href="<?=Uri::create('dashboard/events')?>/?gallery=1&include_merchants=1&location_id=<?= $location_id ?>">
                <?php } ?>
                
                <?=friendly_number(isset($current->events_count) ? $current->events_count : 0)?>

                <?php if ($super_admin) { ?>
                </a>
                <?php } ?>
            </div>
            <div class="healthWidgetDescription">Events</div>
        </div>
        
        <div class="healthWidget yellow">
            <div class="healthWidgetNumber">
                <a href="<?=Uri::create('dashboard/subscribers?company_id=' . $location_id)?>">
                    <?=friendly_number(isset($current->sign_ups_count) ? $current->sign_ups_count : 0)?>
                </a>
            </div>
            <div class="healthWidgetDescription">Sign-Ups</div>
        </div>
        
        <div class="healthWidget green">
            <div class="healthWidgetNumber" data-alternative="<?=friendly_number(isset($current->check_ins_count) ? $current->check_ins_count : 0)?>"><?=friendly_number(isset($current->check_ins_via_ss_count) ? $current->check_ins_via_ss_count : 0)?></div>
            <div class="healthWidgetDescription" data-alternative="On FSQ">Check-Ins</div>
        </div>
        
        <div class="healthWidget blue">
            <div class="healthWidgetNumber" data-alternative="<?=friendly_number(isset($current->likes_count) ? $current->likes_count : 0)?>"><?=friendly_number(isset($current->likes_via_ss_count) ? $current->likes_via_ss_count : 0)?></div>
            <div class="healthWidgetDescription" data-alternative="On FB">Likes</div>
        </div>
        
        <div class="healthWidget purple">
            <div class="healthWidgetNumber"><?=friendly_number(isset($current->follows_count) ? $current->follows_count : 0)?></div>
            <div class="healthWidgetDescription">Followers</div>
        </div>
        <div class="clear"></div>
    </div>
    
    <div class="mt20">
        <div id="chartDivContainer" style="width: 900px;">
            <div id="chartDiv"  style="width: 900px; height: 500px;"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
      var dataTable = new google.visualization.DataTable()
      dataTable.addColumn('date', 'Date');
      dataTable.addColumn('number', 'Favorites');
      dataTable.addColumn('number', 'Offers');
      dataTable.addColumn('number', 'Events');
      dataTable.addColumn('number', 'Sign-Ups');
      dataTable.addColumn('number', 'Check-Ins');
      dataTable.addColumn('number', 'Likes');
      dataTable.addRows([
        <?php
        foreach ($historic as $h) {
            $time = $h->created_at;
            $year = date('Y', $time);
            $month = date('m', $time) - 1;
            $day = date('d', $time);
            echo "[new Date($year, $month, $day), $h->favorites_count, $h->offers_count, $h->events_count, $h->sign_ups_count, " . (int)$h->check_ins_via_ss_count . ", " . (int)$h->likes_via_ss_count . "],";
        }
        ?>
      ]);
      
      var dataView = new google.visualization.DataView(dataTable);

      var options = {
          backgroundColor: '#eeeeee',
          chartArea: {left:100,top:20,width:"90%",height:"90%"},
          colors: ['rgb(251,70,174)', 'rgb(206,47,62)', 'rgb(232,147,49)', 'rgb(235,225,50)', 'rgb(122,228,141)', 'rgb(39,131,251)'],
          lineWidth: 4,
          vAxis : {gridlines: {color: '#888', count: 6}},
          hAxis : {minorGridlines: {color: '#aaaaaa', count: 3}, textStyle: {fontSize:18}},
          legend: {position:'none'}
      };

      var chart = new google.visualization.LineChart(document.getElementById('chartDiv'));
      chart.draw(dataView, options);
    }

    $('.healthWidget').each(function(){
        var numberNode = $(this).find('.healthWidgetNumber')
        var descriptionNode = $(this).find('.healthWidgetDescription')

        if (numberNode.attr('data-alternative') && descriptionNode.attr('data-alternative')) {
            var originalNumber = numberNode.text()
            var originalDescription = descriptionNode.text()
            $(this).mouseover(function() {
                numberNode.text(numberNode.attr('data-alternative'))
                descriptionNode.text(descriptionNode.attr('data-alternative'))
            })
            $(this).mouseout(function() {
                numberNode.text(originalNumber)
                descriptionNode.text(originalDescription)
            })
        }
    })
</script>