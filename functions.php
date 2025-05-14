<?php

require_once(get_stylesheet_directory() . '/functions/hencove_migrator.php');


add_action('wp_head', function () {
	echo '<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />';
	echo '<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>';
}, 10, 1);





function theme_enqueue_styles()
{
	wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('avada-stylesheet'));

	// 
	// 
	wp_register_style(
		'child-styles',
		get_stylesheet_directory_uri() . '/_build/css/styles.css',
		array(),
		filemtime(get_stylesheet_directory() . '/_build/css/styles.css')
	);
	// 
	wp_enqueue_style('child-styles');

	wp_register_script(
		'child-scripts',
		get_stylesheet_directory_uri() . '/_build/js/scripts.js',
		array(),
		filemtime(get_stylesheet_directory() . '/_build/js/scripts.js'),
		true,
	);
	// 
	wp_enqueue_script('child-scripts');
}
add_action('wp_enqueue_scripts', 'theme_enqueue_styles');

function avada_lang_setup()
{
	$lang = get_stylesheet_directory() . '/languages';
	load_child_theme_textdomain('Avada', $lang);
}
add_action('after_setup_theme', 'avada_lang_setup');


// Register Custom Taxonomy
function custom_taxonomy()
{

	$labels = array(
		'name'                       => _x('Care Settings', 'Taxonomy General Name', 'text_domain'),
		'singular_name'              => _x('Care Setting', 'Taxonomy Singular Name', 'text_domain'),
		'menu_name'                  => __('Care Settings', 'text_domain'),
		'all_items'                  => __('All Care Settings', 'text_domain'),
		'parent_item'                => __('Parent Care Setting', 'text_domain'),
		'parent_item_colon'          => __('Parent Care Setting:', 'text_domain'),
		'new_item_name'              => __('New Care Setting', 'text_domain'),
		'add_new_item'               => __('Add New Care Setting', 'text_domain'),
		'edit_item'                  => __('Edit Care Setting', 'text_domain'),
		'update_item'                => __('Update Care Setting', 'text_domain'),
		'view_item'                  => __('View Care Setting', 'text_domain'),
		'separate_items_with_commas' => __('Separate Care Settings with commas', 'text_domain'),
		'add_or_remove_items'        => __('Add or remove items', 'text_domain'),
		'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
		'popular_items'              => __('Popular Care Settings', 'text_domain'),
		'search_items'               => __('Search Items', 'text_domain'),
		'not_found'                  => __('Not Found', 'text_domain'),
		'no_terms'                   => __('No items', 'text_domain'),
		'items_list'                 => __('Items list', 'text_domain'),
		'items_list_navigation'      => __('Items list navigation', 'text_domain'),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
	);
	register_taxonomy('care-settings', array('post', 'article'), $args);
}
// add_action( 'init', 'custom_taxonomy', 10 );

// Register Custom Taxonomy
function product_category()
{

	$labels = array(
		'name'                       => _x('Products', 'Taxonomy General Name', 'text_domain'),
		'singular_name'              => _x('Product', 'Taxonomy Singular Name', 'text_domain'),
		'menu_name'                  => __('Products', 'text_domain'),
		'all_items'                  => __('All Products', 'text_domain'),
		'parent_item'                => __('Parent Product', 'text_domain'),
		'parent_item_colon'          => __('Parent Product:', 'text_domain'),
		'new_item_name'              => __('New Product', 'text_domain'),
		'add_new_item'               => __('Add New Product', 'text_domain'),
		'edit_item'                  => __('Edit Product', 'text_domain'),
		'update_item'                => __('Update Product', 'text_domain'),
		'view_item'                  => __('View Product', 'text_domain'),
		'separate_items_with_commas' => __('Separate Products with commas', 'text_domain'),
		'add_or_remove_items'        => __('Add or remove items', 'text_domain'),
		'choose_from_most_used'      => __('Choose from the most used', 'text_domain'),
		'popular_items'              => __('Popular Products', 'text_domain'),
		'search_items'               => __('Search Items', 'text_domain'),
		'not_found'                  => __('Not Found', 'text_domain'),
		'no_terms'                   => __('No items', 'text_domain'),
		'items_list'                 => __('Items list', 'text_domain'),
		'items_list_navigation'      => __('Items list navigation', 'text_domain'),
	);
	$args   = array(
		'labels'            => $labels,
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => true,
	);
	register_taxonomy('products', array('post', 'article'), $args);
}
// add_action( 'init', 'product_category', 10 );


// Add tags to body class
add_filter('body_class', 'themeprefix_add_taxonomy_class');
// Add taxonomy terms name to body class
function themeprefix_add_taxonomy_class($classes)
{
	if (is_singular()) {
		global $post;
		$taxonomy_terms = get_the_terms($post->ID, 'products'); // change to your taxonomy
		if ($taxonomy_terms) {
			foreach ($taxonomy_terms as $taxonomy_term) {
				// var_dump($taxonomy_term);
				if (!is_array($taxonomy_term)) {
					$classes[] = 'tax_' . $taxonomy_term->slug;
				}
			}
		}
	}
	return $classes;
}

add_filter('ppwp_customize_password_form', 'customize_pwd_form', 10, 3);
function customize_pwd_form($element, $post_id, $wrong_password_message)
{
	$label           = 'pwbox-' . (empty($post_id) ? rand() : $post_id);
	$custom_elements = '<div>
        <div class="panel panel-default account-panel">
            <div class="panel-heading">
                <h3 class="panel-title">Enter password to access content.</h3>
            </div>
            <div class="panel-body">
                <div class="center-block text-center">
                    <div class="unlock-form">' . $wrong_password_message . '
                        <label class="pass-label" for="' . $label . '">' . __('') . ' </label>
                        <div>
                            <input name="post_password" id="' . $label . '" type="password" class="form-control" placeholder="Password" required autofocus />
                        </div>
		
                        <div>
                            <input type="submit" name="Submit" class=" btn-primary btn-block" value="' . esc_attr__('Unlock') . '" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>';
	return $custom_elements;
}


function wpr_add_preload()
{
	echo '<link rel="preload" href="/fileadmin/2020/11/Sentec_Background_Hero_2-2.png" as="image">';
}
add_action('wp_head', 'wpr_add_preload', 5);



/* Remove extra categories */
add_filter('the_category_list', function ($categories) {
	if (! empty($categories) && is_array($categories)) {
		foreach ($categories as $key => $cat) {
			if (! $cat->parent) {
				$ob[] = $categories[$key];
				return $ob;
			}
		}
	}

	return $categories;
}, 100);


/* Custom package English Print Order Form*/
function nicu_package_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/09/RF-012667-_-NICU-Brochure-VSign-DIGITAL.pdf" target="_blank">NICU Brochure (RF-012667-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/RF-012338-_-INTL-NICU-TCM-Clinical-Impact-Summary.pdf" target="_blank">NICU Clinical Impact Summary (RF-012338-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/11/Balancing-Brain-Lung-Protection-Whitepaper_RF-013873-_-.pdf" target="_blank">Whitepaper - Balancing Brain and Lung protection in the NICU (RF-013873-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/10/RF-013975-_-NonAdhesiveWrapFlyer-HALF-LETTER-DIGITAL-072423-1.pdf">Non Adhesive Wrap Flyer (RF-013975-_)</a>
    ';

	return $content;
}
add_shortcode('nicu_package', 'nicu_package_custom');

function picu_package_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2024/03/RF-014106-_-PICU-Brochure-EN-DIGITAL-013124.pdf" target="_blank">PICU Brochure (RF-014106-a)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/Clinical-Applications-in-Childrens-Hospital-Impact-Summary-A4-oUS-RF-013576-_-.pdf" target="_blank">Clinical Applications in Children’s Hospital – Clinical Impact Summary (RF-013576-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/NIV-in-the-PICU-Whitepaper-US-Letter_RF-013797-_-.pdf" target="_blank">Whitepaper - NIV in the PICU (RF-014073-_)</a>
    ';
	return $content;
}
add_shortcode('picu_package', 'picu_package_custom');

function sleep_package_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/RF-012668-a-Sleep-Brochure.pdf" target="_blank">SLEEP Brochure (RF-012668-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/11/RF-011745-a-INTL-Sleep-Lab-Clinical-Impact-Summary.pdf" target="_blank">SLEEP Clinical Impact Summary (RF-011745-a)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/CO2-Monitoring-in-Patients-with-V_Q-Mismatch-Whitepaper_081922-FLAT_RF-013000-_-.pdf" target="_blank">Whitepaper - V/Q Mismatch (RF-013000-_)</a>
    ';
	return $content;
}
add_shortcode('sleep_package', 'sleep_package_custom');

function new_customers_package_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2024/03/RF-012420-a-Implementation-Workbook-DRAFT.pdf" target="_blank">SDMS Implementation Planning Guide (RF-012420-a)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/HB-011657-_-Bedside-Tips-Flyer_UK.pdf" target="_blank">NICU Bedside Tips Sheet (HB-011657-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/12/Troubleshooting-Tips-Sheet-A5-oUS-DIGITAL-HBQ-184-V1.pdf" target="_blank">Troubleshooting Tips Sheet (HBQ-184-V1)</a>
    ';
	return $content;
}
add_shortcode('new_customers_package', 'new_customers_package_custom');

/* German */

function nicu_paket_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/RF-013464-_-NICU-Brochure-OxiVenT_DE.pdf" target="_blank">NICU Broschüre-DE (RF-013464-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/11/RF-0121.pdf" target="_blank">NICU Clinical Impact Summary-DE (RF-012570-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/10/RF-0131.pdf" target="_blank">Whitepaper - Balancing Brain and Lung protection in the NICU-DE (RF-013962-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/09/RF-014043-_-NonAdhesiveWrapFlyer-DE-A5-PRINT.pdf" target="_blank">Flyer zum Klettbandapplikator (RF-014043-_)</a>
    ';
	return $content;
}
add_shortcode('nicu_paket', 'nicu_paket_custom');

function picu_paket_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2024/03/RF-014113-_-PICU-Brochure-DE_DIGITAL-031324.pdf" target="_blank">PICU Broschüre-DE (RF-014113-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/10/RF-0131-1.pdf" target="_blank">Clinical Applications in Children’s Hospital – Clinical Impact Summary-DE (RF-013798-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2024/04/RF-014114-_-NIVinthePICU-Whitepaper-A4_DE_DIGITAL_040924.pdf" target="_blank">Whitepaper - NIV in the PICU-DE (RF-014114-_)</a>
    ';
	return $content;
}
add_shortcode('picu_paket', 'picu_paket_custom');

function sleep_paket_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/RF-013471-_-Sleep-Brochure_DE.pdf" target="_blank">SLEEP Broschüre-DE (RF-013471-_)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2024/03/RF-0121.pdf" target="_blank">SLEEP Clinical Impact Summary-DE (RF-012575-b)</a><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/10/RF-013467-a-CO2-Monitoring-in-Patients-with-V_Q-Mismatch-Whitepaper-A4-DE-DIGITAL-100523.pdf" target="_blank">Whitepaper - V/Q Mismatch-DE (RF-013467-_)</a>
    ';
	return $content;
}
add_shortcode('sleep_paket', 'sleep_paket_custom');

function neukunden_paket_custom()
{
	$content = '
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="URL_FUR_SDMS_GUIDE_DE" target="_blank">SDMS Implementation Planning Guide-DE (RF-014111-_)</a><br><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/07/HB-012375-a-Bedside-Tips-Sheet_DE.pdf" target="_blank">NICU Bedside Tips Sheet-DE (HB-012375-a)</a><br><br>
        <span style="color: #ff6600; font-size: 30px; margin-right: 5px;">•</span><a href="https://www.sentec.com/fileadmin/2023/12/HBQ-185-V1-Troubleshooting-Tips-Sheet-A5-DACH-DIGITAL-112823.pdf" target="_blank">Troubleshooting Tips Sheet-DE (HBQ-185-V1)</a>
    ';
	return $content;
}
add_shortcode('neukunden_paket', 'neukunden_paket_custom');

function add_gtm_code_body()
{
?>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5W7XDRF"
			height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->
<?php
}

add_action('wp_body_open', 'add_gtm_code_body');


function add_gtm_code()
{
?>
	<!-- Google Tag Manager -->
	<script>
		(function(w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-5W7XDRF');
	</script>
	<!-- End Google Tag Manager -->
<?php
}
add_action('wp_head', 'add_gtm_code');
