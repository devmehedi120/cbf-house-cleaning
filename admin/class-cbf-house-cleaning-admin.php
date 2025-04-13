<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/wpdevmehedi
 * @since      1.0.0
 *
 * @package    Cbf_House_Cleaning
 * @subpackage Cbf_House_Cleaning/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cbf_House_Cleaning
 * @subpackage Cbf_House_Cleaning/admin
 * @author     Mehedi Hasan  <wpdevmehedi@gmail.com>
 */
class Cbf_House_Cleaning_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_action('admin_init', [$this, 'register_ajax_actions']);


	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cbf-house-cleaning-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
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
		

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cbf-house-cleaning-admin.js', array( 'jquery' ), $this->version, false );


		wp_enqueue_script('off-days-admin', plugin_dir_url(__FILE__) . 'js/off-days-admin.js', ['jquery'], null, true);
		wp_localize_script('off-days-admin', 'offDaysData', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('save_off_days')
		]);

		wp_enqueue_script('location-js', plugin_dir_url(__FILE__) . 'js/location-manager.js', ['jquery'], '1.0', true);
				wp_localize_script('location-js', 'locationAjax', [
					'ajax_url' => admin_url('admin-ajax.php'),
					'nonce'    => wp_create_nonce('location_nonce')
				]);

	}
	

	function custom_location_menu() {
		add_menu_page(
			'Location Manager',
			'Location Manager',
			'manage_options',
			'location-manager',
			[$this,'render_location_manage_page'],
			'dashicons-location',
			60
		);
	
		add_submenu_page(
			'location-manager',
			'Manage Locations',
			'Manage Locations',
			'manage_options',
			'location-manager',
			[$this,'render_location_manage_page']
		);
	
	

		add_submenu_page(
			'location-manager', // Parent slug
			'Manage Off Days',  // Page title
			'Off Days',         // Menu title
			'manage_options',   // Capability
			'location-off-days', // Slug
			[$this, 'render_off_days_page'] // Callback
		);

		add_submenu_page(
			'location-manager',
			'Bookings',            // Page title
			'Bookings',            // Menu title
			'manage_options',      // Capability required
			'view_bookings',       // Menu slug
			[$this,'view_bookings_page'],  // Function to display the content
			                     // Menu position
		);

		add_submenu_page(
			'location-manager',
			'Pricing Settings',
			'Pricing Settings',
			'manage_options',
			'pricing-settings',
			[$this,'pricing_settings_page'],
			
		);
		add_submenu_page(
			'location-manager',
			'Settings',
			'Settings',
			'manage_options',
			'settings',
			[$this,'cbf_settings'],
			
		);
		
	}

	function cbf_settings() {
		?>
		<div class="wrap">
			<h1>CBF Settings</h1>
			<style>
				.cbf-settings-table {
					border-collapse: collapse;
					width: 100%;
					max-width: 600px;
					margin-top: 20px;
				}
				.cbf-settings-table th,
				.cbf-settings-table td {
					text-align: left;
					padding: 10px 5px;
					border: none;
				}
				.cbf-shortcode-input {
					width: 100%;
					border: none;
					background: #f4f4f4;
					padding: 8px;
					font-family: monospace;
					font-size: 14px;
					cursor: pointer;
				}
			</style>
	
			<table class="cbf-settings-table">
				<tr>
					<th>Purpose</th>
					<th>Shortcode</th>
				</tr>
				<tr>
					<td>Display House Cleaning Feature</td>
					<td>
						<input type="text" class="cbf-shortcode-input" value="[cbf_house_cleaning]" readonly onclick="this.select();" />
					</td>
				</tr>
			</table>
	
			<p>Click the shortcode box to copy it.</p>
		</div>
		<?php
	}
	
	
	function pricing_settings_page() {
		// Handle form submit
		if (isset($_POST['pricing_settings_nonce']) && wp_verify_nonce($_POST['pricing_settings_nonce'], 'save_pricing_settings')) {
			update_option('pricing_basic', floatval($_POST['pricing_basic']));
			update_option('pricing_deep', floatval($_POST['pricing_deep']));
			update_option('pricing_move', floatval($_POST['pricing_move']));
			update_option('pricing_discount_weekly', floatval($_POST['pricing_discount_weekly']));
			update_option('pricing_discount_monthly', floatval($_POST['pricing_discount_monthly']));
			echo '<div class="updated"><p>Pricing settings saved!</p></div>';
		}
	
		?>
		<div class="wrap">
			<h1>Pricing Settings</h1>
			<form method="post">
				<?php wp_nonce_field('save_pricing_settings', 'pricing_settings_nonce'); ?>
	
				<table class="form-table">
					<tr><th>Basic</th><td><input type="text" name="pricing_basic" value="<?php echo esc_attr(get_option('pricing_basic', 0.25)); ?>"></td></tr>
					<tr><th>Deep</th><td><input type="text" name="pricing_deep" value="<?php echo esc_attr(get_option('pricing_deep', 0.40)); ?>"></td></tr>
					<tr><th>Move</th><td><input type="text" name="pricing_move" value="<?php echo esc_attr(get_option('pricing_move', 0.55)); ?>"></td></tr>
					<tr><th>2-Weekly Discount</th><td><input type="text" name="pricing_discount_weekly" value="<?php echo esc_attr(get_option('pricing_discount_weekly', 0.10)); ?>"></td></tr>
					<tr><th>Monthly Discount</th><td><input type="text" name="pricing_discount_monthly" value="<?php echo esc_attr(get_option('pricing_discount_monthly', 0.15)); ?>"></td></tr>
				</table>
	
				<p><input type="submit" class="button-primary" value="Save Settings"></p>
			</form>
		</div>
		<?php
	}
	
	public function render_off_days_page() {
		?>
		<div class="wrap">
			<h1>Manage Off Days</h1>
	
			<input type="date" id="off_day_input">
			<button id="add_off_day_btn" class="button button-primary">Add Off Day</button>
	
			
		</div>

		<table id="off_days_table" class=" widefat fixed striped pages">
    <thead>
        <tr>
            <th class="manage-column">Date</th>
            <th class="manage-column">Action</th>
        </tr>
    </thead>
    <tbody>
        <!-- Off days will be dynamically inserted here -->
    </tbody>
</table>


	
		
		<?php
	}
	
	
	function render_location_manage_page() {
		?>
		<div class="wrap">
			<h1>Manage Locations/PostCode</h1>
			<input type="text" id="location-input" placeholder="Enter location name" />
			<button id="add-location" class="button button-primary">Add Post Code</button>
	
			<table class="widefat fixed striped" style="margin-top: 20px;">
				<thead>
					<tr><th>Post Code</th><th>Action</th></tr>
				</thead>
				<tbody id="location-list">
					<?php
					$locations = get_option('predefined_locations', []);
					foreach ($locations as $loc) {
						echo "<tr data-location='" . esc_attr($loc) . "'>
								<td>" . esc_html($loc) . "</td>
								<td><button class='button delete-location'>Delete</button></td>
							  </tr>";
					}
					?>
				</tbody>
			</table>
		</div>
		<?php
	}
	
	
	


function handle_add_location() {
    check_ajax_referer('location_nonce');

    $location = sanitize_text_field($_POST['location']);
    if (empty($location)) wp_send_json_error('Empty location name.');

    $locations = get_option('predefined_locations', []);
    if (!in_array($location, $locations)) {
        $locations[] = $location;
        update_option('predefined_locations', $locations);
    }

    wp_send_json_success($location);
}

function handle_delete_location() {
    check_ajax_referer('location_nonce');

    $location = sanitize_text_field($_POST['location']);
    $locations = get_option('predefined_locations', []);
    $locations = array_filter($locations, fn($loc) => $loc !== $location);
    update_option('predefined_locations', array_values($locations));

    wp_send_json_success('Deleted');
}


public function register_ajax_actions() {
    add_action('wp_ajax_add_off_day', [$this, 'add_off_day']);
    add_action('wp_ajax_delete_off_day', [$this, 'delete_off_day']);
    add_action('wp_ajax_get_off_days', [$this, 'get_off_days']);
}

public function get_off_days() {
    $off_days = get_option('custom_off_days', []);
    wp_send_json($off_days);
}

public function add_off_day() {
    $day = sanitize_text_field($_POST['day']);
    $off_days = get_option('custom_off_days', []);
    if (!in_array($day, $off_days)) {
        $off_days[] = $day;
        update_option('custom_off_days', $off_days);
    }
    wp_send_json_success();
}

public function delete_off_day() {
    $day = sanitize_text_field($_POST['day']);
    $off_days = get_option('custom_off_days', []);
    if (($key = array_search($day, $off_days)) !== false) {
        unset($off_days[$key]);
        update_option('custom_off_days', array_values($off_days));
    }
    wp_send_json_success();
}

// Register custom post type for bookings
// Register custom post type for bookings
function create_booking_post_type() {
    $labels = array(
        'name'               => 'Bookings',
        'singular_name'      => 'Booking',
        'menu_name'          => 'Bookings',
        'name_admin_bar'     => 'Booking',
        'all_items'          => 'All Bookings',
        'search_items'       => 'Search Bookings',
        'not_found'          => 'No bookings found.',
        'not_found_in_trash' => 'No bookings found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false, // Make the post type not publicly accessible
        'show_ui'            => false,  // Show it in the admin UI
        'show_in_menu'       => false,  // Show it in the WordPress admin menu
        'show_in_admin_bar'  => false,  // Show it in the WordPress admin bar
        'show_in_nav_menus'  => false, // Hide it from menus
        'can_export'         => true,  // Allow export
        'has_archive'        => false, // No public archive
        'hierarchical'       => false, // Not hierarchical
        'menu_position'      => 5,     // Menu position in the dashboard
        'supports'           => array('title', 'editor', 'custom-fields'), // Supported features
        'capability_type'    => 'post', // Basic capabilities
        'map_meta_cap'       => true,  // Map meta capabilities for custom post types
        'show_in_rest'       => false, // Disable REST API
    );

    // Register the custom post type
    register_post_type('booking', $args);
}



function view_bookings_page() {
    // Start output buffering to prevent header errors
    ob_start();

    // Handle the delete request if 'delete' parameter is set
    if (isset($_GET['delete']) && current_user_can('delete_posts')) {
        $booking_id_to_delete = intval($_GET['delete']);
        // Fetch the booking post
        $booking = get_post($booking_id_to_delete);
        
        if ($booking && $booking->post_type === 'booking') {
            // Delete the booking post
            wp_delete_post($booking_id_to_delete, true); // true to force deletion
			echo '<script type="text/javascript">
			window.location.href = "' . admin_url('admin.php?page=view_bookings') . '";
		  </script>';
            exit; // Always call exit after wp_redirect to stop further script execution
        }
    }

    // Check if 'view' parameter is set for detailed view
    if (isset($_GET['view'])) {
        $booking_id = intval($_GET['view']);
        $this->view_booking_invoice($booking_id);
        return;
    }

    // Fetch all bookings
    $args = array(
        'post_type' => 'booking',
        'post_status' => 'publish',
        'posts_per_page' => -1, // Show all bookings
    );
    $query = new WP_Query($args);

    if ($query->have_posts()) :
        echo '<div class="wrap">';
        echo '<h1 class="wp-heading-inline">Bookings</h1>';
        echo '<table class="wp-list-table widefat fixed striped posts">';
        echo '<thead><tr><th>ID</th><th>Title</th><th>Date</th><th>Location</th><th>Actions</th></tr></thead>';
        echo '<tbody>';

        while ($query->have_posts()) : $query->the_post();
            $booking_id = get_the_ID();
            $post_content = get_post_field('post_content', $booking_id);
            $post_content = json_decode($post_content);

            echo '<tr>';
            echo '<td>' . $booking_id . '</td>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td>' . esc_html($post_content->date) . '</td>';
            echo '<td>' . esc_html($post_content->location) . '</td>';
            echo '<td>';
            echo '<a href="' . admin_url('admin.php?page=view_bookings&view=' . $booking_id) . '" class="button">View</a>';
            echo ' <a href="' . admin_url('admin.php?page=view_bookings&delete=' . $booking_id) . '" class="button button-secondary" onclick="return confirm(\'Are you sure you want to delete this booking?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
        endwhile;

        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        wp_reset_postdata();
    else :
        echo '<p>No bookings found.</p>';
    endif;

    // End output buffering and clean
    ob_end_flush();
}


// Display the invoice-style table for the selected booking
function view_booking_invoice($booking_id) {
    // Fetch the booking post content
    $post_content = get_post_field('post_content', $booking_id);
    $post_content = json_decode($post_content);

    // Check if content exists and is a valid object
    if ($post_content && is_object($post_content)) {
        // Start the invoice HTML layout
        echo '<div class="booking-invoice">';
		echo '<a href="' . esc_url( admin_url( 'admin.php?page=view_bookings' ) ) . '" style="display: inline-block; padding: 8px 16px; background-color: #0073aa; color: #fff; text-decoration: none; border-radius: 4px; font-weight: bold;">&larr; Back to Bookings</a>';

        echo '<h1>Booking Invoice</h1>';
        echo '<div class="invoice-details">';
        
        // Display booking details
        echo '<table>';
        echo '<tr><td><strong>Property Type:</strong></td><td>' . esc_html($post_content->propertyType) . '</td></tr>';
        echo '<tr><td><strong>Square Feet:</strong></td><td>' . esc_html($post_content->squareFeet) . '</td></tr>';
        echo '<tr><td><strong>Service Type:</strong></td><td>' . esc_html($post_content->serviceType) . '</td></tr>';
        echo '<tr><td><strong>Frequency:</strong></td><td>' . esc_html($post_content->frequency) . '</td></tr>';
        echo '<tr><td><strong>Date:</strong></td><td>' . esc_html($post_content->date) . '</td></tr>';
        echo '<tr><td><strong>Time Slot:</strong></td><td>' . esc_html($post_content->timeSlot) . '</td></tr>';
        echo '<tr><td><strong>Special Requests:</strong></td><td>' . esc_html($post_content->specialRequests) . '</td></tr>';
        echo '<tr><td><strong>Full Name:</strong></td><td>' . esc_html($post_content->fullName) . '</td></tr>';
        echo '<tr><td><strong>Email:</strong></td><td>' . esc_html($post_content->email) . '</td></tr>';
        echo '<tr><td><strong>Phone:</strong></td><td>' . esc_html($post_content->phone) . '</td></tr>';
        echo '<tr><td><strong>Location:</strong></td><td>' . esc_html($post_content->location) . '</td></tr>';
        echo '<tr><td><strong>Address:</strong></td><td>' . esc_html($post_content->address) . '</td></tr>';
        echo '<tr><td><strong>Payment Method:</strong></td><td>' . esc_html($post_content->paymentMethod) . '</td></tr>';
        echo '</table>';
        
        echo '</div>'; // End invoice details
        echo '</div>'; // End booking invoice

        // Add any additional styling or custom elements here
    } else {
        echo '<p>Booking not found.</p>';
    }
}



}
