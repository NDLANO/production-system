(function($){


    function initializeNDLAField(metaBox) { // Set all variables to be used in scope

        var lang = 'en';
        var per_page=10;

        var delImgLink = metaBox.find('.delete-ndla-image'),
            addImgLink = metaBox.find('.add-ndla-image'),
            imgContainer = metaBox.find('.ndla-image-container'),
            imgIdInput = metaBox.find('.acf-ndla_image-value'),
            form = metaBox.find('#ndla-image-form'),
            input = form.find('#q'),
            submitButton = form.find("#submit-search");

        input.keyup(function(event){
            if(event.keyCode == 13){
                submitButton.click();
            }
        });

        submitButton.on('click', function () {
            cache = {};
            q = input.val();
            if (jQuery('#filter_photos', form).is(':checked') && !jQuery('#filter_cliparts', form).is(':checked')) image_type = 'photo';
            else if (!jQuery('#filter_photos', form).is(':checked') && jQuery('#filter_cliparts', form).is(':checked')) image_type = 'clipart';
            else image_type = 'all';
            if (jQuery('#filter_horizontal', form).is(':checked') && !jQuery('#filter_vertical', form).is(':checked')) orientation = 'horizontal';
            else if (!jQuery('#filter_horizontal', form).is(':checked') && jQuery('#filter_vertical', form).is(':checked')) orientation = 'vertical';
            else orientation = 'all';
            call_api(q, 0);
        });

        function resized() {
            if (jQuery(window).width() < 768) jQuery('.thumb').addClass('small'); else jQuery('.thumb').removeClass('small');
        }

        function call_api(q, p) {
            if (p in cache)
                render_px_results(q, p, cache[p]);
            else {

                var data = {
                    'action': 'ndla_image_search',
                    'query': q,
                    'page': p,
                    'pageSize': per_page
                };

                jQuery.get(ajaxurl, data, function(res) {
                    var response = JSON.parse(res);

                    if (!(response.totalCount > 0)) {
                        jQuery('#ndla-results-container').html('<div style="color:#d71500;font-size:16px">No hits</div>');
                        return false;
                    }
                    render_px_results(q, p, response);
                });
                // var xhr = new XMLHttpRequest();
                // xhr.open('GET', 'https://pixabay.com/api/?key=27347-23fd1708b1c4f768195a5093b&response_group=high_resolution&lang=' + lang + '&image_type=' + image_type + '&orientation=' + orientation + '&per_page=' + per_page + '&page=' + p + '&search_term=' + encodeURIComponent(q));
                // xhr.onreadystatechange = function () {
                //     if (this.status == 200 && this.readyState == 4) {
                //         var data = JSON.parse(this.responseText);
                //         cache[p] = data;
                //         render_px_results(q, p, data);
                //     }
                // };
                // xhr.send();
            }
            return false;
        }

        function select_image(element) {
            $(element).addClass("selected");
        }

        function render_px_results(q, p, data) {
            var results_container = jQuery("#ndla-results-container");
            hits = data['hits']; // store for upload click
            pages = Math.ceil(data.totalCount / per_page);
            var s = '';
            jQuery.each(data.results, function (k, v) {
                s += '<div class="thumb" data-idx="' + k + '"><img data-idx="' + k + '" style="width:160px;height:120px;" src="' + v.previewUrl + '"></div>';
            });
            s += '<div style="clear:both;height:30px"></div><div id="paginator" style="text-align:center">';
            if (p == 0)
                s += '<span class="button disabled">Prev</span>';
            else
                s += '<a href="#" data-index="' + (p - 1) + '" class="button">Prev</a>';
            for (i = 1; i < pages + 1; i++) {
                s += '<a href="#" data-index="' + (i - 1) + '" class="button' + (p == i ? ' disabled' : '') + '">' + i + '</a>';
            }
            if (p == pages - 1)
                s += '<span class="button disabled">Next</span>';
            else
                s += '<a href="#" data-index="' + (p + 1) + '" class="button">Next</a>';
            s += '</div>';
            results_container.html(s);
            results_container.off('click', '.button');
            results_container.on('click', '.button', function () {
                p = $(this).data('index');
                call_api(q, p);
            });

            results_container.off('click', '.thumb > img');
            results_container.one('click', '.thumb > img', data.results, function (event) {
                // Send the attachment URL to our custom image input field.
                var index = $(this).data('idx');
                var image = event.data[index];
                imgContainer.append('<img src="' + image.previewUrl + '" alt="" style="max-width:320px;"/>');

                // Send the attachment id to our hidden input
                imgIdInput.val(image.id);

                // Hide the add image link
                addImgLink.addClass('hidden');

                // Unhide the remove image link
                delImgLink.removeClass('hidden');

                // Clear and remove thickbox
                input.val('');
                results_container.html('');

                tb_remove();
            });

            resized();
        }

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


    }

    $(document).on('acf/setup_fields', function (e, ndla_container) {
        $(ndla_container).find('.field[data-field_type="ndla_image"]').each(function () {
            initializeNDLAField($(this));
        })
    });
})(jQuery);
