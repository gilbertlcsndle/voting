$(function(){
	$("button").click(function(e){

        var confirm_msg;

        if ($(this).attr('name') == 'remove') {
            confirm_msg = 
                'Are you sure you want to permanently delete this record?';
        } else if ($(this).attr('name') == 'cancel') {
            confirm_msg = 'Do you want to cancel editing?';
        }

        if ($(this).attr('type') == 'reset') {
            confirm_msg = 'Are you sure you want to reset the form?';
        }

        if (confirm_msg) {
            var is_confirmed = confirm(confirm_msg);
            
            if (!is_confirmed) {
                e.preventDefault();   
            }
        }
	});  

    $("a#logout").click(function(e){
        var is_confirmed = confirm('Do you want to logout?');

        if (!is_confirmed) {
            e.preventDefault();
        }
    });
});