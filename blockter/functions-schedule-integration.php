<?php
/**
 * Insomniacs Schedule Configuration Admin Page
 */

// ==========================================
// ADMIN MENU & SETTINGS BACKEND FOR SCHEDULE
// ==========================================
if ( is_admin() ) {
    add_action( 'admin_menu', 'insom_sched_add_admin_menu' );
    add_action( 'admin_init', 'insom_sched_settings_init' );
    add_action( 'admin_enqueue_scripts', function($hook) {
        if ( 'toplevel_page_insomniacs-schedule-settings' === $hook ) {
            wp_enqueue_media();
        }
    });
}

if ( ! function_exists( 'insom_sched_add_admin_menu' ) ) {
    function insom_sched_add_admin_menu() {
        add_menu_page(
            'Insomniacs Schedule',
            'Insomniacs Schedule',
            'manage_options',
            'insomniacs-schedule-settings',
            'insom_sched_settings_layout',
            'dashicons-calendar-alt',
            60
        );
    }
}

if ( ! function_exists( 'insom_sched_settings_init' ) ) {
    function insom_sched_settings_init() {
        register_setting( 'insom_sched_settings_group', 'insom_sched_post_types' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_title_1' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_title_2' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_description' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_accent_color' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_bg_color' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_fallback_bg' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_query_orderby' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_query_order' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_hide_past' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_excluded_posts' );
        register_setting( 'insom_sched_settings_group', 'insom_sched_pinned_post' );
    }
}

if ( ! function_exists( 'insom_sched_settings_layout' ) ) {
    function insom_sched_settings_layout() {
        $post_types = get_option('insom_sched_post_types', 'ht_movie,ht_show,ht_tv_show');
        $title_1 = get_option('insom_sched_title_1', 'MOVIES & TV');
        $title_2 = get_option('insom_sched_title_2', 'SCHEDULE');
        $description = get_option('insom_sched_description', 'Interactive cinematic scheduler. Sync schedules directly to your personal planners and track upcoming releases.');
        $accent_color = get_option('insom_sched_accent_color', '#ef4444');
        $bg_color = get_option('insom_sched_bg_color', '#040406');
        $fallback_bg = get_option('insom_sched_fallback_bg', '');
        $orderby = get_option('insom_sched_query_orderby', 'date');
        $order = get_option('insom_sched_query_order', 'ASC');

        $hide_past = get_option('insom_sched_hide_past', 'yes_today');
        $excluded_posts = get_option('insom_sched_excluded_posts', '');
        if (is_array($excluded_posts)) {
            $excluded_ids = $excluded_posts;
        } else {
            $excluded_ids = !empty($excluded_posts) ? explode(',', $excluded_posts) : array();
        }
        $excluded_ids = array_map('trim', $excluded_ids);
        $pinned_post = get_option('insom_sched_pinned_post', '');

        if ( isset($_GET['settings-updated']) && $_GET['settings-updated'] ) {
            echo '<div class="notice notice-success is-dismissible"><p><strong>Insomniacs Schedule Configuration Synchronized Safely.</strong></p></div>';
        }
        ?>
        <div class="wrap" style="background: #0d0e12; color: #f1f1f1; padding: 25px; border-radius: 12px; max-width: 950px; margin-top: 20px; font-family: 'Segoe UI', system-ui, sans-serif; box-shadow: 0 10px 30px rgba(0,0,0,0.35);">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #2d303b; padding-bottom: 20px; margin-bottom: 25px;">
                <div>
                    <h1 style="color: #fff; font-size: 28px; font-weight: 800; margin: 0; text-transform: uppercase; letter-spacing: -1px; text-shadow: 0 0 10px rgba(239,68,68,0.2);">⚡ Insomniacs Schedule Deck</h1>
                    <p style="color: #8b92a6; margin: 5px 0 0 0; font-size: 13px;">Manage colors, query parameters, landing page titles, and fallback artwork graphics for the Release Schedule Page Template.</p>
                </div>
                <div style="background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3); padding: 8px 16px; border-radius: 30px; font-size: 11px; font-weight: 700; color: #ef4444; letter-spacing: 1px; text-transform: uppercase;">
                    Scheduler Core Active
                </div>
            </div>

            <form method="post" action="options.php" style="background: #15171e; padding: 25px; border-radius: 10px; border: 1px solid #232731;">
                <?php settings_fields( 'insom_sched_settings_group' ); ?>
                <?php do_settings_sections( 'insom_sched_settings_group' ); ?>

                <!-- SECTION 1: TITLE & DETAILS -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 0 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">📝 Section 1: Page Title & Custom Copy</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Page Title Line 1</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Main header line.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_sched_title_1" value="<?php echo esc_attr($title_1); ?>" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Page Title Line 2</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Highlighted header line (Red Glow).</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_sched_title_2" value="<?php echo esc_attr($title_2); ?>" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Page Subtitle / Description</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Explanatory text under titles.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <textarea name="insom_sched_description" rows="3" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 10px; border-radius: 6px; font-size: 13px; resize: vertical;" required><?php echo esc_textarea($description); ?></textarea>
                        </td>
                    </tr>
                </table>

                <!-- SECTION 2: GLOBAL COLORS & FALLBACK IMAGES -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">🎨 Section 2: Global Styling & Fallback Artwork</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Primary Accent Theme Color</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">The primary neon brand color.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="color" name="insom_sched_accent_color" value="<?php echo esc_attr($accent_color); ?>" style="width: 60px; height: 35px; border: none; background: transparent; cursor: pointer; vertical-align: middle;" />
                            <code style="background: #0d0e12; color: #ef4444; padding: 6px 10px; border-radius: 4px; margin-left: 10px; font-size: 12px; border: 1px solid #232731; vertical-align: middle;"><?php echo esc_html($accent_color); ?></code>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">App Background Color</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Default page background color hex.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="color" name="insom_sched_bg_color" value="<?php echo esc_attr($bg_color); ?>" style="width: 60px; height: 35px; border: none; background: transparent; cursor: pointer; vertical-align: middle;" />
                            <code style="background: #0d0e12; color: #ffffff; padding: 6px 10px; border-radius: 4px; margin-left: 10px; font-size: 12px; border: 1px solid #232731; vertical-align: middle;"><?php echo esc_html($bg_color); ?></code>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Fallback Poster Artwork Image</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Used if a movie or show does not specify a featured image thumbnail.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <div style="display: flex; gap: 8px; width: 100%; max-width: 500px; align-items: center;">
                                <input type="text" id="insom_sched_fallback_bg" name="insom_sched_fallback_bg" value="<?php echo esc_url($fallback_bg); ?>" style="flex: 1; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 12px;" />
                                <button type="button" class="insom-sched-media-upload-trigger button button-secondary" data-target="insom_sched_fallback_bg" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.15) !important; color: #ffffff !important; padding: 4px 14px; border-radius: 6px; height: 35px; line-height: 25px;">
                                    Library...
                                </button>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- SECTION 3: CORE DATA & QUERY CODES -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">💾 Section 3: Database Query & Custom Sorting</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Target WordPress Post Types</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Comma-separated listing of custom post slugs (e.g. <code>ht_movie,ht_show,ht_tv_show</code>).</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <input type="text" name="insom_sched_post_types" value="<?php echo esc_attr($post_types); ?>" style="width: 100%; max-width: 500px; background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px;" required />
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Database Query Order (Chronology)</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Ascending or Descending calendar listings feed.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_query_order" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 180px;">
                                <option value="ASC" <?php selected($order, 'ASC'); ?>>Ascending (ASC)</option>
                                <option value="DESC" <?php selected($order, 'DESC'); ?>>Descending (DESC)</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Query Sort Parameter (Order By)</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Variable determining sorting key query array.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_query_orderby" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 180px;">
                                <option value="date" <?php selected($orderby, 'date'); ?>>Published Date</option>
                                <option value="title" <?php selected($orderby, 'title'); ?>>Alphabetical Title</option>
                                <option value="ID" <?php selected($orderby, 'ID'); ?>>Specific ID</option>
                                <option value="menu_order" <?php selected($orderby, 'menu_order'); ?>>WordPress Menu Order</option>
                                <option value="rand" <?php selected($orderby, 'rand'); ?>>Shuffled / Random (rand)</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <!-- SECTION 4: RELEASES FILTER, EXCLUSIONS & FEATURED PIN -->
                <h3 style="color: #ef4444; border-bottom: 1px solid rgba(239,68,68,0.15); padding-bottom: 8px; margin: 30px 0 15px 0; font-size: 16px; text-transform: uppercase; letter-spacing: 1px;">📌 Section 4: Releases Filtering, Manual Hiding & Countdown Pin</h3>
                <table class="form-table" style="width: 100%; border-collapse: separate; border-spacing: 0 12px; margin-bottom: 30px;">
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Auto-Hide Released Items</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Choose when to automatically remove items whose countdown hits zero/date is passed.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_hide_past" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 100%; max-width: 500px;">
                                <option value="yes_today" <?php selected($hide_past, 'yes_today'); ?>>Hide on Release Day & Past (as soon as countdown hits 0 / date <= Today)</option>
                                <option value="yes_past" <?php selected($hide_past, 'yes_past'); ?>>Hide after Release Day has passed (date < Today)</option>
                                <option value="no" <?php selected($hide_past, 'no'); ?>>Show All (Keep displaying past releases on the list)</option>
                            </select>
                        </td>
                    </tr>
                    
                    <?php
                    // Query active DB movies/TV shows to showcase for Pinning & Manual Exclusion
                    $post_types_arr = array_map('trim', explode(',', $post_types));
                    $all_db_posts_list = get_posts(array(
                        'post_type'      => $post_types_arr,
                        'posts_per_page' => -1,
                        'post_status'    => 'publish',
                        'orderby'        => 'title',
                        'order'          => 'ASC'
                    ));

                    $display_options_posts = array();
                    if (!empty($all_db_posts_list)) {
                        foreach ($all_db_posts_list as $p_item) {
                            $display_options_posts[] = array('id' => (string)$p_item->ID, 'title' => $p_item->post_title . ' [WP Database]');
                        }
                    } else {
                        // Showcase dummy fallback options so page is instantly testable and manageable for Admin
                        $display_options_posts[] = array('id' => 'toy-story-5', 'title' => 'TOY STORY 5 [Mock Showcase]');
                        $display_options_posts[] = array('id' => 'house-of-the-dragon', 'title' => 'HOUSE OF THE DRAGON [Mock Showcase]');
                        $display_options_posts[] = array('id' => 'the-odyssey', 'title' => 'ODYSSEY [Mock Showcase]');
                    }
                    ?>
                    
                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Featured / PIN Item</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Choose a specific movie or series to feature permanently as the default active countdown pinned item.</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <select name="insom_sched_pinned_post" style="background: #0d0e12; border: 1px solid #2d303b; color: #fff; padding: 8px 12px; border-radius: 6px; font-size: 13px; width: 100%; max-width: 500px;">
                                <option value="">None - Automatic (First Chronological Upcoming Release)</option>
                                <?php foreach ($display_options_posts as $opt_p): ?>
                                    <option value="<?php echo esc_attr($opt_p['id']); ?>" <?php selected($pinned_post, $opt_p['id']); ?>>
                                        <?php echo esc_html($opt_p['title']); ?> (ID: <?php echo esc_html($opt_p['id']); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th style="width: 250px; text-align: left; vertical-align: middle; padding: 5px 0;">
                            <label style="font-weight: 700; color: #fff; font-size: 13px;">Manually Exclude / Hide Items</label>
                            <p style="font-size: 11px; color: #8b92a6; margin: 4px 0 0 0; font-weight: normal;">Select which specific releases to manually hide from the schedule page (regardless of their date).</p>
                        </th>
                        <td style="padding: 5px 0;">
                            <!-- Hidden default value to reset array options when all checkboxes are deselected -->
                            <input type="hidden" name="insom_sched_excluded_posts" value="" />
                            <div style="background: #0d0e12; border: 1px solid #2d303b; border-radius: 6px; max-height: 200px; overflow-y: auto; padding: 12px; max-width: 500px;">
                                <?php foreach ($display_options_posts as $opt_p): ?>
                                    <label style="display: flex; align-items: center; margin-bottom: 8px; color: #fff; font-size: 12px; cursor: pointer; user-select: none;">
                                        <input type="checkbox" name="insom_sched_excluded_posts[]" value="<?php echo esc_attr($opt_p['id']); ?>" <?php checked(in_array((string)$opt_p['id'], $excluded_ids)); ?> style="margin-right: 8px; cursor: pointer;" />
                                        <span><?php echo esc_html($opt_p['title']); ?> (ID: <?php echo esc_html($opt_p['id']); ?>)</span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 30px; border-top: 1px solid #2d303b; padding-top: 20px; text-align: right;">
                    <?php submit_button( 'Save Schedule Configuration', 'primary', 'submit', false, array( 'style' => 'background: #ef4444; color: #fff; border: none; font-weight: 800; text-transform: uppercase; padding: 12px 30px; font-size: 13px; border-radius: 6px; cursor: pointer; text-shadow: none; box-shadow: 0 4px 15px rgba(239, 68, 68, 0.25);' ) ); ?>
                </div>
            </form>
        </div>

        <script>
        jQuery(document).ready(function($){
            $('.insom-sched-media-upload-trigger').click(function(e) {
                e.preventDefault();
                var targetId = $(this).data('target');
                var inputField = $('#' + targetId);
                
                var custom_uploader = wp.media({
                    title: 'Select Fallback Background Artwork',
                    button: {
                        text: 'Use Selected Artwork'
                    },
                    multiple: false
                })
                .on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    inputField.val(attachment.url);
                })
                .open();
            });
        });
        </script>
        <?php
    }
}
