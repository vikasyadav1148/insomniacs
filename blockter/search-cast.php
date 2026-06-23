<?php
/**
 * The Template for search cast
 */
get_header();

$find = get_search_query();
$args = array(
  'taxonomy'   => array( 'mv_actor' ),
  's'          => $find,
  'orderby'    => 'id',
  'order'      => 'ASC',
  'name__like' => $find
);

$terms = get_terms( $args );

?>

<main id="main" class="page_content flw blog-page blog-standard search_result_page celebrity-list-item">
	<div class="container">
		<div class="row">
		<?php if(is_active_sidebar('blog-widget')): ?>
			<div class="col-md-9 col-lg-9">
			<div class="theme-blog-single">
				<?php
				$count = count( $terms );
				var_dump( $count );
				if ( $count > 0 ) :
				?>
				<div class="movie-wrapper">
				<div class="taxography-grid theme-celebrity-items movie-items">
					<?php

					foreach ($terms as $term) :
						$term_id = $term->term_id;
						$term_option = fw_get_db_term_option( $term_id, 'mv_actor' );
					?>
					<div class="celebrity-grid-item celeb-list-it item list-group-item">
					<?php
						if ( array_key_exists( 'avatar_url', $term_option) && ( $term_option['avatar_url'] != '' ) ) :
					?>
					<a class="celebrity-img" href="<?php echo get_term_link( $term ); ?>">
						<img src="<?php echo esc_url( $term_option['avatar_url'] ); ?>" alt="<?php echo esc_attr( 'Actor Avatar', 'blockter' ); ?>">
					</a>
					<?php
						elseif ( array_key_exists( 'avatar', $term_option) && ( $term_option['avatar'] != '' ) ) :
					?>
					<a class="celebrity-img" href="<?php echo get_term_link( $term ); ?>">
						<?php
						$attachment_id = $term_option['avatar']['attachment_id'];
						echo wp_get_attachment_image( $attachment_id, 'blockter-cast-thumbnail-list' );
						?>
					</a>
					<?php else: ?>
					<a class="celebrity-img" href="<?php echo get_term_link( $term ); ?>">
						<div class="no-image"></div>
					</a>
					<?php endif; ?>

					<div class="celebrity-infor">
						<h4 class="celebrity-name">
						<a class="actor-name" href="<?php echo get_term_link( $term ) ?>"><?php echo esc_html( $term->name ); ?></a>
						</h4>
						<?php if ( $term->description != '' ) : ?>
						<div class="ceb-des">
							<?php echo esc_html( $term->description ); ?>
						</div>
						<?php endif; ?>
					</div><!-- .celebrity-infor -->
					</div><!-- .celebrity-grid-item -->
					<?php
					endforeach;
					else :
					get_template_part('content', 'none') ;
					endif; ?>
				</div><!-- .movie-items -->
				</div><!-- .movie-wrapper -->
			</div>
			</div>
			<div class="col-md-3 col-lg-3">
			<?php get_sidebar(); ?>
			</div>
		<?php else: ?>
			<div class="col-md-9 not-active-sidebar">
			<div class="theme-blog-single">
				<?php
				$count = count( $terms );
				if ( $count > 0 ) :
				?>
				<header class="page-header">
				<h3 class="page-title"><?php printf( esc_html__( 'Search results for: %s', 'blockter' ), get_search_query() ); ?></h3>
				</header><!-- .page-header -->
				<div class="movie-wrapper">
				<div class="taxography-grid theme-celebrity-items movie-items">
					<?php
					foreach ($terms as $term) :
						$term_id = $term->term_id;
						$term_option = fw_get_db_term_option( $term_id, 'mv_actor' );
					?>
					<div class="celebrity-grid-item celeb-list-it item list-group-item">
<?php
						if ( array_key_exists( 'avatar_url', $term_option) && ( $term_option['avatar_url'] != '' ) ) :
					?>
					<a class="celebrity-img" href="<?php echo get_term_link( $term ); ?>">
						<img src="<?php echo esc_url( $term_option['avatar_url'] ); ?>" alt="<?php echo esc_attr( 'Actor Avatar', 'blockter' ); ?>">
					</a>
					<?php
						elseif ( array_key_exists( 'avatar', $term_option) && ( $term_option['avatar'] != '' ) ) :
					?>
					<a class="celebrity-img" href="<?php echo get_term_link( $term ); ?>">
						<?php
						$attachment_id = $term_option['avatar']['attachment_id'];
						echo wp_get_attachment_image( $attachment_id, 'blockter-cast-thumbnail-list' );
						?>
					</a>
					<?php else: ?>
					<a class="celebrity-img" href="<?php echo get_term_link( $term ); ?>">
						<div class="no-image"></div>
					</a>
					<?php endif; ?>

					<div class="celebrity-infor">
						<h4 class="celebrity-name">
						<a class="actor-name" href="<?php echo get_term_link( $term ) ?>"><?php echo esc_html( $term->name ); ?></a>
						</h4>
						<?php if ( $term->description != '' ) : ?>
						<div class="ceb-des">
							<?php echo esc_html( $term->description ); ?>
						</div>
						<?php endif; ?>
					</div><!-- .celebrity-infor -->
					</div>
					<?php
					endforeach;
					else :
					get_template_part('content', 'none') ;
					endif; ?>
				</div><!-- .movie-items -->
				</div><!-- .movie-wrapper -->
			</div>
			</div>
		<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
