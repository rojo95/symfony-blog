const Toast = Swal.mixin({
})

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
                $('button#dislike').addClass('btn-outline-secondary').removeClass('btn-outline-danger');
                if($('button#like').hasClass('btn-outline-secondary')) {
                    $('button#like').addClass('btn-outline-success').removeClass('btn-outline-secondary');
                } else {
                    $('button#like').addClass('btn-outline-secondary').removeClass('btn-outline-success');
                }
            } else {
                $('button#like').addClass('btn-outline-secondary').removeClass('btn-outline-success');
                if($('button#dislike').hasClass('btn-outline-secondary')) {
                    $('button#dislike').addClass('btn-outline-danger').removeClass('btn-outline-secondary');
                } else {
                    $('button#dislike').addClass('btn-outline-secondary').removeClass('btn-outline-danger');
                }
            }
            console.log(data)
        }
    });
}



$(document).ready(function(){
    $('button.borrar').click(function(){
        var com = $(this).parent().children('input[name=id]').val();
        const commentaryContainer = $(this).closest('.card');
        Toast.fire({
            icon: 'warning',
            title: '¿Estás seguro de eliminar el comentario?',
            text: "¡Ésta acción no puede ser revertida!",
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'ELIMINAR COMENTARIO',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {

                const ruta = Routing.generate('comentary_delete');
                $.ajax({
                    type: 'DELETE',
                    url: ruta,
                    data: ({ id: com }),
                    async: true,
                    dataType: 'json',
                    success: function(res) {

                        if (res.success) {

                            Toast.fire({
                                showConfirmButton: false,
                                toast: true,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                },
                                position: 'top-end',
                                icon: 'success',
                                title: 'Comentario eliminado correctamente.'
                            });
                            
                            commentaryContainer.remove()
            
                            if($('.commentary').length <=0){
                                $('div.card.my-3').after('<div class="card"><div class="card-body">Aún no hay ningún comentario, ¡sé el primero!</div></div>');
                            }
                            
                        } else {
                            Toast.fire({
                                showConfirmButton: false,
                                toast: true,
                                timer: 3000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                },
                                position: 'top-end',
                                icon: 'error',
                                title: 'Error al eliminar el comentario.'
                            });
                        }


                    }
                })

            }
        })

    });
});