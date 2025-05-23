<?php
/**
 * Template for search results.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<?php
/**
 * Header template.
 *
 * @package Avada
 * @subpackage Templates
 */

// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
?>
<!DOCTYPE html>
<html class="<?php avada_the_html_class(); ?>" <?php language_attributes(); ?>>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<?php Avada()->head->the_viewport(); ?>

	<?php wp_head(); ?>

	<?php
	/**
	 * The setting below is not sanitized.
	 * In order to be able to take advantage of this,
	 * a user would have to gain access to the database
	 * in which case this is the least of your worries.
	 */
	echo apply_filters( 'avada_space_head', Avada()->settings->get( 'space_head' ) ); // phpcs:ignore WordPress.Security.EscapeOutput
	?>
</head>

<?php
$object_id      = get_queried_object_id();
$c_page_id      = Avada()->fusion_library->get_page_id();
$wrapper_class  = 'fusion-wrapper';
$wrapper_class .= ( is_page_template( 'blank.php' ) ) ? ' wrapper_blank' : '';
?>
<body <?php body_class(); ?> <?php fusion_element_attributes( 'body' ); ?>>
	<?php do_action( 'avada_before_body_content' ); ?>
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'Avada' ); ?></a>

	<div id="boxed-wrapper">
		<div class="fusion-sides-frame"></div>
		<div id="wrapper" class="<?php echo esc_attr( $wrapper_class ); ?>">
			<div id="home" style="position:relative;top:-1px;"></div>
			<?php if ( has_action( 'avada_render_header' ) ) : ?>
				<?php do_action( 'avada_render_header' ); ?>
			<?php else : ?>

				<?php avada_header_template( 'below', ( is_archive() || Avada_Helper::bbp_is_topic_tag() ) && ! ( class_exists( 'WooCommerce' ) && is_shop() ) ); ?>
				<?php if ( 'left' === fusion_get_option( 'header_position' ) || 'right' === fusion_get_option( 'header_position' ) ) : ?>
					<?php avada_side_header(); ?>
				<?php endif; ?>

				<?php avada_sliders_container(); ?>

				<?php avada_header_template( 'above', ( is_archive() || Avada_Helper::bbp_is_topic_tag() ) && ! ( class_exists( 'WooCommerce' ) && is_shop() ) ); ?>

			<?php endif; ?>

			<div class="avada-page-titlebar-wrapper">
	<div class="fusion-page-title-bar education-header fusion-page-title-bar-breadcrumbs fusion-page-title-bar-center">
		<div class="fusion-page-title-row">
			<div class="fusion-page-title-wrapper">
				<div class="fusion-page-title-captions">

																							<h1 class="darkhone entry-title fusion-responsive-typography-calculated" data-fontsize="46" data-lineheight="normal" style="--fontSize:46;">Education</h1>
					<p>Use the options below to filter through education resources.</p>
	<div class="filters">	<?php echo do_shortcode( '[searchandfilter id="555"]' ); ?></div>

																
				</div>

				
			</div>
		</div>
	</div>
</div>
			<?php
			$row_css    = '';
			$main_class = '';

			if ( apply_filters( 'fusion_is_hundred_percent_template', false, $c_page_id ) ) {
				$row_css    = 'max-width:100%;';
				$main_class = 'width-100';
			}

			if ( fusion_get_option( 'content_bg_full' ) && 'no' !== fusion_get_option( 'content_bg_full' ) ) {
				$main_class .= ' full-bg';
			}
			do_action( 'avada_before_main_container' );
			?>
			<main id="main" class="clearfix <?php echo esc_attr( $main_class ); ?>">
				<div class="fusion-row" style="<?php echo esc_attr( $row_css ); ?>">

					<section id="content filterresults"  <?php Avada()->layout->add_class( 'content_class' ); ?> <?php Avada()->layout->add_style( 'content_style' ); ?>>
<?php if ( have_posts() ) : ?>

<div id="filterresultscontainer"> 
	<h2 style="color: #4a4a57; text-align: left; font-size: 30px; margin-top: 50px;">
		<?php
	//Get a multiple fields values by passing an array of field names
	//replace `1526` with the ID of your search form
	global $searchandfilter;
	$sf_current_query = $searchandfilter->get(555)->current_query();

	
	
	/*
	 * EXAMPLE 3 - without labels for fields themselves (the `str` value has been changed to include only values)
	 */
	$args = array(
		"str" 					=> '%2$s', 
		"delim" 				=> array(", ", " - "), 
		"field_delim"				=> ', ', 
		"show_all_if_empty"			=> false,
	);
	
	echo $sf_current_query->get_fields_html(
		array("_sft_category", "_sft_products", "_sft_care-settings"), 
		$args
	);

?></h2>
	

		<?php if ( 'bottom' === Avada()->settings->get( 'search_new_search_position' ) ) : ?>
			<?php get_template_part( 'templates/blog', 'layout' ); ?>
			<div class="fusion-clearfix"></div>
		<?php endif; ?>

		<?php if ( 'hidden' !== Avada()->settings->get( 'search_new_search_position' ) ) : ?>
	
		<?php endif; ?>

		<?php if ( 'top' === Avada()->settings->get( 'search_new_search_position' ) || 'hidden' === Avada()->settings->get( 'search_new_search_position' ) ) : ?>
			<?php get_template_part( 'templates/blog', 'layout' ); ?>
		<?php endif; ?>

	<?php else : ?>

		<div class="post-content">
			
		

			<div class="error-page">

									<div class="filters">	<?php echo do_shortcode( '[searchandfilter id="555"]' ); ?></div>

							<h3>There are no results for <?php
	//Get a multiple fields values by passing an array of field names
	//replace `1526` with the ID of your search form
	global $searchandfilter;
	$sf_current_query = $searchandfilter->get(555)->current_query();

	
	
	/*
	 * EXAMPLE 3 - without labels for fields themselves (the `str` value has been changed to include only values)
	 */
	$args = array(
		"str" 					=> '%2$s', 
		"delim" 				=> array(", ", " - "), 
		"field_delim"				=> ', ', 
		"show_all_if_empty"			=> false,
	);
	
	echo $sf_current_query->get_fields_html(
		array("_sft_category", "_sft_products", "_sft_care-settings"), 
		$args
	);

?> <br>
								Remove some of the filters and try again.</h3>

			</div>
		</div>
	<?php endif; ?>
	</section></div>
<?php do_action( 'avada_after_content' ); ?>
<?php get_footer(); ?>

