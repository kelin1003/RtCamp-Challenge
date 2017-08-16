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

		$posts = get_post_types();
		foreach ($posts as  $post) {
			add_meta_box("rtcc-add-contributors-meta", "Add Contributors", array($this,"rtcc_add_contributors_meta_box_markup") ,$post, "side", "high", null);	
		}
		

	}

	/**
	 * Markup function for the rtcc_add_contributos_metabox
	 *
	 * @since    1.0.0
	 */
	function rtcc_add_contributors_meta_box_markup() {

		global $wpdb, $post;

		$usersList = get_users( [ 'role__not_in' => [ 'subscriber' ] ]);
		
		if( $post ) {
			$coAuthors = get_post_meta( $post->ID, 'rtcc_coauthors', true );
			$coAuthors = explode( ',', $coAuthors );
		}

		echo '<input tpye="text" id="search_author" onkeyup="searchAuthor(this.value);" placeholder="Enter Name / Email"/><br><br>';

		echo '<input type="hidden" value="true" name = "auhidden" form="post"/>';

		echo '<div class="contributors-metabox">';

		foreach ($usersList as $user) {

			if( get_current_user_id() == $user->data->ID ) {
				continue;
			}

			if( $post ) {

				if( in_array( $user->data->ID, $coAuthors ) ) {
					echo '<label  style="display:block;margin-bottom:3px" for="'.$user->data->ID.'" ><input data-rtcname="'.$user->data->user_email.' '.$user->data->user_nicename.'" checked type="checkbox" name="rtcc-authors[]" id="'.$user->data->ID.'" form="post" value= "'.$user->data->ID.'">'.$user->data->user_nicename.' ('.$user->data->user_email.')</label>';		
				} else {
					echo '<label style="display:block;margin-bottom:3px" for="'.$user->data->ID.'" ><input data-rtcname="'.$user->data->user_email.' '.$user->data->user_nicename.'" type="checkbox" name="rtcc-authors[]" id="'.$user->data->ID.'" form="post" value= "'.$user->data->ID.'">'.$user->data->user_nicename.' ('.$user->data->user_email.')</label>';
				}
			}

	
			
		}
		echo '</div>';

		echo '
			<script>

				var all = document.getElementsByClassName("contributors-metabox")[0].childNodes;
				

				function searchAuthor(val) {
					for(var i=0;i<all.length;i++) {
						var tmp = all[i].childNodes[0].getAttribute("data-rtcname");
						if( tmp.indexOf( val ) !== -1 ){
							all[i].style = "display:block";
							console.log(all[i].childNodes[0]);
						}else{
							all[i].style = "display:none";
							console.log(all[i].childNodes[0]);
						}
					}
				}


			</script>
		';

	}


	/**
	 * This function saves the authors name to the database provided in the 		 * contributors metabox
	 * @since    1.0.0
	 */
	function rtcc_add_contributors_save_post_callback( $post_id, $post, $update ) {
		
		global $wpdb, $post;
	

		if(!isset($_REQUEST['auhidden'])) return;

		if( sizeof($_REQUEST['rtcc-authors']) == 0 ) {
			delete_post_meta( $post_id, 'rtcc_coauthors');
			return;
		}
		
		$authorIds = $_REQUEST['rtcc-authors'];
		$authorIds = implode(',',$authorIds);
		delete_post_meta( $post->ID, 'rtcc_coauthors' );
		add_post_meta( $post->ID, 'rtcc_coauthors', $authorIds, true );

	}

	
	
	/**
	 * Shortcode to show coauthors in the post
	 * Is not used anymore
	 * This the the markup function for the coauthors shortcode
	 *
	 * @since    1.0.0
	 */
	function rtcc_coauthors_shortcode( $atts = [], $content = null, $tags = [] ) {
		
		global $post, $wpdb;
		ob_start();

		

		$coAuthors = get_post_meta( $post->ID, 'rtcc_coauthors', true );
		$coAuthors = explode( ',', $coAuthors );

		?>  <script type="text/javascript"> <?php

		echo '
			var div = document.createElement("div");
			div.id = "co-authors-list";
			div.innerHTML = "Co-Authors: ";
			div.style = "font-size:.8em";
			jQuery(".entry-meta").after(div);
		';

		foreach ($coAuthors as $coAuthor) {

			$author = get_userdata( $coAuthor );

			echo '<div style="display:inline-block">
					<a href="'.get_author_posts_url($author->data->ID).'">
			';

		}

		?> </script> <?php

		
	}

	function rtcc_add_column_header_author( $default ) {
		
			$new_default = array( 'custom_author' => __('Author') );	
		
		return array_merge( $default, $new_default);
	}

	/**
	 * This function adds all contrbuting authors to the post list table
	 * @since    1.0.0
	 */
	function rtcc_add_content_to_author_column( $column_name, $post_id  ) {
		global $wpdb;
		if( $column_name == 'custom_author' ) {
			$results = $wpdb->get_results("SELECT post_author from wp_posts where ID = ".$post_id);

			$o = $results[0]->post_author;

			$o = get_userdata( $o )->user_nicename;
			echo $o;
			$o ='';

			$coAuthors = get_post_meta( $post_id, 'rtcc_coauthors', true );
			if($coAuthors=="") return;
			$coAuthors = explode( ',', $coAuthors );
			
			foreach ($coAuthors as $coAuthor) {
				$o .= ', '.get_userdata( $coAuthor )->user_nicename;	
			}

			// echo substr($o,0,$o.length-1);
			echo $o;
		}

	}

	/**
	 * This function removes the actual authors post list column.
	 * @since    1.0.0
	 */
	function rtcc_remove_authors_column( $columns ) {
		unset($columns['author']);
		return $columns;
	}
	



}
