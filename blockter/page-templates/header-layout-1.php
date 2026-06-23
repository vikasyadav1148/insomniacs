<?php
/*hide bread crumbs when install theme*/
$hide = '';
if(!function_exists('FW')){
    $hide = 'bread-hide';
}
?>
<header class="header-layout-1 <?php echo esc_attr($hide); ?>" itemscope itemtype="http://schema.org/WPHeader">
    <div class="theme-header flw">
        <div class="theme-menu-box">
            <?php blockter_header_edit_location('hd1');/*header edit location*/ ?>
            <div class="container">
                <div class="menu-flex-box">
                    <?php blockter_logo_image();/*logo*/ ?>
                    <div class="theme-wrap-primary-menu" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
                    <?php if(has_nav_menu('primary') && has_nav_menu('primary-menu-right')): ?>
                        <div class="primary-menu-left">
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'theme-primary-menu', 'container' => '' ) ); ?>
                        </div>
                        <div class="primary-menu-right">
                        <?php  wp_nav_menu( array( 'theme_location' => 'primary-menu-right', 'menu_class' => 'theme-primary-menu') ); ?>
                        </div>
                    <?php elseif(has_nav_menu('primary')): ?>
                    <div class="primary-menu-left">
                        <?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'theme-primary-menu', 'container' => '' ) ); ?>
                        </div>
                        <div class="primary-menu-right">
                        </div>
                    <?php elseif(has_nav_menu('primary-menu-right')): ?>
                        <div class="primary-menu-left"></div>
                        <div class="primary-menu-right">
                            <?php  wp_nav_menu( array( 'theme_location' => 'primary-menu-right', 'menu_class' => 'theme-primary-menu') ); ?>
                        </div>
                    <?php else: ?>
                    	<?php if ( is_user_logged_in() ): ?>
                    		<a class="add-menu-suggest" href="<?php echo esc_url( get_admin_url() . 'nav-menus.php' ); ?>"><?php esc_html_e( 'Add Menu', 'blockter' ); ?></a>
                    	<?php endif ?>

                    <?php endif; ?>
                </div>
            </div>
            <?php
                /*ajax movie search*/
                $has_ajax_search = (get_theme_mod('ajax_movie_search', false)) ? 'ajax-movie-search' : '';
                if($has_ajax_search == ''){
                    ?>
                    <?php echo blockter_top_search_form();
                }else{
                    ?>
                    <?php echo blockter_top_ajaxsearch_form();
                }
            ?>
            <!-- search form-->
            </div>
        </div>
        <?php get_template_part('page-templates/page', 'header');/* breadcrumbs */ ?>
    </div>

</header>
