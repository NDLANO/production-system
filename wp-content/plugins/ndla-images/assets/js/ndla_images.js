(function($) {

    var form;
    var container;
    var sidebar;
    var per_page=10;
    var selectedIndex = -1;
    var selectedImage;
    var selectedPhoto;
    var imageDetailsCache = {};
    var numColumns;
    var columnWidth = 150;
    var submitButton;

    jQuery(document).ready(function() {
        var ndlaDialog = $('#ndla-images-content');
        ndlaDialog.dialog({
            title: 'My Dialog',
            dialogClass: 'wp-dialog',
            autoOpen: false,
            draggable: false,
            width: "auto",
            height: "auto",
            modal: true,
            resizable: false,
            closeOnEscape: true,

            open: function () {
                // close dialog by clicking the overlay behind it
                $('.ui-widget-overlay').bind('click', function(){
                    $('#ndla-images-content').dialog('close');
                });

                $('body').css('overflow','hidden');

                form = ndlaDialog.find('#ndla-image-form');
                sidebar = ndlaDialog.find('.ndla-media-sidebar');
                container = ndlaDialog.find('#ndla-results-container > ul');
                numColumns = Math.floor(container.width() / columnWidth );
                ndlaDialog.find(".results-container")[0].dataset.columns = numColumns;
                form.submit(function (e) {
                    e.preventDefault();
                    cache = {};
                    var query = jQuery('#q', form).val();

                    ndla_call_api(query, 1, container);
                });

                submitButton  = ndlaDialog.find('.ndla-image-button-insert');
                submitButton.on('click', selectedIndex, function() {
                    wp.media.editor.insert('<img src="' + selectedPhoto.previewUrl + '" />');
                    $('#ndla-images-content').dialog('close');
                })
            },
            create: function () {
                // style fix for WordPress admin
                $('.ui-dialog-titlebar-close').addClass('ui-button');
            },
            close: function () {
                $('body').css('overflow','scroll');
            },
            resize: function (event, ui) {

            }
        });
        // bind a button or a link to open the dialog
        $('#ndla-images-open').click(function(e) {
            e.preventDefault();
            $('#ndla-images-content').dialog('open');
        });



    });

    function resized() {
        if (jQuery(window).width() < 768) jQuery('.thumb').addClass('small'); else jQuery('.thumb').removeClass('small');
    }

    window.ndla_call_api = function(queryString, pageNr, results_container, selectCallback) {
        if (pageNr in cache)
            render_px_results(queryString, pageNr, cache[pageNr], selectCallback);
        else {
            findImages(queryString, pageNr, per_page, function(res) {
                var response = JSON.parse(res);

                if (!(response.totalCount > 0)) {
                    results_container.html('<div style="color:#d71500;font-size:16px">No hits</div>');
                    return false;
                }
                render_px_results(queryString, pageNr, response, results_container, selectCallback);
            });
        }

        return false;
    };

    function render_px_results(q, p, data, container) {
        var results_container = container;
        var hits = data['results']; // store for upload click
        var pages = Math.ceil(data.totalCount / per_page);
        var s = '';
        jQuery.each(data.results, function (k, v) {
            s += '<li class="attachment"><div class="attachment-preview" data-idx="' + k + '"><div class="thumbnail"><div class="centered"><img data-idx="' + k + '" src="' + v.previewUrl + '" style="max-width:250px;max-height:250px"></div></div></div><button type="button" class="button-link check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text">Fjern markering</span></button></li>';
        });
        s += '<div style="clear:both;height:30px"></div><div id="paginator" style="text-align:center">';
        if (p == 1)
            s += '<span class="button disabled">Prev</span>';
        else
            s += '<a href="#" data-index="' + (p - 1) + '" class="button">Prev</a>';
        for (var i = 1; i < pages + 1; i++) {
            s += '<a href="#" data-index="' + i + '" class="button' + (p == i ? ' disabled' : '') + '">' + i + '</a>';
        }
        if (p == pages)
            s += '<span class="button disabled">Next</span>';
        else
            s += '<a href="#" data-index="' + (p + 1) + '" class="button">Next</a>';
        s += '</div>';
        results_container.html(s);
        results_container.off('click', '.button');
        results_container.on('click', '.button', function () {
            p = $(this).data('index');
            ndla_call_api(q, p, container);
        });

        results_container.off('click', '.thumbnail');
        results_container.on('click', '.thumbnail', hits, thumbnailClicked);

        resized();
    }

    function thumbnailClicked(event) {
        var hits = event.data;
        var container = $(event.target).parents('li');
        var img = container.find('img');
        var index = img.data('idx');
        if (selectedIndex == index) {
            selectedImage.removeClass('details selected');
            hideImageDetails();
            submitButton.attr('disabled', true);
            selectedIndex = -1;
        } else {
            if (selectedIndex != -1) {
                selectedImage.removeClass('details selected');
            }
            var image = hits[index];

            selectImage(index, image, container);
            submitButton.attr('disabled', false);
        }
    }

    function selectImage(index, image, container) {
        selectedIndex = index;
        selectedImage = container;
        selectedImage.addClass('details selected');
        showImageDetails(image);
    }

    function findImages(query, pageNr, imagesPerPage, callback) {
        var data = {
            'action': 'ndla_image_search',
            'query': query,
            'page': pageNr,
            'pageSize': imagesPerPage
        };

        jQuery.get(ajaxurl, data, callback);
    }

    function getImageDetails(imageId, callback) {
        var data = {
            'action': 'ndla_image_details',
            'imageid': imageId
        };

        jQuery.get(ajaxurl, data, callback);
    }


    function showImageDetails(image) {
        selectedPhoto = image;
        var imageId = image.id;
        if (imageDetailsCache[imageId]) {
            showImage(imageDetailsCache[imageId]);
        } else {
            getImageDetails(image.id, function (responseString) {
                var image = JSON.parse(responseString);
                showImage(image);
                imageDetailsCache[imageId] = image;
            });
        }
    }

    function showImage(image) {
        var html = '<div class="attachment-info"><h2>Bildedetaljer</h2>';
        html += '<img src="' + image.imageUrl + '" class="thumbnail thumbnail-image" />';
        html += "<div class='details'><ul>";
        html += "<li>Tittel: " + image.titles[0].title + "</li>";
        html += "<li>Lisens: " + image.copyright.license.description + "</li>";
        html += "</ul></div></div>";
        sidebar.html(html);
    }

    function hideImageDetails() {
        sidebar.html('');
    }

})(jQuery);