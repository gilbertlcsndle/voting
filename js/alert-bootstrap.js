$("[id*='alert-']").each(function() {
    var level;
    var sign;
    var msg='';

    if ($(this).is('#alert-danger')) {
        level = 'danger';
        sign  = 'exclamation-sign';
        msg   = '<b>Oh snap!</b> ';
    }
    if ($(this).is('#alert-info')) {
        level = 'info';
        sign  = 'info-sign';
    }
    if ($(this).is('#alert-warning')) {
        level = 'warning';
        sign  = 'warning-sign';
    }
    if ($(this).is('#alert-success')) {
        level = 'success';
        sign  = 'ok';
        msg   = '<b>Well done!</b> ';
    }
    
    $(this)
        .addClass('alert alert-'+level+' alert-dismissible')
        .html(
            "<span class='glyphicon glyphicon-"+sign+"'></span>\
            <button type='button' class='close' data-dismiss='alert'>\
            &times</button>"+msg+$(this).html()
        );

});