// Hide/show passwords.
$(document).ready(function() {

    if ($('#showpasswd').is(':checked')) {
        $('.toggle_show').attr('type', 'text');
    } else {
        $('.toggle_show').attr('type', 'password');
    }

    $('#showpasswd').bind('click', function() {
        if (this.checked) {
            $('.toggle_show').attr('type', 'text');
        } else {
            $('.toggle_show').attr('type', 'password');
        }
    });

});
