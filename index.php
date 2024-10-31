<?php

/**

 * @package OA Open Graph for FB

 */

/*

Plugin Name: OA Open Graph for FB

Plugin URI: http://oalves.com/facebookopengraph.php

Description: It solves the problem of many templates that do not have open graph from Facebook. Now you can set content to be displayed in sharing on Facebook.

Version: 1.0.2

Author: OAlves

Author URI: http://oalves.com/

Text Domain: oalves

*/



defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );



if ( ! class_exists( 'oa_open_graph' ) ) {

	class oa_open_graph

	{

		public function __construct()

		{

			global $wpdb;



			$charset_collate = $wpdb->get_charset_collate();



			$create_og = "CREATE TABLE ".$wpdb->prefix."oa_og (

			  active int(11) NOT NULL,

			  defaulttitle text NOT NULL,

			  defaultsitename text NOT NULL,

			  defaultcontent text NOT NULL,

			  defaulttype text NOT NULL,

			  defaulturl text NOT NULL,

			  defaultimage text NOT NULL,

			  defaultappid text NOT NULL

			) $charset_collate;";

			

			$if_exist = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."oa_og");

			if (!$if_exist) {

				$wpdb->insert( 

					$wpdb->prefix.'oa_og', 

					array( 

						'active' => '0', 

						'defaulttitle' => get_bloginfo('description'), 

						'defaultsitename' => get_bloginfo('name'), 

						'defaultcontent' => '', 

						'defaulttype' => 'blog', 

						'defaulturl' => network_site_url('/'), 

						'defaultimage' => '',

						'defaultappid' => ''

					) 

				);

			}

			$og = $wpdb->get_row("SELECT * from ".$wpdb->prefix."oa_og");

			

			add_action('admin_menu', 'oa_open_graph_create_menu');



			function oa_open_graph_create_menu() {

				add_options_page('OA Open Graph', 'OA Open Graph', 'administrator', __FILE__, 'oa_open_graph_settings_page' , 'dashicons-desktop' );

			}

			

			function oa_open_graph_settings_page() {

				$local = get_locale();

				include('language.php');

				global $wpdb;

				$og = $wpdb->get_row("SELECT * from ".$wpdb->prefix."oa_og");

				if ($_POST) {

					extract($_POST);

					$wpdb->query("

						UPDATE ".$wpdb->prefix."oa_og SET 

						active = '".$active."', 

						defaulttitle = '".$defaulttitle."', 

						defaultsitename = '".$defaultsitename."', 

						defaultcontent = '".$defaultcontent."', 

						defaulttype = '".$defaulttype."', 

						defaulturl = '".$defaulturl."',

						defaultimage = '".$defaultimage."',

						defaultappid = '".$defaultappid."'

					");

					$og = $wpdb->get_row("SELECT * from ".$wpdb->prefix."oa_og");

					?>

					<div id="oa-update-success" style="background: green; color: #fff; position: absolute; left: 45%; padding: 10px 15px; font-size: 20px; border-radius: 0 0 5px 5px; display: none;">

						<?php echo $lang['Updated']; ?>

					</div>

					<script src="https://code.jquery.com/jquery-1.10.2.js"></script>

					<script>

						$(document).ready(function () {

							$('#oa-update-success').slideToggle();

							setTimeout(function () {

								$('#oa-update-success').slideToggle();

							}, 3000);

						});

					</script>

					<?php

				}

				?>

<div class="wrap">

	<h1><?php echo $lang['OA Open Graph for FB Options']; ?></h1>

	<form method="post" action="" novalidate="novalidate">

		<table class="form-table">

			<tr>

				<th scope="row"><?php echo $lang['Activate OA Open Graph']; ?>?</th>

				<td>

					<fieldset>

						<label for="active">

							<input name="active" type="checkbox" id="active" value="1" <?php if ($og->active == 1) { echo 'checked'; } ?> />

							<?php echo $lang['Select to Activate']; ?>

						</label>

					</fieldset>

				</td>

			</tr>

			<tr>

				<th scope="row" colspan="2">

					<h2><?php echo $lang['Default Content']; ?></h2>

					<p><?php echo $lang['Default content for when missing the Facebook Open Graph.']; ?></p>

				</th>

			</tr>

			<tr>

				<th scope="row">

					<label for="defaulttitle"><?php echo $lang['Default Title']; ?></label>

				</th>

				<td>

					<input id="defaulttitle" name="defaulttitle" type="text" class="regular-text" value="<?php echo $og->defaulttitle; ?>" />

				</td>

			</tr>

			<tr>

				<th scope="row">

					<label for="defaultsitename"><?php echo $lang['Default Site Name']; ?></label>

				</th>

				<td>

					<input id="defaultsitename" name="defaultsitename" type="text" class="regular-text" value="<?php echo $og->defaultsitename; ?>" />

				</td>

			</tr>

			<tr>

				<th scope="row">

					<label for="defaultcontent"><?php echo $lang['Default Content']; ?></label>

				</th>

				<td>

					<input id="defaultcontent" name="defaultcontent" type="text" class="regular-text" value="<?php echo $og->defaultcontent; ?>" />

				</td>

			</tr>

			<tr>

				<th scope="row">

					<label for="defaulttype"><?php echo $lang['Default Type']; ?></label>

				</th>

				<td>

					<input id="defaulttype" name="defaulttype" type="text" class="regular-text" value="<?php echo $og->defaulttype; ?>" />

				</td>

			</tr>

			<tr>

				<th scope="row">

					<label for="defaulturl"><?php echo $lang['Default URL']; ?></label>

				</th>

				<td>

					<input id="defaulturl" name="defaulturl" type="text" class="regular-text" value="<?php echo $og->defaulturl; ?>" />

				</td>

			</tr>

			<tr>

				<th scope="row">

					<label for="defaultimage"><?php echo $lang['Default Image URL']; ?></label>

				</th>

				<td>

					<input id="defaultimage" name="defaultimage" type="text" class="regular-text" value="<?php echo $og->defaultimage; ?>" />

				</td>

			</tr>

			<tr>

				<th scope="row">

					<label for="defaultappid"><?php echo $lang['Default App ID']; ?></label>

				</th>

				<td>

					<input id="defaultappid" name="defaultappid" type="text" class="regular-text" value="<?php echo $og->defaultappid; ?>" />

				</td>

			</tr>

			<tr>				<th scope="row">					<?php submit_button(); ?>				</th>				<td>					<h4><?php echo $lang['Need Help']."? ".$lang['Contact me by sending email to geral@oalves.com']; ?>.</h4>					<h4>						<?php echo $lang['Like this plugin']."? ".$lang['Give a thank you by donating one coffie']; ?>						<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7GB7B2M62RTH2" target="_blank">							<img style="margin: -6px 5px; padding: 0;" src="https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_donate_LG.gif" alt="PayPal - A forma mais fácil e segura de efetuar pagamentos online!" />						</a>					</h4>				</td>			</tr>

		</table>

	</form>

</div>

				<?php

			}

			

			$og = $wpdb->get_row("SELECT * from ".$wpdb->prefix."oa_og");

			if ($og->active == 1) {

				add_action('wp_head', 'add_content_after_header', 1);

				add_action( 'admin_menu', 'oa_og_create_post_meta_box' );

				add_action( 'save_post', 'oa_og_save_post_meta_box', 10, 2 );

			}



			function oa_og_create_post_meta_box() {

				$local = get_locale();

				include( ABSPATH . 'wp-content/plugins/oa-open-graph-for-fb/language.php');

				add_meta_box( 'my-meta-box', $lang['Facebook Open Graph Content'], 'oa_og_post_meta_box', 'post', 'normal', 'high' );

			}



			function oa_og_post_meta_box( $object, $box ) {

				$local = get_locale();

				include( ABSPATH . 'wp-content/plugins/oa-open-graph-for-fb/language.php');

				?>

				<p>

					<label for="ogtitle"><?php echo $lang['Title']; ?></label>

					<br />

					<textarea name="ogtitle" id="ogtitle" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo wp_specialchars( get_post_meta( $object->ID, 'OG Title', true ), 1 ); ?></textarea>

					<input type="hidden" name="my_meta_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

				</p>

				<p>

					<label for="ogdescription"><?php echo $lang['Description']; ?></label>

					<br />

					<textarea name="ogdescription" id="ogdescription" cols="60" rows="4" tabindex="30" style="width: 97%;"><?php echo wp_specialchars( get_post_meta( $object->ID, 'OG Description', true ), 1 ); ?></textarea>

					<input type="hidden" name="my_meta_box_nonce" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

				</p>

			<?php }



			function oa_og_save_post_meta_box( $post_id, $post ) {



				if ( !wp_verify_nonce( $_POST['my_meta_box_nonce'], plugin_basename( __FILE__ ) ) )

					return $post_id;



				if ( !current_user_can( 'edit_post', $post_id ) )

					return $post_id;



				$meta_value = get_post_meta( $post_id, 'OG Title', true );

				$new_meta_value = stripslashes( $_POST['ogtitle'] );



				if ( $new_meta_value && '' == $meta_value )

					add_post_meta( $post_id, 'OG Title', $new_meta_value, true );



				elseif ( $new_meta_value != $meta_value )

					update_post_meta( $post_id, 'OG Title', $new_meta_value );



				elseif ( '' == $new_meta_value && $meta_value )

					delete_post_meta( $post_id, 'OG Title', $meta_value );

					

				$meta_value2 = get_post_meta( $post_id, 'OG Description', true );

				$new_meta_value2 = stripslashes( $_POST['ogdescription'] );



				if ( $new_meta_value2 && '' == $meta_value2 )

					add_post_meta( $post_id, 'OG Description', $new_meta_value2, true );



				elseif ( $new_meta_value2 != $meta_value2 )

					update_post_meta( $post_id, 'OG Description', $new_meta_value2 );



				elseif ( '' == $new_meta_value2 && $meta_value2 )

					delete_post_meta( $post_id, 'OG Description', $meta_value2 );

			}

 

			function add_content_after_header() {

				global $wpdb;

				$og = $wpdb->get_row("SELECT * from ".$wpdb->prefix."oa_og");

				if (is_single()) {

					?>

					<meta property="og:title" content="<?php if (get_post_meta(get_the_ID(),'OG Title',TRUE)) { echo stripslashes(get_post_meta(get_the_ID(),'OG Title',TRUE)); } else { echo get_the_title( get_the_ID() ); }; ?>"/>

					<meta property="og:site_name" content="<?php if ($og->defaultsitename) { echo $og->defaultsitename; } else { echo get_bloginfo('name'); } ?>"/>

					<meta property="og:description" content="<?php if (get_post_meta(get_the_ID(),'OG Description',TRUE)) { echo get_post_meta(get_the_ID(),'OG Description',TRUE); } else { echo $og->defaultcontent; } ?>"/>

					<meta property="og:type" content="<?php if ($og->defaulttype) { echo $og->defaulttype; } else { echo 'blog'; } ?>"/>

					<meta property="og:url" content="<?php if (get_permalink($post->ID)) { echo get_permalink($post->ID); } else { echo $og->defaulturl; } ?>"/>

					<meta property="og:image" content="<?php if (wp_get_attachment_url( get_post_thumbnail_id($post->ID) )) { echo wp_get_attachment_url( get_post_thumbnail_id($post->ID) ); } else { echo $og->defaultimage; } ?>"/>

					<meta property="fb:app_id" content="<?php if ($og->defaultappid) { echo $og->defaultappid; } else { echo "966242223397117"; } ?>"/>

					<?php

				} else {

					?>

					<meta property="og:title" content="<?php if (get_post_meta(get_the_ID(),'OG Title',TRUE)) { echo get_post_meta(get_the_ID(),'OG Title',TRUE); } else { echo get_the_title( get_the_ID() ); }; ?>"/>

					<meta property="og:site_name" content="<?php if ($og->defaultsitename) { echo $og->defaultsitename; } else { echo get_bloginfo('name'); } ?>"/>

					<meta property="og:description" content="<?php if (get_post_meta(get_the_ID(),'OG Description',TRUE)) { echo get_post_meta(get_the_ID(),'OG Description',TRUE); } else { echo $og->defaultcontent; } ?>"/>

					<meta property="og:type" content="<?php if ($og->defaulttype) { echo $og->defaulttype; } else { echo 'blog'; } ?>"/>

					<meta property="og:url" content="<?php echo "http://".$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]; ?>" />

					<meta property="og:image" content="<?php echo $og->defaultimage; ?>"/>

					<meta property="fb:app_id" content="<?php if ($og->defaultappid) { echo $og->defaultappid; } else { echo "966242223397117"; } ?>"/>

					<?php

				}

			}

			

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			dbDelta( $create_og );

		}

	}

	new oa_open_graph;

 }



define( 'OA_OPEN_GRAPH__VERSION', '1.0.2' );

define( 'OA_OPEN_GRAPH__MINIMUM_WP_VERSION', '0.0.1' );

define( 'OA_OPEN_GRAPH__PLUGIN_URL', plugin_dir_url( __FILE__ ) );

define( 'OA_OPEN_GRAPH__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

define( 'OA_OPEN_GRAPH_DELETE_LIMIT', 100000 );
?>