// File withoutExtension
function getPHPFile(file, divDestination) {
    $.ajax({
        type:'GET',
        url: file + '.php',
        data:'',
        success: function(data){
            divDestination.html(data);
        }
    });
}