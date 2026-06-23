<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$class_to_filter = '';
$class_to_filter .= vc_shortcode_custom_css_class( $inline_css, ' ' ) . $this->getExtraClass( $class ) . $this->getCSSAnimation( $css_animation );
$all_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_to_filter, $this->settings['base'], $atts );


/*detect column*/
$col = '';
if($column == '2'){
    $col = 'col-md-6';
}elseif($column == '3'){
    $col = 'col-md-4';
}else{
    $col = 'col-md-3';
}

/*categories*/
$categories = get_terms( 'fw-portfolio-category' );

/*variable*/
$categories_arr = $id_arr = $arr = array();

/*custom background hover*/
$uid = '';
if($effect == 'yes' && !empty($color1) && !empty($color2)):
    $uid = uniqid('theme-case-key-');

    $bg[] = 'background: -webkit-linear-gradient(45deg, ' . $color1 . ' 0%, ' . $color2 . ' 100%)';

    echo '<style>';
    echo '.theme-cases.' . esc_attr($uid) . ' .case-text{' . implode( ';', $bg ) . ';}';
    echo '</style>';

endif;


echo '<div class="theme-cases flw ' . esc_attr($uid) . '">';
/*GET POSTS BY CATEGORIES======================================================*/
if($source == 'cats'):
    /*final categories*/
    $final_categories_arr = explode(', ', $cats_data);

    if( !empty( $source ) ) {
        foreach( $categories as $cat ) {
            if( in_array($cat->slug, $final_categories_arr) ) {
                $categories_arr[] = (object) array( 'name' => $cat->name, 'slug' => $cat->slug);
                $id_arr[] = $cat->term_id;
            }
        }
        $categories = $categories_arr;        
    }
    
    /*filter*/
    if($filter == 'yes'):
        echo '<div class="theme-cases-filter">';
            echo '<button class="is-checked" data-filter="*">ALL</button>';	
            foreach( $categories as $cat ):
                echo '<button data-filter=".' . esc_attr( $cat->slug ) . '">' . esc_attr( $cat->name ) . '</button>';
            endforeach;
        echo '</div>';
    endif;
    /*end filter*/

    /*grid*/ ?>
    <div class="theme-cases-grid row">
        <?php
            foreach($id_arr as $key){
                $all_post = get_posts(
                    array(
                        'post_type' => 'fw-portfolio',
                        'posts_per_page' => -1,
                        'tax_query' => array(
                            array(
                                'taxonomy' => 'fw-portfolio-category',
                                'field' => 'term_id',
                                'terms' => $key
                            )
                        )
                    )
                );
                foreach($all_post as $key_arr){
                    if(!in_array($key_arr->ID, $arr)){
                        array_push($arr, $key_arr->ID);
                    }
                }
            }
            
            foreach($arr as $key):
                $post = get_post($key);
                $pid = $post->ID;

                /*thumbnail id*/
                $thumbnail_id = get_post_thumbnail_id($pid);
                /*image size*/
                if($column == 2){
                    $thumbnail = fw_resize($thumbnail_id, 558, 470, false);
                }elseif($column == 3){
                    $thumbnail = fw_resize($thumbnail_id, 362, 305, false);
                }else{
                    $thumbnail = fw_resize($thumbnail_id, 264, 222, false);
                }
                /*terms*/
                $terms = get_the_terms($pid, 'fw-portfolio-category');
                $terms_class = '';
                if($terms){
                    foreach($terms as $key){
                        $terms_class .= ' ' . $key->slug;
                    }
                } 

        ?>
        <div class="theme-case-item <?php echo esc_attr($col . $terms_class); ?>">
            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php esc_attr('Case  Image', 'blockter'); ?>">
            <div class="case-text">
                <a class="case-title" href="<?php echo get_permalink($pid); ?>"><?php echo get_the_title($pid); ?></a>
                <span class="case-cats">
                    <?php
                        foreach($terms as $key):
                            echo '<a href="' . get_term_link( $key->slug, $key->taxonomy ) .' ">' . esc_attr($key->name) . '</a>';
                        endforeach;
                    ?>
                </span>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php
    /*end grid*/
    
/*GET POSTS BY POSTS======================================================*/
else:
    $post_slug = explode(', ', $posts_data);

    /*filter*/
    if($filter == 'yes'):
         echo '<div class="theme-cases-filter">';
            echo '<button data-filter="*" class="is-checked">ALL</button>';

            foreach ($categories as $key => $a_cat):
                $cat_id = $a_cat->term_id;
                $filters = array();
                foreach($post_slug as $key){
                    if( $post = get_page_by_path( $key, OBJECT, 'fw-portfolio' ) ){
                        $pid = $post->ID;
                    }
                    $taxs = get_the_terms( $pid, 'fw-portfolio-category' );
                    $array = '';
                    foreach($taxs as $key){
                        $array = $key->term_id;
                        if ( $array == $cat_id && !in_array($array, $filters) ):
                            array_push($filters, $array);
                            echo '<button data-filter=".'.esc_attr($key->slug).'">'.esc_attr($key->name).'</button>';
                        endif;
                    }
                }

            endforeach;

        echo '</div>';
    endif;
    /*end filter*/

    /*grid*/
    echo '<div class="theme-cases-grid row">';
    foreach($post_slug as $key){
        if( $post = get_page_by_path( $key, OBJECT, 'fw-portfolio' ) ){
            $pid = $post->ID;

            /*thumbnail id*/
            $thumbnail_id = get_post_thumbnail_id($pid);
            /*image size*/
            if($column == 2){
                $thumbnail = fw_resize($thumbnail_id, 558, 470, false);
            }elseif($column == 3){
                $thumbnail = fw_resize($thumbnail_id, 362, 305, false);
            }else{
                $thumbnail = fw_resize($thumbnail_id, 264, 222, false);
            }
            /*term*/
            $terms = get_the_terms($pid, 'fw-portfolio-category');
            $terms_class = '';
            if($terms){
                foreach($terms as $key){
                    $terms_class .= ' ' . $key->slug;
                }
            } ?>

            <div class="theme-case-item <?php echo esc_attr($col . $terms_class); ?>">
                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php esc_attr('Case Study Image', 'blockter'); ?>">
                <div class="case-text">
                    <a class="case-title" href="<?php echo get_permalink($pid); ?>"><?php echo get_the_title($pid); ?></a>
                    <span class="case-cats">
                        <?php
                            foreach($terms as $key):
                                echo '<a href="' . get_term_link( $key->slug, $key->taxonomy ) .' ">' . esc_attr($key->name) . '</a>';
                            endforeach;
                        ?>
                    </span>
                </div>
            </div>

        <?php }
    }
    echo '</div>';
    /*end grid*/
    
endif; ?>
</div>