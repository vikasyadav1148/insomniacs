<?php
/**
 * Template Name: Coming Soon Template
 */

get_header('coming-soon'); ?>
	<div class="coming-soon-page flw">
		<div class="container">
			<?php
				while ( have_posts() ) : the_post();
					get_template_part('content', 'page');
				endwhile;
			?>
		</div>
	</div>	
<?php get_footer('coming-soon'); ?>