(function ($) {
	$doc = $(document);
	$doc.ready(
		function () {
			/*Retrieve posts*/
			// var container = $( '.container-async' );
			// container.each(
			// 	function() {
			// 		var $movie_tab_id = $( this ).attr( 'id' ),
			// 		$movie_tab        = "#" + $movie_tab_id;
			// 		// $content = $($movie_tab).find('.category-content');

			$(document).on(
				'click',
				'button[data-filter], .category-pagination li a',
				function (event) {
					event.preventDefault();
					var $this = $(this),
						bigElement = $(this).parents('.container-async'),
						$content = bigElement.find('.category-content'),
						$mediaType = null;
					posts_per_page = bigElement.data('paged');
					row = bigElement.data('row');
					/*Set filter active*/
					if ($this.data('filter')) {
						$this.addClass('active').siblings().removeClass('active');
						$page = $this.data('page');
						$mediaType = $this.data('media');
						$postin = $this.data('postin');
					} else {
						/*Pagination*/
						$page = parseInt($this.attr('href').replace(/\D/g, ''));
						$mediaType = $this.parent().data('media');
						$postin = $this.parent().data('postin');
						$this = bigElement.find('.category-filter button.active');
					}
					$content.addClass('loading');
					$content.append('<div class="ajaxloading"></div>');
					$params = {
						'page': $page,
						'tax': $this.data('filter'),
						'term': $this.data('term'),
						'qty': posts_per_page,
						'row': row,
						'media': $mediaType,
						'postin': $this.data('postin'),
					};
					console.log($page);

					/*Run query*/
					get_posts($params, $content);
				}
			);
			$('button[data-term]:first-child').trigger('click');
			// }
			// );
		}
	);

	function get_posts($params, $content) {
		$.ajax(
			{
				type: 'POST',
				url: blockter.ajax_url,
				data: {
					action: 'do_filter_posts',
					nonce: blockter.nonce,
					params: $params
				},
				dataType: 'json',
				success: function (data, textStatus, XMLHttpRequest) {
					// console.log( data );
					if (data.status === 200) {
						// console.log( data );
						$content.html(data.content);
					} else if (data.status === 201) {
						$content.html(data.message);
					}
				},
				error: function (MLHttpRequest, textStatus, errorThrown) {

				},
				complete: function (data, textStatus) {

					msg = textStatus;

					if (textStatus === 'success') {
						msg = data.responseJSON.found;
					}

					$content.removeClass('loading');
				}
			}
		);
	}

})(jQuery);