Import = {
    doImport: function(){
        
        $("#importStatusError").css("visibility", "hidden");
        $("#importStatus").css("visibility", "hidden");
        $("#dealsAlreadyImportedRow").css("visibility", "hidden");
        
        var provider = $("#provider").val();
        var queryFilter = $("#queryFilter").val();
        
        if (provider == "-1"){
            alert("Please select a provider");
            return;
        }
        
        if (queryFilter == "-1"){
            alert("Please enter a city");
            return;
        }
        
        var url = base_url+"api/offers/doImport/"+provider+"?login_hash="+login_hash+"&queryFilter="+queryFilter+"&ajax=1";
        
        LoaderSpinner.show("Please wait, this may take some minutes.");

        $.get(url, function(data) {

            console.log(data);
            
            if (data.meta.status == 1){
                
                LoaderSpinner.hide();
                
                $("#importStatus").css("visibility", "visible");
                
                $('#importStatusDealsSaved').html(data.data.dealsSaved);
                
                if (data.data.existingRecords > 0){
                    $("#dealsAlreadyImportedRow").css("visibility", "visible");
                    $('#importStatusDealsAlreadyImported').html(data.data.existingRecords);
                }
                
            }else{
                
                LoaderSpinner.hide();
                
                $("#importStatusError").css("visibility", "visible");
                
                $("#importStatusErrorText").html("ERROR: "+data.meta.error);
                
            }
           
        });
        
    }
    
}

LoaderSpinner = {
    spinner: null,
    show: function(loaderMessage){
        
        if (typeof loaderMessage == 'undefined') var loaderMessage = "Please wait..."           
        
        $("#loaderContainer").css("visibility", "visible");
        $("#loaderBg").css("visibility", "visible");
        
        $("#loaderMessage").html(loaderMessage);
                
        $("body").css("overflow", "hidden");

        LoaderSpinner.spinner = new Spinner({
                lines: 12, // The number of lines to draw
                length: 7, // The length of each line
                width: 5, // The line thickness
                radius: 10, // The radius of the inner circle
                color: '#000', // #rbg or #rrggbb
                speed: 1, // Rounds per second
                trail: 100, // Afterglow percentage
                shadow: true // Whether to render a shadow
        }).spin(document.getElementById("spinnerContainer"));

    },
    hide: function(){
        
        $("#loaderContainer").css("visibility", "hidden");
        $("#loaderBg").css("visibility", "hidden");
        
        $("body").css("overflow", "auto");
        
        LoaderSpinner.spinner.stop();
    }
}