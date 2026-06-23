<?php
/**
 * The template for displaying posts in the Quote post format
 */
$quote_bg = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'data_quote_bg') : '';
$quote = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'data_quote') : '';
$quote_author = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'author_quote') : '';
$quote_subtitle = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'subtitle_quote') : '';
?>
<div class="flw padding-right">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="flw flex-parent sc-blog-item">
		<?php if(!is_single()): ?>
			<?php if(!empty($quote_bg)){ ?>
				<div class="blog-post-cover blog-post-quote" style="background-image:url(<?php echo esc_url($quote_bg['url']); ?>);">
					<div class="quote-subtitle"><?php echo esc_html($quote_subtitle); ?></div>
					<div class="quote-data"><?php echo esc_html($quote); ?></div>
					<div class="quote-author"><?php echo esc_html($quote_author); ?></div>
					<?php blockter_sticky_post();/*sticky post*/ ?>
				</div>
			<?php } ?>
		<?php endif; ?>
			<div class="blog-post-content">
				<h2 class="post-tit" itemprop="headline">
					<?php if(is_single()): ?>
						<?php the_title(); ?>
						<div class="blog-post-infor posted_date">
							<?php echo blockter_post_date(); ?>
						</div>
					<?php else: ?>
						<a itemprop="url" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<?php endif; ?>
				</h2>
				<div class="blog-post-single" itemprop="articleBody"><?php the_content(); wp_link_pages(); ?></div>
				<ul class="blog-post-info">
					<?php if(!is_single()) :?>
						<li class="posted_date">
							<?php echo blockter_post_date(); ?>
						</li>
					<?php endif;?>
				</ul>
				<?php if(!is_single()): ?>
					<div class="blog-post-sumary" itemprop="description"><?php the_excerpt(); ?></div>
				<?php else: ?>
					<div class="blog-post-single" itemprop="articleBody"><?php the_content(); wp_link_pages(); ?></div>
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
<?php
/**
 * The template for displaying posts in the Quote post format
 */
$quote_bg = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'data_quote_bg') : '';
$quote = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'data_quote') : '';
$quote_author = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'author_quote') : '';
$quote_subtitle = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($post->ID, 'subtitle_quote') : '';
?>
<div class="flw padding-right">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<div class="flw flex-parent sc-blog-item">
		<?php if(!is_single()): ?>
			<?php if(!empty($quote_bg)){ ?>
				<div class="blog-post-cover blog-post-quote" style="background-image:url(<?php echo esc_url($quote_bg['url']); ?>);">
					<div class="quote-subtitle"><?php echo esc_html($quote_subtitle); ?></div>
					<div class="quote-data"><?php echo esc_html($quote); ?></div>
					<div class="quote-author"><?php echo esc_html($quote_author); ?></div>
					<?php blockter_sticky_post();/*sticky post*/ ?>
				</div>
			<?php } ?>
		<?php endif; ?>
			<div class="blog-post-content">
				<h2 class="post-tit" itemprop="headline">
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
							<?php echo blockter_post_date(); ?>
						</li>
					<?php endif;?>
				</ul>
				
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
		
					<div class="theme-tags" itemprop="keywords"><?php echo the_tags($before = '', $sep = '', $after = ''); ?></div>
					
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
