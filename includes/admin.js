jQuery(document).ready(function($) {
    $('#upload-logo-button').click(function(e) {
        e.preventDefault();
        var logo_id = $('#logo-id').val();
        var custom_uploader = wp.media({
            title: 'Sélectionner un logo',
            button: {
                text: 'Utiliser ce logo'
            },
            multiple: false
        });
        custom_uploader.on('select', function() {
            var attachment = custom_uploader.state().get('selection').first().toJSON();
            $('#logo-id').val(attachment.id);
            $('#logo-preview').html('<img src="' + attachment.url + '" alt="Logo">');
        });
        custom_uploader.open();
    });
});
 