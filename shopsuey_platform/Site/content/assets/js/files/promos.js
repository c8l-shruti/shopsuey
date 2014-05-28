Promo = {
    deliver: function(promoId, offerId, winnerId){
        
        var url = base_url+"admin/promos/doSetWinner/"+promoId+"/"+offerId+"/"+winnerId+"?login_hash="+login_hash+"&ajax=1";

        $.get(url, function(data) {

            data = $.parseJSON(data);

            if (data != false){

                $('#findAnotherWinnerButton').hide();
                $('#winnerProfileButton').show();

                $('#winnerNotificationMsg').html("Reward delivered!");
                $('#winnerNotificationMsg').show();
                
                $('.deliverButton').hide();
                
            }else{
                $('#findAWinnerArea').html("<div id='winnerError'>There was an error setting a winner. Please try again later.</div>");
            }

        });
        
    }
}