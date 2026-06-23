/* globals global */
jQuery(function($){
    function blockterUpdateSearchPlaceholder($form) {
        var $sel = $form.find('[name="topsortby"]');
        var $inputs = $form.find('.header-search-form-input');
        if (!$sel.length || !$inputs.length) {
            return;
        }
        var label = $sel.find('option:selected').text();
        var prefix = (typeof global.searchPlaceholderPrefix !== 'undefined') ? global.searchPlaceholderPrefix : 'Search for ';
        $inputs.attr('placeholder', prefix + label.trim());
    }

    $('.header-search-form').each(function() {
        var $form = $(this);
        var $searchOptions = $form.find('[name="topsortby"]');
        var $postType = $form.find('.post-type');
        if ($searchOptions.length && $postType.length) {
            $postType.attr('value', $searchOptions.val());
            blockterUpdateSearchPlaceholder($form);
            $searchOptions.on('change', function() {
                $postType.attr('value', $(this).val());
                blockterUpdateSearchPlaceholder($form);
            });
        }
    });

    $('.search-autocomplete').each(function() {
        var $input = $(this);
        var $form = $input.closest('form');
        var urlMap = {};
        var searchRequest;

        $input.autoComplete({
            minChars: 2,
            delay: 0,
            source: function(term, suggest){
                try { searchRequest.abort(); } catch(e){}
                urlMap = {};

                searchRequest = $.ajax({
                    url: global.ajax,
                    type: 'POST',
                    data: {
                        search: term,
                        action: 'search_site',
                        type: $form.find('.post-type').attr('value'),
                    },
                    success: function(res){
                        var choices = res.data;
                        var suggestions = [];
                        term = term.toLowerCase();
                        for (var i = 0; i < choices.length; i++) {
                            if (~choices[i].label.toLowerCase().indexOf(term)) {
                                suggestions.push(choices[i].label);
                                if (choices[i].url) {
                                    urlMap[choices[i].label] = choices[i].url;
                                }
                            }
                        }
                        suggest(suggestions.length ? suggestions : ['No result']);
                    }
                });
            },
            onSelect: function(event, ui) {
                if (ui && urlMap[ui]) {
                    window.location.href = urlMap[ui];
                } else {
                    $input.val(ui);
                    $form.submit();
                }
            }
        });
    });
});
