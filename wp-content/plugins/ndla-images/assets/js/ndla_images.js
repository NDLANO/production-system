(function($) {

    var form;
    var container;
    var per_page=10;
    var selected_image;

    jQuery(document).ready(function() {
        var wWidth = $(window).width();
        var dWidth = wWidth * 0.8;
        var wHeight = $(window).height();
        var dHeight = wHeight * 0.8;

        var ndlaDialog = $('#ndla-images-content');
        ndlaDialog.dialog({
            title: 'My Dialog',
            dialogClass: 'wp-dialog',
            autoOpen: false,
            draggable: false,
            width: dWidth,
            height: dHeight,
            modal: true,
            resizable: false,
            closeOnEscape: true,
            position: {
                my: "center",
                at: "center",
                of: window
            },
            open: function () {
                // close dialog by clicking the overlay behind it
                $('.ui-widget-overlay').bind('click', function(){
                    $('#ndla-images-content').dialog('close');
                });

                form = ndlaDialog.find('#ndla-image-form');
                container = ndlaDialog.find('#ndla-results-container');
                form.submit(function (e) {
                    e.preventDefault();
                    cache = {};
                    var query = jQuery('#q', form).val();

                    ndla_call_api(query, 1, container);
                });

                ndlaDialog.find('.ndla-image-button-insert').on('click', selected_image, function(event) {
                    var image = event.data;
                    wp.media.editor.insert('<img src="' + image.src + '" />');
                    $('#ndla-images-content').dialog('close');
                })
            },
            create: function () {
                // style fix for WordPress admin
                $('.ui-dialog-titlebar-close').addClass('ui-button');
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


            var data = {
                'action': 'ndla_image_search',
                'query': queryString,
                'page': pageNr,
                'pageSize': per_page
            };

            jQuery.get(ajaxurl, data, function(res) {
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
            s += '<div class="thumb attachment" data-idx="' + k + '"><img data-idx="' + k + '" style="width:160px;height:120px;" src="' + v.previewUrl + '"><button type="button" class="button-link check" tabindex="-1"><span class="media-modal-icon"></span><span class="screen-reader-text">Fjern markering</span></button></div>';
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

        results_container.off('click', '.thumb > img');
        results_container.on('click', '.thumb > img', hits, function (event) {
             wp.media.editor.insert('<img src="' + $(this)[0].src + '" />');
        });

        resized();
    }

})(jQuery);