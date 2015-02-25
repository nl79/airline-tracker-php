$ = function(id) {
    return document.getElementById(id); 
}

function select(evt) {
    
    //prevent the default click action. 
    evt.preventDefault();
    
    //get the target url
    var url = evt.target;
    
    //build an ajax call.
    var ajax = new XMLHttpRequest();
    
    ajax.onreadystatechange=function() {
        
    if (ajax.readyState==4 && ajax.status==200)
      {
      document.getElementById("div-content").innerHTML=ajax.responseText;
      }
    }

    ajax.open("GET",url + '&ajax=ajax' ,true);
    ajax.send();
    
}

function init() {
    $('ul-links').setAttribute("onclick", 'select(event)');    
}



window.onload = function() {
    init(); 
}