/**
 * 
 * @param {*} info 
 */
function Megusta(info) {
    const ruta = Routing.generate('like');
    $.ajax({
        type: 'POST',
        url: ruta,
        data: ({id:info[0], type:info[1]}),
        async: true,
        dataType: 'json',
        success: function (data) {
            $('div#likes').html(data.likes);
            $('div#dislikes').html(data.dislikes);
            if (data.type) {
                $('button#dislike').addClass('btn-outline-secondary').removeClass('btn-outline-success');
                if($('button#like').hasClass('btn-outline-secondary')) {
                    $('button#like').addClass('btn-outline-success').removeClass('btn-outline-secondary');
                } else {
                    $('button#like').addClass('btn-outline-secondary').removeClass('btn-outline-success');
                }
            } else {
                $('button#like').addClass('btn-outline-secondary').removeClass('btn-outline-danger');
                $('div#dislikes').html(data.dislikes);
                $('button#dislike').addClass('btn-outline-danger').removeClass('btn-outline-secondary');
            }
            console.log(data)
        }
    });
}