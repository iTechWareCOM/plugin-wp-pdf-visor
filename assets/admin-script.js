document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('itechware-pdf-visor-button')) {
        let button = document.getElementById('itechware-pdf-visor-button');
        button.addEventListener('click', function (e) {
            e.preventDefault();
            let frame = wp.media({
                title: 'Selecciona el archivo PDF',
                button: {
                    text: 'Usar este archivo PDF'
                },
                multiple: false,
                library: {
                    type: 'application/pdf'
                }
            });
            frame.on('select', function () {
                let attachments = frame.state().get('selection').map(function (attachment) {
                    attachment = attachment.toJSON();
                    return attachment.url;
                });
                let shortcode = attachments.map(function(url) {
                    return '[itw_pdf_visor src="' + url + '" width="100%" height="100%"]';
                }).join('');
                
                if (typeof tinymce !== 'undefined' && tinymce.activeEditor) {
                    tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode);
                } else {
                    let editor = document.querySelector('#content');
                    if (editor) {
                        editor.value += shortcode;
                    }
                }
            });
            frame.open();
        });
    }
});
