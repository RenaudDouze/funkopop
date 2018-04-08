$(document).ready(function () {
    $('.tpl-mosaique .thumbnail').on('click', function() {
        $(this).closest('.columns').toggleClass('active');
    });
});