$(function () { //$(document).ready(function() { ... });
    //Initialize Select2 Elements
    $('.select2').select2()

    //Initialize Select2 Elements
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    })
    $('[data-toggle="tooltip"]').tooltip();
    $('.imageupload').imageupload({
        allowedFormats: [ 'jpg', 'jpeg', 'png', 'gif' ],
        maxWidth : 220,
        maxHeight : 250,
        maxFileSizeKb: 2048,
        imgSrc: '',
    });
});