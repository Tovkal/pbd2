/**
 * Created by tovkal on 4/12/14.
 */
$(document).ready(function() {
    'use strict';

    // UPLOAD CLASS DEFINITION
    // ======================

    var dropZone = document.getElementById('drop-zone');
    var uploadForm = document.getElementById('js-upload-form');
    var progress = document.getElementById('photoProgressBar');

    var startUpload = function(files) {

        if (files.length == 0) {
            return;
        }

        var data = new FormData();
        data.append('SelectedFile', files[0]);

        var request = new XMLHttpRequest();
        request.onreadystatechange = function(){
            if(request.readyState == 4){
                var resp;
                try {
                    resp = JSON.parse(request.response);
                } catch (e){
                    resp = {
                        status: 'error',
                        data: 'Unknown error occurred: [' + request.responseText + ']'
                    };
                }
                console.log(resp.status + ': ' + resp.data);

                if (resp.status == 'success') {
                    //Show success alert?
                    //TODO Hide upload form?
                }
            }
        };

        request.upload.addEventListener('progress', function(e){
            progress.style.width = Math.ceil(e.loaded/e.total) * 100 + '%';
        }, false);

        request.open('POST', 'scripts/upload.php');
        request.send(data);
    };

    uploadForm.addEventListener('submit', function (e) {
        var uploadFiles = document.getElementById('js-upload-files').files;
        e.preventDefault();

        startUpload(uploadFiles)
    });


    dropZone.ondrop = function(e) {
        e.preventDefault();
        this.className = 'upload-drop-zone';

        startUpload(e.dataTransfer.files)
    };

    dropZone.ondragover = function() {
        this.className = 'upload-drop-zone drop';
        return false;
    };

    dropZone.ondragleave = function() {
        this.className = 'upload-drop-zone';
        return false;
    }
});
