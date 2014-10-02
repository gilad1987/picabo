/**
 * Created by Gilad on 01/10/2014.
 */
Dropzone.options.uploadfile = {
    success:function(file,response,xhr){

        if(response.success){
            $('#image-url').val(response.url);
            $('#file-upload-container').addClass('image_uploaded_success');
        }
    }
};

document.onkeydown = function (e) {
    console.log(e.which);
}

window.addEventListener("keyup",kPress,false);
function kPress(e)
{
    var c=e.keyCode||e.charCode;
    if (c==44) {
        console.log('gf');
        return false;
    };
}