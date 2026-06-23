<?php $args = cast_query_by_termid(); ?>
<div class="movie-wrapper">
	<div class="celebrity-topbar-filter">
		<!-- sort by -->
		<form class="celebrity-sorting">
			<span><?php echo esc_html__( 'Sort By:', 'blockter' ); ?></span>
			<select name="sortby" class="consult-dropdown-list">
				<?php
					$orderby_options = array(
						'default' => 'Default',
						'post_title' => 'Title',
					);
					$sortby = array_key_exists( 'sortby', $_GET ) ? $_GET['sortby'] : '';
					foreach ( $orderby_options as $value => $label ) {
						echo '<option ' . selected( $sortby, $value ) . ' value=' . esc_attr( $value ) . '>' . esc_attr( $label ) . '</option>';
					}
					if ( ! empty( $sortby ) && $sortby == 'default' ) {
						$args = cast_query_by_termid();
					}
					if ( ! empty( $sortby ) && $sortby == 'post_title' ) {
						$args = cast_query_by_termname();
					}
				?>
			</select>
		</form>
		<div class="filter-right">
			<div class="celebrity-view btn-group">
				<a href="#"  class="ion-ios-list-outline list"></a>
				<a href="#"  class="ion-grid current grid"></a>
			</div>
		</div>
	</div>
	<?php echo blockter_get_cast_grid( $args ); ?>
</div>




