$(document).ready(function(){
    //alert('multipurpose.js has been loaded');
    $('#cats').change(function(){
        $.ajax({
            url: mp_ajax,
            data:{
                id_category: $(this).val()
            },
            method: 'POST',
            success: function(data) {
                $('.ajax_products').html(data);
            }
        });
    });
})
