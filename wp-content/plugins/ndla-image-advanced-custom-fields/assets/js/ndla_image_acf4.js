(function($){

    function initializeNDLAField(metaBox) { // Set all variables to be used in scope

        var delImgLink,
            imgContainer,
            imgIdInput,
            noImage,
            form,
            input,
            submitButton;

        delImgLink = metaBox.find('.delete-ndla-image');
        imgContainer = metaBox.find('.ndla-image-container');
        noImage = metaBox.find('.no-image');
        imgIdInput = metaBox.find('.acf-ndla_image-value');
        form = metaBox.find('#acf-ndla-images-form');

        input = form.find('#q');
        submitButton = form.find("#submit-search");

        // DELETE IMAGE LINK
        delImgLink.off('click');
        delImgLink.on('click', function (event) {

            event.preventDefault();
            var img = imgContainer.find('.ndla-image');
            img.attr('src', '');
            img.addClass('hidden');

            imgContainer.parent().removeClass('active');

            noImage.removeClass('hidden');

            // Hide the delete image link
            delImgLink.addClass('hidden');

            // Delete the image id from the hidden input
            imgIdInput.val('');

        });

        input.keyup(submitButton, function (event) {
            if (event.keyCode == 13) {
                var button = event.data;
                button.click();
            }
        });

        var select_image = function (event) {
            // Send the attachment URL to our custom image input field.
            var index = $(event.target).data('idx');
            var image = event.data[index];
            var img = imgContainer.find('.ndla-image');
            img.attr('src', image.previewUrl);
            img.removeClass('hidden');
            noImage.addClass('hidden');
            imgContainer.parent().addClass('active');

            // Send the attachment id to our hidden input
            imgIdInput.val(image.id);

            // Show the remove image link
            delImgLink.removeClass('hidden');
        };


        submitButton.on('click', function (event) {
            cache = {};
            var container = $(event.target).parents('.ndla-images');
            var input = container.find("#q");
            q = input.val();

            window.ndla_call_api(q, 1, container, select_image);
        });

    }

    $(document).on('acf/setup_fields', function (e, ndla_container) {
        $(ndla_container).find('.field[data-field_type="ndla_image"]').each(function () {
            initializeNDLAField($(this));
        })
    });

    $(document).on('acf/validate_field', function( e, f ){
        // vars
        var field = $(f);

        var field_type = field.data('field_type');
        if (field_type == 'ndla_image') {
            var key = field.data('field_key');
            var valuefield = field.find("input[type='hidden'][name='fields[" + key + "]']");

            // set validation to false on this field
            if( valuefield.val() != '' && valuefield.val() != '0') {
                field.data('validation', true);
            } else {
                field.data('validatoin', false);
            }
        }


    });


})(jQuery);
