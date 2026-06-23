<?php
/**
 * The Template for displaying Network search results.
 */
get_header();

$find  = get_search_query();
$terms = get_terms( array(
	'taxonomy'   => 'networks',
	'orderby'    => 'name',
	'order'      => 'ASC',
	'name__like' => $find,
	'hide_empty' => false,
) );
?>

<main id="main" class="page_content flw blog-page blog-standard search_result_page">
	<div class="container">
		<div class="row">
			<?php if ( is_active_sidebar( 'blog-widget' ) ) : ?>
			<div class="col-md-9 col-lg-9">
				<div class="theme-blog-single">
					<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
					<header class="page-header">
						<h3 class="page-title"><?php printf( esc_html__( 'Networks matching: %s', 'blockter' ), '<em>' . esc_html( $find ) . '</em>' ); ?></h3>
					</header>
					<ul class="sb-related-list search-tax-results">
						<?php foreach ( $terms as $term ) : ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
								<?php echo esc_html( $term->name ); ?>
							</a>
							<?php if ( ! empty( $term->description ) ) : ?>
							<span class="search-tax-desc"><?php echo esc_html( $term->description ); ?></span>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="col-md-3 col-lg-3">
				<?php get_sidebar(); ?>
			</div>

			<?php else : ?>

			<div class="col-md-9 not-active-sidebar">
				<div class="theme-blog-single">
					<?php if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) : ?>
					<header class="page-header">
						<h3 class="page-title"><?php printf( esc_html__( 'Networks matching: %s', 'blockter' ), '<em>' . esc_html( $find ) . '</em>' ); ?></h3>
					</header>
					<ul class="sb-related-list search-tax-results">
						<?php foreach ( $terms as $term ) : ?>
						<li>
							<a href="<?php echo esc_url( get_term_link( $term ) ); ?>">
								<?php echo esc_html( $term->name ); ?>
							</a>
							<?php if ( ! empty( $term->description ) ) : ?>
							<span class="search-tax-desc"><?php echo esc_html( $term->description ); ?></span>
							<?php endif; ?>
						</li>
						<?php endforeach; ?>
					</ul>
					<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
