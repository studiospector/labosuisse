<?php
/**
 * Email Styles
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-styles.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 4.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load colors.
$bg        = get_option( 'woocommerce_email_background_color' );
$body      = get_option( 'woocommerce_email_body_background_color' );
$base      = get_option( 'woocommerce_email_base_color' );
$base_text = wc_light_or_dark( $base, '#202020', '#ffffff' );
$text      = get_option( 'woocommerce_email_text_color' );

// Pick a contrasting color for links.
$link_color = wc_hex_is_light( $base ) ? $base : $base_text;

if ( wc_hex_is_light( $body ) ) {
	$link_color = wc_hex_is_light( $base ) ? $base_text : $base;
}

$bg_darker_10    = wc_hex_darker( $bg, 10 );
$body_darker_10  = wc_hex_darker( $body, 10 );
$base_lighter_20 = wc_hex_lighter( $base, 20 );
$base_lighter_40 = wc_hex_lighter( $base, 40 );
$text_lighter_20 = wc_hex_lighter( $text, 20 );
$text_lighter_40 = wc_hex_lighter( $text, 40 );

// !important; is a gmail hack to prevent styles being stripped if it doesn't like something.
// body{padding: 0;} ensures proper scale/positioning of the email in the iOS native email app.
?>
body {
	padding: 0;
}

#wrapper {
	background-color: #E0E0E0;
	margin: 0;
	padding: 70px 0;
	-webkit-text-size-adjust: none !important;
	width: 100%;
}

#template_container,
#template_footer {
	border: 1px solid <?php echo esc_attr( $bg_darker_10 ); ?>;
	border-radius: 3px;
	border-bottom: 0;
}

#template_container {
	box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1) !important;
	background-color: #ffffff;
	border-bottom: 0;
	border-radius: 3px 3px 0 0;
}

#template_footer {
	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1) !important;
	border-top: 0;
	border-radius: 0 0 3px 3px;
}

#template_header {
	background-color: #ffffff;
	border-radius: 3px 3px 0 0 !important;
	color: #000000;
	border-bottom: 0;
	font-weight: bold;
	line-height: 100%;
	vertical-align: middle;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

#template_header h1 a {
	color: #000000;
	background-color: inherit;
}

#template_header_image img {
	margin-left: 0;
	margin-right: 0;
}

#body_content {
	background-color: <?php echo esc_attr( $body ); ?>;
}

#body_content table td {
	padding: 48px 48px 32px;
}

#body_content table td td {
	padding: 12px;
}

#body_content table td th {
	padding: 12px;
}

#body_content td ul.wc-item-meta {
	font-size: small;
	margin: 1em 0 0;
	padding: 0;
	list-style: none;
}

#body_content td ul.wc-item-meta li {
	margin: 0.5em 0 0;
	padding: 0;
}

#body_content td ul.wc-item-meta li p {
	margin: 0;
}

#body_content p {
	margin: 0 0 16px;
}

#body_content_inner {
	color: #000000;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 14px;
	line-height: 150%;
	text-align: <?php echo is_rtl() ? 'right' : 'left'; ?>;
}

.td {
	color: #000000;
	/* border: 1px solid <?php echo esc_attr( $body_darker_10 ); ?>; */
	vertical-align: middle;
}

.address {
	padding: 12px;
	color: #000000;
	border: 1px solid <?php echo esc_attr( $body_darker_10 ); ?>;
}

.text {
	color: <?php echo esc_attr( $text ); ?>;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
}

.link {
	color: <?php echo esc_attr( $link_color ); ?>;
}

#header_wrapper {
}

h1 {
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-weight: 500;
	font-size: 20px;
	line-height: 28px;
	letter-spacing: 0.02em;
	text-transform: uppercase;
	margin: 0;
}

h2 {
	color: #000000;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 18px;
	font-weight: bold;
	line-height: 130%;
	margin: 0 0 18px;
}

h3 {
	color: #000000;
	display: block;
	font-family: "Helvetica Neue", Helvetica, Roboto, Arial, sans-serif;
	font-size: 16px;
	font-weight: bold;
	line-height: 130%;
	margin: 16px 0 8px;
}

a {
	color: #b52a2d;
	font-weight: normal;
	text-decoration: none;
}



.lb-header .lb-header__logos td {
	padding: 32px 48px;
}

.lb-header .lb-header__logos img,
.lb-footer .lb-footer__logo img {
	width: auto;
	height: 32px;
}

.lb-header .lb-header__logos .lb-header__logos__logo {
	text-align: right;
}

.lb-header .lb-header__banner {
	width: 100%;
	position: relative;
	padding: 50px 0;
	background-size: cover;
}

.lb-header .lb-header__banner h1 {
	width: 60%;
	margin: 0 auto;
	text-align: center;
	padding: 30px;
	background-color: #ffffff;
}



.lb-footer {
	background-color: #F5F5F5;
}

.lb-footer .lb-footer__logo {
	padding: 30px 0;
}

.lb-footer .lb-footer__logo img {
	display: block;
	margin: 0 auto;
}

.lb-footer .lb-footer__socials {
	padding-top: 0;
	padding-bottom: 30px;
}

.lb-footer .lb-footer__socials ul {
	display: block;
	margin: 0;
	padding: 0;
	list-style: none;
	text-align: center;
}

.lb-footer .lb-footer__socials ul li {
	display: inline-block;
	margin: 0 12px;
	vertical-align: middle;
}



#body_content #body_content_inner > h2 {
	text-align: right;
	margin-top: 50px;
	font-size: 12px;
	font-weight: 700;
	line-height: 18px;
	letter-spacing: 0.02em;
	text-transform: uppercase;
}

#body_content #body_content_inner > h2 a {
	width: 50%;
	float: left;
	text-align: left;
	color: #000000;
	font-weight: 600;
	font-size: 16px;
}

#body_content #body_content_inner .lb-order-details {
	border-collapse: collapse;
	font-size: 14px;
	font-weight: 400;
}

#body_content #body_content_inner .lb-order-details thead tr {
	border-top: 1px solid #E0E0E0;
	border-bottom: 1px solid #E0E0E0;
}

#body_content #body_content_inner .lb-order-details tbody tr.order_item {
	border-bottom: 1px solid #E0E0E0;
}

#body_content #body_content_inner .lb-order-details tfoot tr:last-child {
	border-top: 1px solid #E0E0E0;
}

#body_content #body_content_inner .lb-order-details tfoot th,
#body_content #body_content_inner .lb-order-details tbody tr.order_item .wc-item-meta li {
	font-weight: 400;
}

#body_content #body_content_inner .lb-order-details thead th,
#body_content #body_content_inner .lb-order-details tbody tr.order_item .wc-item-meta strong {
	font-weight: 300;
}

#body_content #body_content_inner #addresses h2 {
	font-weight: 700;
	font-size: 16px;
	line-height: 18px;
	letter-spacing: 0.02em;
	text-transform: uppercase;
}

#body_content #body_content_inner #addresses address {
	padding: 24px 15px;
	border: 1px solid #E0E0E0;
	font-size: 14px;
	font-weight: 300;
    font-style: normal;
	line-height: 21px;
}

#body_content #body_content_inner #addresses tr:last-child h2 {
	margin-top: 40px;
}
<?php
