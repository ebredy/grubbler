/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
errorAlert = function(msg){
    
    if(typeof bootbox != 'undefined'){
        
        bootbox.dialog({
          message: "<p>" + msg + "</p>",
          title: +"<div class='alert alert-danger' role='alert'> <span class='glyphicon glyphicon-exclamation-sign' aria-hidden='true'></span><span class='sr-only'>Error:</span>Oops There Is An Error!</div>",
            buttons: {

            main: {
              label: "OK",
              className: "btn-primary",
              callback: function() {

              }
            }
          }
        });
    }
    else{
            alert(msg);
    }
}

