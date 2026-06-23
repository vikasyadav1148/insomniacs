(function($){
	"use strict";
    $(document).ready( function() {
        var favourite = $('.favorite-btn:not(.add-to-list-btn) button[data-user]');
        favourite.on('click',function(event){
            event.preventDefault();
            var $button = $(this);
            var user_id = $button.data('user');
            var post_id = $button.data('post');
            var show_id = $button.data('tvshow');
        	var favourite_text_change = $.trim($button.find('.favourite-text').text());
        	$.ajax({
            	url : favourite_params.ajaxurl,
            	type:'POST',
                data : {
                    action: 'buster_add_favourite',
                    user_id: user_id,
                    post_id: post_id,
                    show_id: show_id,
                },
                error: function(xhr){
        			alert("An error occured: " + xhr.status + " " + xhr.statusText);
            	},
            	success : function( data ){
	               	$button.toggleClass('favourited');
		            $button.find('.favourite-text').text(
		            	 favourite_text_change == "Favourited" ? "Favourite" : "Favourited"
		            );
	            },
            });
        });
    });
})(jQuery);
