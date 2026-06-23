<?php
/**
 * The template for displaying posts in the Gallery post format
 */

$gallery = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'data_gallery') : '';
?>
<div class="flw padding-right">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="flw flex-parent sc-blog-item">
			<?php if(!is_single()): ?>
				<?php if(!empty($gallery)){ ?>
					<div class="blog-post-cover">
					<div class="blog-post-cover blog-post-gallery">
						<?php foreach($gallery as $key): ?>
							<img src="<?php echo fw_resize($key['url'], '900', '400', true); ?>" alt="<?php esc_attr_e('Blog image', 'blockter'); ?>">
						<?php endforeach; ?>
						<?php blockter_sticky_post();/*sticky post*/ ?>
					</div>
					</div>

				<?php } ?>
			<?php endif; ?>
			<div class="blog-post-content">
				<h2 class="post-tit" itemprop="headline">
					<?php /*sticky post*/
						if(is_sticky()){
							echo '<span class="theme-sticky"><i class="fa fa-thumb-tack" aria-hidden="true"></i></span>';
						}
					?>
					<?php if(is_single()): ?>
						<?php the_title(); ?>
					<?php else: ?>
						<a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<?php endif; ?>
				</h2>
				<?php if(is_single()) :?>
					<div class="blog-post-infor posted_date">
						<?php echo get_the_date(); ?>
					</div>
				<?php endif; ?>
				<ul class="blog-post-info">
					<?php if(!is_single()) :?>
						<li class="posted_date">
						<a itemprop="url" href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
						</li>
					<?php endif;?>
					
				</ul>
				<div class="blog-post-single" itemprop="articleBody"><?php the_content(); wp_link_pages(); ?></div>
				
				<?php if(!is_single()): ?>
					<div class="blog-post-sumary" itemprop="description"><?php the_excerpt(); ?></div>
				<?php else: ?>
					<div class="blog-post-single" itemprop="articleBody"><?php the_content(); 
					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'blockter' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) ); 
					?></div>
					<div class="theme-tags" itemprop="keywords"><?php echo the_tags($before = '', $sep = ', ', $after = ''); ?></div>
					
					<div class="consult-comment-related flw">
						<?php /*comment*/
							if ( comments_open() || get_comments_number() ) {
							comments_template();
						} ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</article>
</div>