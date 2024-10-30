<?php
/*
Plugin Name: Local Classifieds
Plugin URL: http://cursuri-dezvoltare-personala.ro
Description: Classifieds system for your LOCAL needs, works with any template.
Version: 1.0
Author: Ghinea SM
Author URI: http://cursuri-dezvoltare-personala.ro
*/


/**
 * GLOBALS CONSTANTS
 *
 * @since       1.0
*/

if (!defined('LCGRW_BASE_FILE'))          define('LCGRW_BASE_FILE', __FILE__);
if (!defined('LCGRW_BASE_DIR'))           define('LCGRW_BASE_DIR', dirname(LCGRW_BASE_FILE));
if (!defined('LCGRW_PLUGIN_URL'))         define('LCGRW_PLUGIN_URL', plugin_dir_url(__FILE__));
if (!defined('LCGRW_LIST_PAGE'))          define('LCGRW_LIST_PAGE', 'Local Classifieds');
if (!defined('LCGRW_ADD_PAGE'))           define('LCGRW_ADD_PAGE', 'Post free ad');
if (!defined('LCGRW_PLUGIN_POST_TYPE'))   define('LCGRW_PLUGIN_POST_TYPE', 'classified');

/**
 * Plugin required scripts
 *
 * @since       1.0
 */

function LCGRW_thematic_enqueue_scripts() {
	wp_register_style('GRW-css2', plugins_url('/local-classifieds/includes/css/bootstrap-theme.min.css'));
	wp_enqueue_style('GRW-css2');
    wp_enqueue_script('GRW-script', plugins_url('includes/javascript/bootstrap.min.js', __FILE__ ), array('jquery'));
}

/**
 * Local Classifieds (custom types)
 *
 * @since       1.0
 */

function LCGRW_setup_post_types()
{

    // custom post type labels
    $labels = array(
        'name' => esc_html__('Classifieds List', 'LCGRW'),
        'singular_name' => esc_html__('Ads', 'LCGRW'),
        'add_new' => esc_html__('Post new ad', 'LCGRW'),
        'add_new_item' => esc_html__('Post new ad', 'LCGRW'),
        'edit_item' => esc_html__('Edit Ad', 'LCGRW'),
        'new_item' => esc_html__('Post new ad', 'LCGRW'),
        'view_item' => esc_html__('View Ad', 'LCGRW'),
        'search_items' => esc_html__('Search Ads', 'LCGRW'),
        'not_found' => esc_html__('No ads found', 'LCGRW'),
        'not_found_in_trash' => esc_html__('No ads found in trash', 'LCGRW'),
        'parent_item_colon' => ''
    );

    // supports
    $supports = array('title', 'editor');

    // custom ads type
    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_nav_menus' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => array('slug' => 'classifieds', 'with_front' => true),
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_position' => 25,
        'supports' => $supports,
        'menu_icon' => LCGRW_PLUGIN_URL . '/includes/images/class_icon.png', // you can set your own icon here
    );

    //register new post type
    register_post_type(LCGRW_PLUGIN_POST_TYPE, $args);
	flush_rewrite_rules();


    //register new taxonomy or property
    register_taxonomy('price', LCGRW_PLUGIN_POST_TYPE, array('show_ui' => true, 'show_in_menu' => false, 'hierarchical' => false, 'label' => 'Price', 'query_var' => true, 'rewrite' => true));
    register_taxonomy('location', LCGRW_PLUGIN_POST_TYPE, array('show_ui' => true, 'show_in_menu' => false, 'hierarchical' => false, 'label' => 'Location', 'query_var' => true, 'rewrite' => true));
    register_taxonomy('adcategory', LCGRW_PLUGIN_POST_TYPE, array('show_ui' => true, 'show_in_menu' => false, 'hierarchical' => false, 'label' => 'Category', 'query_var' => true, 'rewrite' => true));
    register_taxonomy('adsdomain', LCGRW_PLUGIN_POST_TYPE, array('show_ui' => true, 'show_in_menu' => false, 'hierarchical' => false, 'label' => 'Domain', 'query_var' => true, 'rewrite' => true));
    register_taxonomy('website', LCGRW_PLUGIN_POST_TYPE, array('show_ui' => true, 'show_in_menu' => false, 'hierarchical' => false, 'label' => 'Website (with http://)', 'query_var' => true, 'rewrite' => true));

	//add the new list ads page
    $the_page = get_page_by_title(LCGRW_LIST_PAGE);
    if (!$the_page) {
        $_p = array();
        $_p['post_title'] = LCGRW_LIST_PAGE;
        $_p['post_content'] = "[local-class-list]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1);
        $the_page_id = wp_insert_post($_p);
        add_option('LCGRW_list_page_id', $the_page_id, '', 'yes');
    }
    //add 'post free ad' page
    $the_page = get_page_by_title(LCGRW_ADD_PAGE);
    if (!$the_page) {
        $_p = array();
        $_p['post_title'] = LCGRW_ADD_PAGE;
        $_p['post_content'] = "[local-class-add]";
        $_p['post_status'] = 'publish';
        $_p['post_type'] = 'page';
        $_p['comment_status'] = 'closed';
        $_p['ping_status'] = 'closed';
        $_p['post_category'] = array(1);
        $the_page_id = wp_insert_post($_p);
        add_option('LCGRW_add_page_id', $the_page_id, '', 'yes');
    }
}

/**
 * This action it will process the ad form from front end
 *
 * @since       1.0
 */

function LCGRW_ajax_post_ad_action()
{
    header("Content-Type: application/json");

    $data = $_GET['ad'];

    $_p = array();
    $_p['post_title'] = ucfirst($data['title']);
    $_p['post_content'] = ucfirst($data['description']);
    $_p['post_author'] = 1;
    $_p['post_status'] = 'publish';
    $_p['post_type'] = LCGRW_PLUGIN_POST_TYPE;
    $_p['comment_status'] = 'closed';
    $_p['ping_status'] = 'closed';
    $_p['post_category'] = array(1);
    $_p['tax_input'] = array(
        'adcategory' => array(
	        sanitize_text_field($data['adcategory'])
        ),
        'price' => array(
            $data['price']
        ),
        'location' => array(
	        sanitize_text_field($data['location'])
        ),
        'adsdomain' => array(
	        sanitize_text_field($_SERVER['SERVER_NAME'])
        ),
        'website' => array(
	        sanitize_text_field($data['website'])
        )
    );

    //insert post
    $the_post_id = wp_insert_post($_p);

    //set taxonomy for this post
    wp_set_object_terms( $the_post_id,  $data['adcategory'], 'adcategory');
    wp_set_object_terms( $the_post_id,  $data['price'], 'price');
    wp_set_object_terms( $the_post_id,  $data['location'], 'location');
    wp_set_object_terms( $the_post_id,  $data['website'], 'website');
    wp_set_object_terms( $the_post_id,  $_SERVER['SERVER_NAME'], 'adsdomain');

    //response
    echo json_encode("response");
    die();
}

/**
 * Returns template file
 *
 * @since       1.0
 */

function LCGRW_template_chooser($template)
{
	//die("$template");

    // Post ID
    $post_id = get_the_ID();
    
    //show page name 
    //die(get_query_var('pagename'));

    // if is the custom list page
    if (is_page(LCGRW_LIST_PAGE)) {
        //do something before list page
        //die($template);
    }

    // if is the 'post free ad' page
    if (is_page(LCGRW_ADD_PAGE)) {
       //do something before add page
       //die($template);
    }


    // if it is a single post of classifieds type
    if (is_single() && (get_post_type($post_id) == LCGRW_PLUGIN_POST_TYPE)) {

        //TODO customize single display
    }

    //else return as normal
    return $template;

}

/**
 * Get the custom template if is set
 *
 * @since       1.0
 */

function LCGRW_get_template_hierarchy($template)
{

    // Get the template slug
    $template_slug = rtrim($template, '.php');
    $template = $template_slug . '.php';

    $file = LCGRW_BASE_DIR . '/includes/templates/' . $template;

    return apply_filters('LCGRW_template_' . $template, $file);
}

/**
 * Main uninstall function
 *
 * @since       1.0
 */

function LCGRW_uninstall()
{
    $the_page = get_page_by_title(LCGRW_LIST_PAGE);
    wp_delete_post($the_page->ID, true);

    $the_page = get_page_by_title(LCGRW_ADD_PAGE);
    wp_delete_post($the_page->ID, true);
}

/**
 *
 * @since       1.0
 */

function LCGRW_shortcode_list_handler( $atts, $content = null ) {
    ob_start();
    include "includes/templates/list.php";
    return str_replace(array("\n", "\r"), "", ob_get_clean());
}

/**
 *
 * @since       1.0
 */

function LCGRW_shortcode_add_handler( $atts, $content = null ) {
    ob_start();
    include "includes/templates/add.php";
    return str_replace(array("\n", "\r"), "", ob_get_clean());
}

/**
 *
 * @since       1.0
 */

function LCGRW_plugin_myown_content( $content ) {

    //post id
    $post_id = get_the_ID();



    //check if
    if (is_single() && (get_post_type($post_id) == LCGRW_PLUGIN_POST_TYPE)) {

        //get tax
        $tax_out = '';
        $tax =  get_the_taxonomies();
        $title =  get_the_title();

        //foreach tax
        foreach($tax as $k=>$v){

            if ($k == 'website') {
                $v = strip_tags($v);

                $url = '@(http)?(s)?(://)?(([a-zA-Z])([-\w]+\.)+([^\s\.]+[^\s]*)+[^,.\s])@';
                $v = preg_replace($url, '<a href="http$2://$4" target="_blank" title="'.$title.'">'.$title.'</a>', $v);

            }

            if ($k != 'adsdomain') {
                $tax_out .= "<div style=\"display:block;\">" . $v . "</div>";
            }
        }

        $content = preg_replace('/\z/', $tax_out, $content);
    }

    return $content;
}

/**
 * ADMIN SETTINGS
 *
 * @since       1.0
 */

function LCGRW_admin_settings_init( $content ) {

	register_setting('LCGRW',
		'LCGRW_options'
	);
	add_settings_section(
		'LCGRW_GCA_SECTION',"PAGINATION",
		'LCGRW_section_html_cb',
		'LCGRW_PAGE'
	);
	add_settings_field(
		'LCGRW_field_pagesize',
		__('Page Size', 'LCGRW'),
		'LCGRW_field_page_size_html_cb',
		'LCGRW_PAGE',
		'LCGRW_GCA_SECTION',
		[
			'label_for'         => 'LCGRW_field_pagesize'
		]
	);

}

/**
 * ADMIN MENU
 *
 * @since       1.0
 */

function LCGRW_admin_menu_init() {

	add_submenu_page(
		'edit.php?post_type=classified',
		'Local Classifieds - plugin settings',
		'Settings',
		'manage_options',
		'LCGRW_PAGE',
		'LCGRW_admin_options_page_html_cb'
	);

}

/**
 * ADMIN PAGE HTML
 *
 * @since       1.0
 */

function LCGRW_admin_options_page_html_cb() {

	?>
	<div class="wrap">
		<h1><?= esc_html(get_admin_page_title()); ?></h1>
		<hr>
		<form action="options.php" method="post">
			<?php
			// output fields for the registered setting "LCGRW"
			settings_fields('LCGRW');
			// output setting sections and their fields (sections are registered for "LCGRW_PAGE", each field is registered to a specific section)
			do_settings_sections('LCGRW_PAGE');
			// output save settings button
			submit_button('Save Settings');
			?>
		</form>
	</div>
	<?php

}

/**
 * ADMIN SECTION HTML
 *
 * section separator
 *
 * @since       1.0
 */

function LCGRW_section_html_cb($args) {
	?><hr><?php
}

/**
 * ADMIN FIELD HTML
 *
 * @since       1.0
 */

function LCGRW_field_page_size_html_cb($args) {
 	$options = get_option('LCGRW_options');
 	?>
	<input type="number" placeholder="20" value="<?php echo $options[$args['label_for']]?>" id="<?php echo esc_attr($args['label_for']); ?>" name="LCGRW_options[<?php echo esc_attr($args['label_for']); ?>]">
	<?php
}

/**
 * ASSIGN CALLBACKS
 *
 * @since       1.0
 */

//assign remove hooks
register_uninstall_hook(__FILE__, 'LCGRW_uninstall');
register_deactivation_hook(__FILE__, 'LCGRW_uninstall');

//assign filters for various things
add_filter('template_include', 'LCGRW_template_chooser');
add_filter('the_content','LCGRW_plugin_myown_content');

//assign add actions
add_action('init', 'LCGRW_setup_post_types');
add_action('wp_ajax_grwformpostad', 'LCGRW_ajax_post_ad_action');
add_action('wp_ajax_nopriv_grwformpostad', 'LCGRW_ajax_post_ad_action');
add_action('wp_enqueue_scripts', 'LCGRW_thematic_enqueue_scripts' );
add_action('admin_init','LCGRW_admin_settings_init');
add_action('admin_menu','LCGRW_admin_menu_init');

//shortcode
add_shortcode( 'local-class-list', 'LCGRW_shortcode_list_handler' );
add_shortcode( 'local-class-add', 'LCGRW_shortcode_add_handler' );

remove_action('wp_head', 'start_post_rel_link', 10, 0 );
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);




