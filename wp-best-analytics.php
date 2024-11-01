<?php
/*
Plugin Name: WP Best Analytics
Plugin URI: https://www.eastsidecode.com
Description: Best analytics plugin for having analytics installed on live sites and dev sites without showing up on both.
Author: Louis Fico
Version: 2.0
Author URI: eastsidecode.com
*/

if ( ! defined( 'ABSPATH' ) ) exit;
 




add_action('admin_menu', function() {
	add_submenu_page( 'options-general.php', 'WP Best Analytics', 'Analytics Settings', 'manage_options', 'wp_best_analytics_analytics_settings', 'wp_best_analytics_analytics_page' );
});
 
 
add_action( 'admin_init', function() {
    register_setting( 'wp-best-analytics-analytics-plugin-settings', 'wp_best_analytics_analytics_meta_field' );
    register_setting( 'wp-best-analytics-analytics-plugin-settings', 'wp_best_analytics_analytics_bing_webmaster_meta_field' );

    register_setting( 'wp-best-analytics-analytics-plugin-settings', 'wp_best_analytics_analytics_tracking_code_field' );
    register_setting( 'wp-best-analytics-analytics-plugin-settings', 'wp_best_analytics_analytics_dev_url' );
    register_setting( 'wp-best-analytics-analytics-plugin-settings', 'wp_best_analytics_analytics_dev_two' );



});
 
 
function wp_best_analytics_analytics_page() {
  ?>
    <div class="wrap">
    	<h1>Analytics Settings</h1>
      <form action="options.php" method="post">
 
        <?php
          settings_fields( 'wp-best-analytics-analytics-plugin-settings' );
          do_settings_sections( 'wp-best-analytics-analytics-plugin-settings' );
        ?>
        <table>
             <tr>
                <td height="10" style="font-size:10px; line-height:10px;"></td>
            </tr>

            <tr>
                <th valign="top" style="text-align: right;">Analyitcs Tracking ID:</th>
                <td><input placeholder="Analytics Tracking ID" name="wp_best_analytics_analytics_tracking_code_field" rows="14" cols="80" value="<?php echo esc_attr( get_option('wp_best_analytics_analytics_tracking_code_field') ); ?>" /></td>
            </tr>

            <tr>
				<td height="15" style="font-size:15px; line-height:15px;"></td>
            </tr>

              <tr>
                <th style="text-align: right;">Google Meta Field for Verification (optional):</th>
                <td><input type="text" placeholder="Verification field for Google search console" name="wp_best_analytics_analytics_meta_field" value="<?php echo esc_attr( get_option('wp_best_analytics_analytics_meta_field') ); ?>" size="80" /></td>
            </tr>
            <tr>
                <td height="2" style="font-size:2px; line-height:2px;"></td>
            </tr>

           <tr>
                <th></th>
                <td>Use this field if you'd like to verify your Google Search console property with a meta tag. Only add the value inside of content="".</td>
            </tr>

            <tr>
                <td height="10" style="font-size:10px; line-height:10px;"></td>
            </tr>

              <tr>
                <th style="text-align: right;">Bing Meta Field for Verification (optional):</th>
                <td><input type="text" placeholder="Verification field for Bing Webmaster Tools" name="wp_best_analytics_analytics_bing_webmaster_meta_field" value="<?php echo esc_attr( get_option('wp_best_analytics_analytics_bing_webmaster_meta_field') ); ?>" size="80" /></td>
            </tr>

            <tr>
				<td height="2" style="font-size:2px; line-height:2px;"></td>
            </tr>

            <tr>
            	<th></th>
            	<td>Use this field if you'd like to verify your Bing Webmaster tools acount with a meta tag. Only add the value inside of content="".</td>
            </tr>


            <tr>
                <td height="15" style="font-size:15px; line-height:15px;"></td>
            </tr>


            <tr>
                <th style="text-align: right;">Dev Site URL (optional):</th>
                <td><input type="text" placeholder="Example: dev.example.com" name="wp_best_analytics_analytics_dev_url" value="<?php echo esc_attr( get_option('wp_best_analytics_analytics_dev_url') ); ?>" size="80" /></td>
            </tr>

            <tr>
				<td height="2" style="font-size:2px; line-height:2px;"></td>
            </tr>

            <tr>
            	<th></th>
            	<td>If the dev site URL is filled in, then the analytics codes won't show up on the dev site</td>
            </tr>


            <tr>
				<td height="10" style="font-size:10px; line-height:10px;"></td>
            </tr>
            <tr>
                <th style="text-align: right;">Dev Site 2 URL (optional):</th>
                <td><input type="text" placeholder="Example: example.local" name="wp_best_analytics_analytics_dev_two" value="<?php echo esc_attr( get_option('wp_best_analytics_analytics_dev_two') ); ?>" size="80" /></td>
            </tr>

            <tr>
				<td height="2" style="font-size:2px; line-height:2px;"></td>
            </tr>

            <tr>
            	<th></th>
            	<td>Use this field if your site has more than one dev url (for example, on a subdomain and in a local environment).</td>
            </tr>

 
            <tr>
                <td><?php submit_button(); ?></td>
            </tr>
 
        </table>
 
      </form>
    </div>
  <?php
}





function wp_best_analytics_add_analytics_code() {

// get option
$trackingCode = sanitize_text_field(get_option('wp_best_analytics_analytics_tracking_code_field'));

// set dev site to false
$devSite = false;

// get current browsrr url
$curBrowserURL = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];



$devUrlOne = get_option('wp_best_analytics_analytics_dev_url');
$devUrlTwo = get_option('wp_best_analytics_analytics_dev_two');


if (isset($devUrlOne)  && !empty($devUrlOne)) { // if dev url one is filled out

if ((strpos($curBrowserURL, $devUrlOne) !== false)) { // if the browser url is equal to dev site one

	return false;

}

}

if (isset($devUrlTwo) && !empty($devUrlTwo)) { // if dev url one is filled out

if ((strpos($curBrowserURL, $devUrlTwo) !== false)) { // if the browser url is equal to dev site one

	return false;

}

}



if (isset($trackingCode)) { ?>
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $trackingCode; ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?php echo $trackingCode; ?>');
</script>

<?php
}

}

add_action('wp_footer', 'wp_best_analytics_add_analytics_code');




function wp_best_analytics_add_site_verification() {
// get option
$veriticationCode = sanitize_text_field(get_option('wp_best_analytics_analytics_meta_field'));
if (isset($veriticationCode) && !empty($veriticationCode)) { ?>
<meta name="google-site-verification" content="<?php echo $veriticationCode; ?>" />

<?php
}

$bingVerificationCode = sanitize_text_field(get_option('wp_best_analytics_analytics_bing_webmaster_meta_field'));

if (isset($bingVerificationCode) && !empty($bingVerificationCode)) { ?>
<meta name="msvalidate.01" content="<?php echo $bingVerificationCode; ?>"/>
<?php
}


} // end. function

add_action('wp_head', 'wp_best_analytics_add_site_verification');


