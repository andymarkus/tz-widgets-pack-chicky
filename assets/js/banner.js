(function($) {
    "use strict";
    $(document).ready(function () {

        var frame,
            metaBox = $('#tz-banner-widget-container'),
            addImgLink = metaBox.find('.image-upload'),
            delImgLink = metaBox.find( '.image-delete');

        // ADD IMAGE LINK
        addImgLink.live( 'click', function( event ){

            event.preventDefault();

            // If the media frame already exists, reopen it.
            if ( frame ) {
                frame.open();
                return;
            }

            // Create a new media frame
            frame = wp.media({
                title: 'Select Image',
                button: {
                    text: 'Select Image'
                },
                multiple: !1  // Set to true to allow multiple files to be selected
            });


            // When an image is selected in the media frame...
            frame.on( 'select', function() {

                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();

                var imgContainerM = $('body').find('#tz-banner-widget-container .tz-image-preview-container');
                var imgIdInputM = $('body').find('#tz-banner-widget-container [id$=image_url]');
                var addImgLinkM = $('body').find('#tz-banner-widget-container .image-upload');
                var delImgLinkM = $('body').find('#tz-banner-widget-container .image-delete');
                // Send the attachment URL to our custom image input field.
                imgContainerM.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

                // Send the attachment id to our hidden input
                imgIdInputM.val( attachment.url );

                // Hide the add image link
                addImgLinkM.addClass( 'hidden' );

                // Unhide the remove image link
                delImgLinkM.removeClass( 'hidden' );


            });

            // Finally, open the modal on click
            frame.open();
        });


        // DELETE IMAGE LINK
        delImgLink.live( 'click', function( event ){

            event.preventDefault();


            var imgContainerM = $('body').find('#tz-banner-widget-container .tz-image-preview-container');
            var imgIdInputM = $('body').find('#tz-banner-widget-container [id$=image_url]');
            var addImgLinkM = $('body').find('#tz-banner-widget-container .image-upload');
            var delImgLinkM = $('body').find('#tz-banner-widget-container .image-delete');

            // Clear out the preview image
            imgContainerM.html( '' );

            // Un-hide the add image link
            addImgLinkM.removeClass( 'hidden' );

            // Hide the delete image link
            delImgLinkM.addClass( 'hidden' );

            // Delete the image id from the hidden input
            imgIdInputM.val( '' );

        });




    });
})(jQuery);