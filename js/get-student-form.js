$(function(){
    if ($("select[name='account[user_level]']").val() == 'Student') {
        $.ajax({
            url: 'forms/student-form.php',
            type: 'POST',
            data: {student: $('#student-form').data('student')},
            success: function(data) {
                $('#student-form').html(data);
            }
        });
    } else {
        $('#student-form').empty();
    }
    $("select[name='account[user_level]']").change(function(){
        if ($(this).val() == 'Student') {
            $.ajax({
                url: 'forms/student-form.php',
                type: 'POST',
                data: {student: $('#student-form').data('student')},
                success: function(data) {
                    $('#student-form').html(data);
                }
            });
        } else {
            $('#student-form').empty();
        }
    });
});