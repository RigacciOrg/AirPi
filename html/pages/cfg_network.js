// Disable manual config when using DHCP.
$(document).ready(function() {

    if ($('#net_use_dhcp').is(':checked')) {
        $('#manualconfig').attr('disabled', true);
    } else {
        $('#manualconfig').removeAttr('disabled');
    }

    $('#net_use_dhcp').bind('click', function() {
        if (this.checked) {
            $('#manualconfig').attr('disabled', true);
        } else {
            $('#manualconfig').removeAttr('disabled');
        }
    });

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
