(function($) {
    "use strict";
    $(document).ready(function () {

        var frame,
            metaBox = $('#tz-menu-image-widget-container'),
            addImgLink = metaBox.find('.image-upload'),
            delImgLink = metaBox.find( '.image-delete'),

            backCheck = metaBox.find( '[id$=background]' );

        $(backCheck).on ('change', function(){
            $(linkInputCont).toggleClass('hidden');
        });


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
                var  _metaBox = $('#tz-menu-image-widget-container');
                var   imgContainer = _metaBox.find( '.tz-image-preview-container');
                var   imgIdInput = _metaBox.find( '[id$=image_url]' );
                var   linkInputCont = _metaBox.find( '#tz-img-link' );
                var   backToggleCont = _metaBox.find( '#tz-back-toggle' );

                // Get media attachment details from the frame state
                var attachment = frame.state().get('selection').first().toJSON();


                // Send the attachment URL to our custom image input field.
                imgContainer.append( '<img src="'+attachment.url+'" alt="" style="max-width:100%;"/>' );

                // Send the attachment id to our hidden input
                imgIdInput.val( attachment.url );

                // Hide the add image link
                addImgLink.addClass( 'hidden' );

                // Unhide the remove image link
                delImgLink.removeClass( 'hidden' );

                backToggleCont.toggleClass( 'hidden' );

            });

            // Finally, open the modal on click
            frame.open();
        });


        // DELETE IMAGE LINK
        delImgLink.on( 'click', function( event ){

            event.preventDefault();

            // Clear out the preview image
            imgContainer.html( '' );

            // Un-hide the add image link
            addImgLink.removeClass( 'hidden' );

            // Hide the delete image link
            delImgLink.addClass( 'hidden' );

            // Delete the image id from the hidden input
            imgIdInput.val( '' );

        });




    });
})(jQuery);