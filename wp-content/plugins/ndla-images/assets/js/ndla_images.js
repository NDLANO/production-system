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
            call_api(q, 1);
        });

    });

    function resized() {
        if (jQuery(window).width() < 768) jQuery('.thumb').addClass('small'); else jQuery('.thumb').removeClass('small');
    }

    function call_api(q, p) {
        if (p in cache)
            render_px_results(q, p, cache[p]);
        else {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'https://pixabay.com/api/?key=27347-23fd1708b1c4f768195a5093b&response_group=high_resolution&lang=' + lang + '&image_type=' + image_type + '&orientation=' + orientation + '&per_page=' + per_page + '&page=' + p + '&search_term=' + encodeURIComponent(q));
            xhr.onreadystatechange = function () {
                if (this.status == 200 && this.readyState == 4) {
                    var data = JSON.parse(this.responseText);
                    if (!(data.totalHits > 0)) {
                        jQuery('#ndla-results').html('<div style="color:#d71500;font-size:16px">No hits</div>');
                        return false;
                    }
                    cache[p] = data;
                    render_px_results(q, p, data);
                }
            };
            xhr.send();
        }
        return false;
    }

    function select_image(element) {
        $(element).addClass("selected");
    }

    function render_px_results(q, p, data) {
        var results_container = jQuery("#ndla-results");
        hits = data['hits']; // store for upload click
        pages = Math.ceil(data.totalHits / per_page);
        var s = '';
        jQuery.each(data.hits, function (k, v) {
            s += '<div class="thumb" data-idx="' + k + '"><img style="width:' + v.previewWidth + 'px;height:' + v.previewHeight + 'px;" src="' + v.previewURL + '"></div>';
        });
        s += '<div style="clear:both;height:30px"></div><div id="paginator" style="text-align:center">';
        if (p == 1)
            s += '<span class="button disabled">Prev</span>';
        else
            s += '<a href="#" data-index="' + (p - 1) + '" class="button">Prev</a>';
        for (i = 1; i < pages + 1; i++) {
            s += '<a href="#" data-index="' + i + '" class="button' + (p == i ? ' disabled' : '') + '">' + i + '</a>';
        }
        if (p == pages)
            s += '<span class="button disabled">Next</span>';
        else
            s += '<a href="#" data-index="' + (p + 1) + '" class="button">Next</a>';
        s += '</div>';
        results_container.html(s);
        results_container.on('click', '.button', function() {
            p = $( this ).data('index');
            call_api(q, p);
        });

        results_container.on('click', '.thumb > img', function () {
            select_image($(this));
            if (tinyMCE && tinyMCE.activeEditor) {
                tinyMCE.activeEditor.selection.setContent('<img src="' + $(this)[0].src + '" />');
                tb_remove();
            }
        });

        resized();

    }

})(jQuery);