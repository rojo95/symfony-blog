const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

$(document).ready(function(){
    $('.atras').click(()=> {
        window.history.back()
    });
    
    $('#post_update_file').change(function(e){

        var fileExtension = ['jpeg', 'jpg', 'png'];
        
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) == -1) {
            $(this).val('');
            Toast.fire({
                icon: 'warning',
                title: 'Solo se aceptan los siguientes formatos: '+fileExtension.join(', ')
              });
        } else {
            var input = this;
            var reader = new FileReader();
            reader.onload = function (e) {
                $('.image-post').remove();
                $('.card-body').before('<img src="'+e.target.result+'" class="image-post card-img-top d-block d-lg-none">');
                $('#image-div').html('<img src="'+e.target.result+'" class="image-post img-fluid rounded-start">');
            }
            reader.readAsDataURL(input.files[0]);
        }
   
    });
});