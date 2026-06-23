<?php
/**
 * Displaying the page template infor
 */

/*Customizer/Metabox variable*/
$c_page_header = get_theme_mod('c_page_header', '1');
$c_breadcrumbs = get_theme_mod('c_crumbs', '1');
$c_header_text = get_theme_mod('c_header_text', '');

/*page id*/
$pid = get_queried_object_id();
$p_page_header = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($pid, 'p_page_header') : '1';

/*custom post title*/
$post_title = (function_exists('fw_get_db_post_option')) ? fw_get_db_post_option($pid, 'spc_opt') : '';

/*Kirki customizer option*/
$c_header_bg = get_theme_mod( 'c_header_bg', 'bg_color' );
$c_header_bg_color = get_theme_mod( 'c_header_bg_color' , '#0b6070' );
$c_header_bg_image = get_theme_mod( 'c_header_bg_image' , false );
$img = wp_get_attachment_image_src($c_header_bg_image, 'full');
$c_header_text_align = get_theme_mod( 'c_header_text_align' , 'text-center' );
$c_blog_title = get_theme_mod('blog_title', $default = 'Blog List');
/*set default value*/
$final_page_header = $c_page_header;
$final_header_text = $c_header_text;
$final_header_text_color = '';

$final_bg_bread = '';

/*page title*/
$page_title = get_the_title();

/*blog header customizer*/
$b_header_title = get_theme_mod('b_header_title', 'Blog List');
$b_header_text = get_theme_mod('b_header_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur suscipit nulla ligula, nec tincid unt tortor pulvinar a. Proin nunc leo, imperdiet nec risus non.');

/*========can be delete on kirki 3.0=======*/
if($c_header_bg_image == true){
   $final_bg_bread = 'style="background-image:url(' . $img[0] . ');"';
}
/*========//can be delete on kirki 3.0=======*/
$gadget = '';
if(function_exists('FW')){
    if(is_page() || is_single()){

        /*variables*/
        if ( isset( $p_page_header['gadget'] ) ) {
            $gadget = $p_page_header['gadget'];
        }

        /*page header value*/
        if(isset($gadget) && $gadget != 'default'){
            $final_page_header = $gadget;
        }

        /*page title*/        
        if($gadget == '1' && $p_page_header['1']['page_header_title'] != ''){
            $page_title = $p_page_header['1']['page_header_title'];
        }
        
        /*header text*/
        if(isset($gadget) && $gadget == '1' && $p_page_header['1']['page_header_text'] != ''){
            $final_header_text = $p_page_header['1']['page_header_text'];
        }

        /*header text color*/
        if(isset($gadget) && $gadget == '1' && $p_page_header['1']['page_header_text_color'] != ''){
            $final_header_text_color = 'style="color: '.$p_page_header['1']['page_header_text_color'].'"';
        }

        /*Override value if enable custom page header*/
        if(isset($gadget) && $gadget == '1'){

            $p_breadcrumb_bg_select = $p_page_header['1']['page_header_bg'];

            if($p_breadcrumb_bg_select['gadget'] == 'color_bg'){
                $final_bg_bread = 'style="background:' . $p_breadcrumb_bg_select['color_bg']['color_bg_data'] . ';"';
            }else{
                if(!empty($p_breadcrumb_bg_select['img_bg']['img_bg_data'])):
                    $final_bg_bread = 'style="background-image:url(' . $p_breadcrumb_bg_select['img_bg']['img_bg_data']['url'] . ');"';
                endif;
            }
        }
    }
    /*custom title and header text on sing post*/
    if(is_single()){
        if(isset($post_title['gadget']) && $post_title['gadget'] == 'yes'){
            $final_header_text = $post_title['yes']['textarea_header'];
        }
    }
    if(is_home()){
        $final_header_text = $b_header_text;
    }
    if(class_exists( 'WooCommerce' ) && is_shop()){
        $final_header_text = get_theme_mod('shop_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur suscipit nulla ligula, nec tincid unt tortor pulvinar a. Proin nunc leo, imperdiet nec risus non.');
    }
    if(class_exists( 'WooCommerce' ) && is_product()){
        $final_header_text = get_theme_mod('shop_text', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur suscipit nulla ligula, nec tincid unt tortor pulvinar a. Proin nunc leo, imperdiet nec risus non.');
    }
}

$shop_title = get_theme_mod('shop_title', 'Shop');

/*not display on 404 page - always show breadcrumb area for single TV show, movie, blog post, and cast (actor) pages*/
if( (!is_404() && $final_page_header == '1') || is_page_template( 'page-templates/user-profile.php' ) || is_singular( 'ht_show' ) || is_singular( 'ht_movie' ) || is_singular( 'post' ) || is_tax( 'mv_actor' ) ): ?>
    <nav class="blockter-breadcrumb flw" <?php echo wp_kses_post($final_bg_bread); ?>>
        <div class="container">
            <div class="bread flw <?php echo esc_attr($c_header_text_align); ?>" <?php echo wp_kses_post($final_header_text_color); ?> itemscope itemtype="http://schema.org/WebPage">
                <?php /*page title*/ ?>
                <h1 class="page-title" itemprop="name">
                    <?php
                    if ( is_day() ) :
                        printf( esc_html__( 'Daily Archives: %s', 'blockter'), get_the_date() );
                    elseif ( is_month() ) :
                        printf( esc_html__( 'Monthly Archives: %s', 'blockter'), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'blockter') ) );
                    elseif (is_home()) :
                        echo esc_html($b_header_title);
                    elseif(is_author()):
                        $curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
                        echo esc_html($curauth->display_name);
                    elseif ( is_year() ) :
                        printf( esc_html__( 'Yearly Archives: %s', 'blockter'), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'blockter') ) );
                    /*for shop page*/
                    elseif(class_exists( 'WooCommerce' ) && is_shop()):
                        // echo esc_html($shop_title);
                         esc_html_e('Shop', 'blockter');
                     /*for shop detail*/
                    elseif(class_exists( 'WooCommerce' ) && is_product()) :
                        esc_html_e('Shop detail', 'blockter');
                    /*event single page*/
                    elseif(is_singular($post_types = 'fw-event')) :
                        esc_html_e('Event ', 'blockter');
                    /*service single page*/
                    elseif(is_singular($post_types = 'ht_movie')) :
                        esc_html_e('Movie Single', 'blockter');
                        // echo the_title();
                    elseif(is_page()) :
                        echo esc_html($page_title);
                    elseif( is_tax() ) :
                        global $wp_query;
                        $term = $wp_query->get_queried_object();
                        $title = $term->name;
                        echo esc_html($title);
                    elseif( is_search() ):
                        esc_html_e('Search results', 'blockter');
                    /*single page*/
                    elseif(is_single()) :
                        if(isset($post_title['gadget']) && $post_title['gadget'] == 'yes'){
                            echo esc_html($post_title['yes']['spc_title']);
                        }elseif($gadget == '1' && $p_page_header['1']['page_header_title'] != ''){
                             echo esc_html($page_title);
                        }else{
                            // echo get_the_title();
                            echo esc_html('Blog Detail');
                        }
                    /*showing the category title*/
                    elseif (is_category()):
                        esc_html_e('Category : ', 'blockter');
                        echo single_term_title();
                    /*end of showing category title*/
                    elseif(is_tag()) :
                        esc_html_e('Tags', 'blockter');

                    elseif( is_archive() ) :
                        $post_type = get_query_var( 'post_type' );
                        if ( is_array( $post_type ) ) {
                            $post_type = reset( $post_type );
                        }
                        $post_type_obj = get_post_type_object( $post_type ? $post_type : 'post' );
                        $type_label    = $post_type_obj ? $post_type_obj->labels->name : esc_html__( 'Archives', 'blockter' );

                        // Normalize custom post type labels for movie/show archives
                        if ( $post_type === 'ht_movie' ) {
                            $type_label = 'Movies';
                        } elseif ( $post_type === 'ht_show' ) {
                            $type_label = 'Series';
                        }

                        $paged = max( 1, (int) get_query_var( 'paged' ) );
                        echo esc_html( $type_label . ' - Page ' . $paged . ' | Insomniacs' );
                    else :
                        esc_html_e( '404 Page ', 'blockter' );
                    endif;
                    ?>
                </h1>
                <?php /*breadcrumbs*/ ?>
                <?php
                    if (function_exists('fw_ext_breadcrumbs')) {
                        //if($c_breadcrumbs == 1){
                          fw_ext_breadcrumbs();
                        //}
                    }
                ?>
                <?php /*header text*/ ?>
                <?php
                    if($final_header_text != ''):
                        echo '<span class="theme-header-text" itemprop="description">';
                        echo wp_kses($final_header_text, array());
                        echo '</span>';
                    endif;
                ?>
                <?php if ( is_tax( array( 'mv_collection', 'mv_genre', 'networks' ) ) ) :
                    $queried_term = get_queried_object();
                    if ( $queried_term && ! empty( $queried_term->description ) ) : ?>
                        <p class="collection-description-text" itemprop="description"><?php echo wp_kses_post( $queried_term->description ); ?></p>
                    <?php endif;
                endif; ?>
                <?php if ( is_page_template( 'page-templates/page-collections.php' ) ) : ?>
                    <p class="collection-description-text" itemprop="description"><?php esc_html_e( 'Browse all movie collections in one place, from blockbuster franchises to iconic film series. Explore complete collections, discover movies in order, and find where to watch each title.', 'blockter' ); ?></p>
                <?php endif; ?>
                <?php if ( is_page_template( 'page-templates/page-networks.php' ) ) : ?>
                    <p class="collection-description-text" itemprop="description"><?php esc_html_e( 'Browse movies and TV series by streaming network, including Netflix, Disney+, Prime Video and more. Discover what\'s available to watch, from trending titles to new releases.', 'blockter' ); ?></p>
                <?php endif; ?>
                <?php if ( is_page_template( 'page-templates/page-genres.php' ) ) : ?>
                    <p class="collection-description-text" itemprop="description"><?php esc_html_e( 'Explore movies and TV shows by genre, from action and horror to comedy, drama, and more. Discover top titles, trending picks, and where to watch your favourite genres.', 'blockter' ); ?></p>
                <?php endif; ?>
                <?php if ( is_page_template( 'page-templates/page-trends.php' ) ) : ?>
                    <p class="collection-description-text" itemprop="description"><?php esc_html_e( 'Explore trending themes and popular story ideas across movies and TV—from time travel and heists to dystopias and holiday favourites. Follow the keywords audiences love, uncover curated picks, and find your next binge-worthy title.', 'blockter' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        <?php blockter_bread_edit_location('bread');/*header edit location*/ ?>
    </nav>
<?php endif;
