(function($) {

    var form;
    var lang = 'en';
    var per_page=10;

    jQuery(document).ready(function() {
        form = jQuery('#ndla-image-form');

        form.submit(function (e) {
            e.preventDefault();
            cache = {};
            q = jQuery('#q', form).val();
            if (jQuery('#filter_photos', form).is(':checked') && !jQuery('#filter_cliparts', form).is(':checked')) image_type = 'photo';
            else if (!jQuery('#filter_photos', form).is(':checked') && jQuery('#filter_cliparts', form).is(':checked')) image_type = 'clipart';
            else image_type = 'all';
            if (jQuery('#filter_horizontal', form).is(':checked') && !jQuery('#filter_vertical', form).is(':checked')) orientation = 'horizontal';
            else if (!jQuery('#filter_horizontal', form).is(':checked') && jQuery('#filter_vertical', form).is(':checked')) orientation = 'vertical';
            else orientation = 'all';
            ndla_call_api(q, 1);0
        });

    });

    function resized() {
        if (jQuery(window).width() < 768) jQuery('.thumb').addClass('small'); else jQuery('.thumb').removeClass('small');
    }

    window.ndla_call_api = function(q, p, selectCallback) {
        if (p in cache)
            render_px_results(q, p, cache[p], selectCallback);
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
                render_px_results(q, p, response, selectCallback);
            });
        }
        return false;
    };

    function render_px_results(q, p, data, selectCallback) {
        var results_container = jQuery("#ndla-results-container");
        hits = data['results']; // store for upload click
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
            ndla_call_api(q, p. selectCallback);
        });

        results_container.off('click', '.thumb > img');
        results_container.on('click', '.thumb > img', hits, function (event) {
            if (selectCallback) {
                selectCallback(event);
            } else {
                wp.media.editor.insert('<img src="' + $(this)[0].src + '" />');
            }
            tb_remove();
        });

        resized();
    }

})(jQuery);