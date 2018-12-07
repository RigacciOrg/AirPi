$('#poweroff_btn').click(function() {
    $('#dlg_header').text(poweroff_header);
    $('#dlg_body').text(poweroff_body);
    $('#dlg_action').val('system_poweroff');
})

$('#reboot_btn').click(function() {
    $('#dlg_header').text(reboot_header);
    $('#dlg_body').text(reboot_body);
    $('#dlg_action').val('system_reboot');
})

$('#apply_cfg_btn').click(function() {
    $('#dlg_header').text(apply_cfg_header);
    $('#dlg_body').text(apply_cfg_body);
    $('#dlg_action').val('system_apply_cfg');
})

$('#dlg_submit').click(function() {
    $('#action').attr('name', $('#dlg_action').val());
    $('#action_form').submit();
});
