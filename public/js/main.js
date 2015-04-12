

$( document ).ready(function() {
    
});

function busy() {
    $('selector').css( 'cursor', 'wait' );
    console.log('busy');
}

function free() {
    console.log('free');
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

function remove() {

    var id = $('input#input-entity-id').val();

    if(id && !isNaN(id)) {

        //get the url property.
        var url = $(this).attr('url');

        //ajax settings object.
        var settings = {
            type:"POST",
            url: url,
            data: 'entity_id=' + id,
            dataType: 'json'
        };

        var callback = function(data){

            if(data.statusCode == 200){
                //remove the tr from the list.
                $("tr#entity_id-" + data.entity_id).remove();

            } else {
                //display error.
                alert(data.message);
            }
        };

        //post the data to the server.
        $.ajax(settings).done(callback);

    } else {
        alert("No Valid Record Selected. \nPlease Select an Existing" +
        " record from the list.");
    }
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

                /*
                check the data-type attribute to see if the value should be numeric.
                 */
                var type = $(this).attr('data-type');

                if(type && type.toLowerCase() == 'number' && isNaN($(this).val())){
                    valid = false;
                    $(this).addClass('error');
                } else {
                    $(this).removeClass('error');
                }

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

            /*
            extract the form action and parse it into key=value pairs.
             */
            var action = $('#' + formId).attr('action');

            /*rigged code because the server does not offer mod_rewrite*/
            var parts = action.split('/');
            //add the parts to the query string.
            query += "&pg=" + parts[0];
            query += "&ac=" + parts[1];
            /***********************************************************/


            //ajax settings object.
            var settings = {
                type:"POST",
                url: '',
                //url: $('#' + formId).attr('action'),
                data: query,
                dataType: 'json'
            };

            var callback = function(data){

                //var data = $.parseJson(data);

                console.log(data);

                //check the status code. if 200 call the add table function.
                if(data.statusCode && data.statusCode == 200) {

                    /*
                    check the OP property. if new, insert a TR, else replace with
                    an updated version.
                     */
                    if(data.op == 'update') {

                        /*
                        select the tr with the ID field equaled to the updated record id.
                         */

                        $("tr#entity_id-" + data.entity_id).replaceWith(buildTr(data.data));


                    } else if(data.op == 'insert') {
                        $('table.table-record-list tr:first').after(buildTr(data.data));

                        //clear the form data.
                        $('form#' + formId).get(0).reset();
                    }

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

function reset() {
    var formId = $(this).attr('target');

    //clear the form data.
    $('form#' + formId).get(0).reset();
    $('input#input-entity-id').val('');
}

