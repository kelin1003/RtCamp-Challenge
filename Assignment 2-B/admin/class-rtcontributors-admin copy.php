<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       sociallyawkward.in
 * @since      1.0.0
 *
 * @package    Rtcontributors
 * @subpackage Rtcontributors/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rtcontributors
 * @subpackage Rtcontributors/admin
 * @author     Kelin Chauhan <kelin1003@gmail.com>
 */
class Rtcontributors_Admin {

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
		 * defined in Rtcontributors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rtcontributors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rtcontributors-admin.css', array(), $this->version, 'all' );

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
		 * defined in Rtcontributors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rtcontributors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rtcontributors-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds the metabox to the post edit/add page to add more than one author/		  * contributors
	 *
	 * @since    1.0.0
	 */
	function rtcc_add_contributors_metabox() {

		add_meta_box("rtcc-add-contributors-meta", "Add Contributors", array($this,"rtcc_add_contributors_meta_box_markup") ,"post", "normal", "high", null);

	}

	/**
	 * Markup function for the rtcc_add_contributos_metabox
	 *
	 * @since    1.0.0
	 */
	function rtcc_add_contributors_meta_box_markup() {

		global $wpdb;

		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/contributors-metabox-display.php';


		$usersList = get_users( [ 'role__not_in' => [ 'subscriber' ] ]);

		foreach ($usersList as $user) {
			
			$term_id =  term_exists( $user->data->user_nicename );
			
			$results = $wpdb->get_results( 'SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = '.$term_id );
			
			$term_taxonomy_id = $results[0]->term_taxonomy_id;

			// wp_set_object_terms(  )


			echo '<label for="'.$user->data->ID.'" ><input type="checkbox" name="rtcc-authors[]" id="'.$user->data->ID.'" form="post" value= "'.$user->data->user_nicename.'">'.$user->data->user_nicename.' ('.$user->data->user_email.')</label>';
		}

	}


	/**
	 * This function saves the authors name to the database provided in the 		 * contributors metabox
	 * @since    1.0.0
	 */
	function rtcc_add_contributors_save_post_callback( $post_id, $post, $update ) {
		
		global $wpdb;
	

		if(empty($_REQUEST['rtcc-authors'])) return;
		
		$usersList = $_REQUEST['rtcc-authors'];

		foreach ($usersList as $user) {
			
			$term_id =  term_exists( $user );
			
			$results = $wpdb->get_results( 'SELECT term_taxonomy_id FROM wp_term_taxonomy WHERE term_id = '.$term_id );
			
			$term_taxonomy_id = $results[0]->term_taxonomy_id;

			$wpdb->insert( 'wp_term_relationships', [ 'object_id' => $post_id, 'term_taxonomy_id' => $term_taxonomy_id, 'term_order' => 1 ] );
	
		}


	}

	
	
	/**
	 * This the the markup function for the coauthors shortcode
	 *
	 * @since    1.0.0
	 */
	function rtcc_coauthors_shortcode( $atts = [], $content = null, $tags = [] ) {
		
		global $post, $wpdb;
		ob_start();

		$results = $wpdb->get_results( 'SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id = '.$post->ID);

		?> <script type="text/javascript"> <?php

		if( !empty( $results ) ) {
			foreach ($results as $value) {
				$results1 = $wpdb->get_results( 'SELECT term_id FROM wp_term_taxonomy WHERE term_taxonomy_id = ' .$value->term_taxonomy_id. ' AND taxonomy = "author"');
				foreach ($results1 as $value1) {
					$results2 = $wpdb->get_results( 'SELECT name FROM wp_terms WHERE term_id = ' .$value1->term_id );
						
					foreach ($results2 as $value2) {
						
						echo 'var $ = jQuery;
							var a = document.createElement("a");
							a.className = "fn";
							a.className = "url";
							a.className = "n";
							a.href = "http://localhost/wordpress/author/'.$value2->name.'";
							a.innerHTML = "'.$value2->name.'";
							$(".vcard").append(" and ");
							$(".vcard").append(a);';

						
					}
				}


			}
		}


		?> </script> <?php

		return ob_get_clean();
	}		

}
