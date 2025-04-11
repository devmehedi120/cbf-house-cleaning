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
			'location-manager',
			'Saved Locations',
			'Saved Locations',
			'manage_options',
			'saved-locations',
			[$this,'render_saved_locations_page']
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
			<h1>Manage Locations</h1>
			<input type="text" id="location-input" placeholder="Enter location name" />
			<button id="add-location" class="button button-primary">Add Location</button>
	
			<table class="widefat fixed striped" style="margin-top: 20px;">
				<thead>
					<tr><th>Location Name</th><th>Action</th></tr>
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
	
	function render_saved_locations_page() {
		$locations = get_option('predefined_locations', []);
		?>
		<div class="wrap">
			<h1>Saved Locations</h1>
			<?php if (!empty($locations)): ?>
				<table class="widefat fixed striped">
					<thead><tr><th>#</th><th>Location Name</th></tr></thead>
					<tbody>
						<?php foreach ($locations as $index => $loc): ?>
							<tr>
								<td><?php echo $index + 1; ?></td>
								<td><?php echo esc_html($loc); ?></td>
								<td><button class='button delete-location'>Delete</button></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php else: ?>
				<p>No locations found.</p>
			<?php endif; ?>
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
