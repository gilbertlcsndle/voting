// applies changes into collumns
$('div#candidate-collumn')
    .attr('class', 'col-sm-'+$("[name='collumn']").val());

var clearfix = "<div class='clearfix'></div>";

// handles the clearing float in collumns
$('div#candidate-collumn').each(function(index,obj){
    // if collumns are 3, clear after 3 collumns
    if ($("select[name='collumn']").val() == 4) {
        for (var x=0; x < $('div#candidate-collumn').length; x+=3) {
            if (x !== 0) {
                if (index == x) {
                    $(obj).before(clearfix);
                }
            }
        }
        x=undefined;
    }

    // if collumns are 2, clear after 2 collumns
    if ($("select[name='collumn']").val() == 6) {
        for (var x=0; x < $('div#candidate-collumn').length; x+=2) {
            if (x !== 0) {
                if (index == x) {
                    $(obj).before(clearfix);
                }
            }
        }
        x=undefined;
    }
});
$(function(){
    $("select[name='collumn']").change(function(){
        // applies changes into collumns
        $('div#candidate-collumn').attr('class', 'col-sm-'+$(this).val());
        
        // reset
        $('.clearfix').remove();
        // handles the clearing float in collumns
        $('div#candidate-collumn').each(function(index,obj){
            // if collumns are 3, append clearfix before every 3 collumns
            if ($("select[name='collumn']").val() == 4) {
                for (var x=0; x < $('div#candidate-collumn').length; x+=3) {
                    if (x !== 0) {
                        if (index == x) {
                            $(obj).before(clearfix);
                        }
                    }
                }
                x=undefined;
            }

            // if collumns are 2, append clearfix before every 2 collumns
            if ($("select[name='collumn']").val() == 6) {
                for (var x=0; x < $('div#candidate-collumn').length; x+=2) {
                    if (x !== 0) {
                        if (index == x) {
                            $(obj).before(clearfix);
                        console.log(obj);
                        }
                    }
                }
                x=undefined;
            }
        });
    });
});