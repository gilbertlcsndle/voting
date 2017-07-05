$(function(){
    $('button').click(function(){
        if ($(this).parent().has('#show-pass')) {
            if ($(this).parents('.input-group').find('input').attr('type') == 'password') {
                $(this).parents('.input-group').find('input').attr('type', 'text');

                $(this).parents('.input-group').find('input')
                    .parents('.input-group')
                    .find('.glyphicon-eye-open')
                    .attr('class', 'glyphicon glyphicon-eye-close');
            } else if ($(this).parents('.input-group').find('input').attr('type') == 'text') {
                $(this).parents('.input-group').find('input').attr('type', 'password');

                $(this).parents('.input-group').find('input')
                    .parents('.input-group')
                    .find('.glyphicon-eye-close')
                    .attr('class', 'glyphicon glyphicon-eye-open');
            }
        }
    });
});