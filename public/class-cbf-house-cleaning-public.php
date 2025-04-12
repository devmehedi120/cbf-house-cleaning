<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.fiverr.com/wpdevmehedi
 * @since      1.0.0
 *
 * @package    Cbf_House_Cleaning
 * @subpackage Cbf_House_Cleaning/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cbf_House_Cleaning
 * @subpackage Cbf_House_Cleaning/public
 * @author     Mehedi Hasan  <wpdevmehedi@gmail.com>
 */
class Cbf_House_Cleaning_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'cbf_house_cleaning', [$this, 'cbf_house_cleaning_html'] );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cbf_House_Cleaning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cbf_House_Cleaning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( '_font_awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css' );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cbf-house-cleaning-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cbf_House_Cleaning_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cbf_House_Cleaning_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		 $pricing = array(
			'basic'   => floatval(get_option('pricing_basic', 0.25)),
			'deep'    => floatval(get_option('pricing_deep', 0.40)),
			'move'    => floatval(get_option('pricing_move', 0.55)),
			'discounts' => array(
				'weekly'  => floatval(get_option('pricing_discount_weekly', 0.10)),
				'monthly' => floatval(get_option('pricing_discount_monthly', 0.15))
			)
		);

		wp_enqueue_script('_vuejs', plugin_dir_url( __FILE__ ) . 'js/vueCDn.js', array( ), $this->version, false );
	
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cbf-house-cleaning-public.js', array( 'jquery','_vuejs' ), $this->version, true );
		wp_localize_script($this->plugin_name, 'locationAjax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('get_locations_nonce'),
			'pricing'=>$pricing
		]);
	

	}


	function cbf_house_cleaning_html(){
		ob_start();
         require_once plugin_dir_path( __FILE__ ). 'partials/cbf-house-cleaning-shortcode.php';
		return ob_get_clean();
	}



function get_predefined_locations() {
	check_ajax_referer('get_locations_nonce');

	$bookings = get_posts(array(
		'post_type'      => 'booking',
		'post_status'    => 'publish',
		'numberposts'    => -1,
	));
	
	$all_dates = array();
	
	foreach ($bookings as $booking) {
		$booking_data = json_decode($booking->post_content, true);
	
		if (!empty($booking_data['date'])) {
			$all_dates[] = $booking_data['date'];
		}
	}

	$custom_offdays = get_option('custom_off_days', []); // return empty array if not set
	$locations = get_option('predefined_locations', []); // return empty array if not set

	$frontdata = array(
        'off_days' => $custom_offdays,
        'locations' => $locations,
		'all_dates'=>$all_dates
    );
	wp_send_json_success($frontdata);
}
function handle_confirm_booking() {
    // Verify nonce
    if( !isset($_POST['_ajax_nonce']) || !wp_verify_nonce( $_POST['_ajax_nonce'], 'get_locations_nonce' ) ) {
        wp_send_json_error('Invalid nonce');
        return;
    }

    // Decode the booking data
    $booking_data = json_decode(stripslashes($_POST['bookingData']), true);
  
    // Save the booking as a custom post
    $post_data = array(
        'post_title'   => $booking_data['fullName'] . ' - ' . $booking_data['serviceType'], // Title of the booking
        'post_content' => json_encode($booking_data),
        'post_status'  => 'publish',  // You can use 'pending' if you want to manually approve
        'post_type'    => 'booking', // Custom post type for bookings
        
    );
    
    $post_id = wp_insert_post($post_data);
  
	if ($post_id) {
		// Send confirmation email to admin
		$admin_email = get_option('admin_email');
		$subject = 'New Booking Confirmed: ' . $booking_data['fullName'];
		
		// Get the HTML template
		ob_start();
		 require_once plugin_dir_path( __FILE__ ). 'partials/mail-template.php';
		$message = ob_get_clean();
		
		// Set HTML content type
		$headers = array('Content-Type: text/html; charset=UTF-8');
		
		wp_mail($admin_email, $subject, $message, $headers);
		
		// Respond to the AJAX request
		wp_send_json_success(['message' => 'Booking confirmed and email sent to admin']);
	} else {
		wp_send_json_error('Failed to save the booking');
	}
}

  
}
