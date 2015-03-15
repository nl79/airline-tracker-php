

$( document ).ready(function() {
    
});

function busy() {
    $('selector').css( 'cursor', 'wait' );
}

function free() {
    $('selector').css( 'cursor', 'auto' );
}

function buildTr(obj) {
    var tr;

    tr = $('<tr/>').attr('id', ('entity_id-' +  obj['entity_id']));


    for(key in obj) {

        if(obj.hasOwnProperty(key)) {
            tr.append("<td>" + obj[key] + "</td>")
        }
    }
    return tr;
}

function save() {
    //select the elements to validate.
    var fields = $("[jq-validate]");

    if(fields) {

        //set the valid flag.
        var valid = true;

        fields.each(function(index) {

            //if value is empty set an error class and flag to false.

            if(!$(this).val()){

                $(this).addClass('error');

                valid = false;

            } else {
                $(this).removeClass('error');
            }

        });

        if(valid) {
            /*
             searlize the form data
             */
            //get the parent attribute value of the button.

            var formId = $(this).attr('parent');

            //get the query string
            var query = $('form#' + formId).serialize();

            //ajax settings object.
            var settings = {
                type:"POST",
                url: $('#' + formId).attr('action'),
                data: query,
                dataType: 'json'
            };

            var callback = function(data){

                //var data = $.parseJson(data);

                //check the status code. if 200 call the add table function.
                if(data.statusCode && data.statusCode == 200) {


                    $('table.table-record-list tr:first').after(buildTr(data.data));

                    //clear the form data.
                    $('form#' + formId).get(0).reset();

                }else {
                    //else display an error message.
                    alert('Operation Failed - Try Again');
                }
            };

            //console.log(settings);
            //post the data to the server.
            $.ajax(settings).done(callback);
        }
    }

}

