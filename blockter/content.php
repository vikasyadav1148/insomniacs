<div class="flw padding-right" itemscope itemtype="https://schema.org/Blog">
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/BlogPosting">
		<div class="flw sc-blog-item" itemprop="mainEntityOfPage">
			<?php /*Publisher*/ ?>
			<div itemprop="publisher" itemscope="itemscope" itemtype="https://schema.org/Organization">
				<div itemprop="logo" itemscope="itemscope" itemtype="https://schema.org/ImageObject">
					<meta itemprop="url" content="<?php echo esc_url(home_url('/')); ?>" />
					<meta itemprop="width" content="100" />
					<meta itemprop="height" content="100" />
				</div>
				<meta itemprop="name" content="<?php the_author(); ?>" />
			</div>
			<?php /*Modified date*/ ?>
			<span itemprop="dateModified" class="screen-reader-text">
				<time datetime="<?php echo esc_attr( get_the_modified_time( 'Y-m-d' ) ); ?>">
					<?php the_modified_date(); ?>
				</time>
			</span>
			<?php if(!is_single()) :?>
				<?php /*post thumbnail*/
					$thumbnail_id = get_post_thumbnail_id($post->ID);
					if(!empty($thumbnail_id)):
						global $post;
						?>
						<div class="blog-post-cover" itemprop="image" itemscope itemtype="http://schema.org/ImageObject">
							<a href="<?php the_permalink(); ?>">
		                        <?php echo wp_get_attachment_image($thumbnail_id, 'blockter-post-thumbnail-list' ); ?>
		                    </a>  
						</div>
				<?php endif; ?>
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
				<?php if(is_single()) :?>
					<div class="blog-post-cover">
						<?php 
							$thumbnail_id = get_post_thumbnail_id($post->ID);
							if(!empty($thumbnail_id)):
								echo wp_get_attachment_image($thumbnail_id, 'large' );
							endif;
						?>
					</div>
				<?php endif; ?>
				<ul class="blog-post-info">
					<?php if(!is_single()) :?>
						<li class="posted_date">
							<a itemprop="url" href="<?php the_permalink(); ?>"><?php echo get_the_date(); ?></a>
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
					?>
					</div>
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