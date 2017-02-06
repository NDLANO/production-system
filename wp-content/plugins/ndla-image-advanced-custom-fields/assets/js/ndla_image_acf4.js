(function($){

    function initializeNDLAField(metaBox) { // Set all variables to be used in scope

        var delImgLink,
            addImgLink,
            imgContainer,
            imgIdInput,
            form,
            input,
            submitButton;

        delImgLink = metaBox.find('.delete-ndla-image');
        addImgLink = metaBox.find('.add-ndla-image');
        imgContainer = metaBox.find('.ndla-image-container');
        imgIdInput = metaBox.find('.acf-ndla_image-value');
        form = metaBox.find('#acf-ndla-images-form');

        input = form.find('#q');
        submitButton = form.find("#submit-search");

        // DELETE IMAGE LINK
        delImgLink.off('click');
        delImgLink.on('click', function (event) {

            event.preventDefault();

            // Clear out the preview image
            imgContainer.html('');

            // Un-hide the add image link
            addImgLink.removeClass('hidden');

            // Hide the delete image link
            delImgLink.addClass('hidden');

            // Delete the image id from the hidden input
            imgIdInput.val('');

        });

        input.keyup(function (event) {
            if (event.keyCode == 13) {
                submitButton.click();
            }
        });

        submitButton.on('click', function (event) {
            cache = {};
            var container = $(event.toElement).parents('.ndla-images');
            var input = container.find("#q");
            q = input.val();

            window.ndla_call_api(q, 0, container, function (event) {
                // Send the attachment URL to our custom image input field.
                var index = $(event.toElement).data('idx');
                var image = event.data[index];
                imgContainer.append('<img src="' + image.previewUrl + '" alt="" style="max-width:320px;"/>');

                // Send the attachment id to our hidden input
                imgIdInput.val(image.id);

                // Hide the add image link
                addImgLink.addClass('hidden');

                // Unhide the remove image link
                delImgLink.removeClass('hidden');
            });


        });

    }

    $(document).on('acf/setup_fields', function (e, ndla_container) {
        $(ndla_container).find('.field[data-field_type="ndla_image"]').each(function () {
            initializeNDLAField($(this));
        })
    });
})(jQuery);
