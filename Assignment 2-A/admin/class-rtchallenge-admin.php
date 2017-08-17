<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       sociallyawkward.in
 * @since      1.0.0
 *
 * @package    Rtchallenge
 * @subpackage Rtchallenge/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * 
 *
 * @package    Rtchallenge
 * @subpackage Rtchallenge/admin
 * @author     Kelin Chauhan <kelin1003@gmail.com>
 */


class Rtchallenge_Admin {
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

		
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rtchallenge-admin.css', array(), $this->version, 'all' );

	}



	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		global $pagenow, $typenow;

		
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( 'clipboardjs', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array( 'jquery' ), $this->version, true );
		
		?> 
		<script type="text/javascript">console.log('scrips loaded')</script>
		<?php

		
		


	}

	/**
	 * To display the admin side page to upload images
	 *
	 * @since    1.0.0
	 */
	public function rtc_display_admin_page() {

		add_menu_page(
			'Upload Images', //page title
			'Upload Images', //menu title
			'manage_options', //capability
			'upload-images-admin', //slug
			array($this, 'rtc_showPage') //display function
			
		);

	}

	/**
	 * Helper function that loads the admin page to display
	 *
	 * @since    1.0.0
	 */
	public function rtc_showPage() {

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/rtchallenge-admin-display.php';


	}

	/**
	 * Function that adds image upload page
	 *
	 * @since    1.0.0
	 */
	public function rtc_register_upload_images(){

		$singular = 'Upload Image';
		$plural = 'Upload Images';

		$labels = array(
			'name' => $plural,
			'singular_name' => $singular,

		);

		$args = array(
			'labels' => $labels,
			'public' => true,
			'capability_type' => 'post',
			'supports' => array( 'title', 'thumbnail', '' ),
			'menu_icon' => 'dashicons-format-gallery'

		);

		register_post_type('rtc_ptype_Image',$args);

	}

	public function rtc_remove_unused_metaboxes(){
		remove_meta_box( 'postcustom' , 'post' , 'normal' ); 
	}

	

	/**
	 * Function that adds custom meta box to upload images
	 *
	 * @since    1.0.0
	 */
	function rtc_add_custom_meta_box()
	{
	    add_meta_box("rtc-upload-images-meta", "Upload Images", array($this,"rtc_custom_meta_box_markup") ,"rtc_ptype_Image", "normal", "high", null);

	    add_meta_box("rtc-show-shortcode-meta", "Shortcode", array($this,"rtc_show_shortcode_meta_markup") ,"rtc_ptype_Image", "normal", "low", null);

		

	}

	/**
	 * Function that provides metabox markup above defined metabox.
	 *
	 * @since    1.0.0
	 */
	function rtc_custom_meta_box_markup() {
		wp_enqueue_script( 'rtchallenge-admin.js', plugin_dir_url( __FILE__ ) . 'js/rtchallenge-admin.js', array( 'jquery' ), $this->version, true );
		

			?>
			
			  
			  <input id="upload-button" type="button" class="button" value="Upload Image" />
			  
			  <input id = "images-hidden" name="images-hidden" form = "post" type = "hidden" />
			
				<ul id="sortable">
			<?php

			if(isset($_REQUEST['post'])){


			$args = array(
				'p' => $_REQUEST['post'],
				'post_type' => 'rtc_ptype_image',
			);

				$q = new WP_Query( $args );
				if($q->have_posts()){
					while( $q->have_posts() ){
						
						$q->the_post();
						$temp = get_the_content();
						echo $temp;

					}
				}
					
				
		    }
		    ?></ul><?php
		

	}

	
	/**
	 * Function that provides metabox markup for displaying shortcode to copy
	 *
	 * @since    1.0.0
	 */
	function rtc_show_shortcode_meta_markup() {
		global $post;
		$post_id = $post->ID;
		?>
			<button style="cursor: pointer" onclick="copyToClipboard('#rt_<?php echo $post_id; ?>')"><p id="rt_<?php echo $post_id; ?>">[rtc_challenge_a id="<?php echo $post_id; ?>"]</p></button>

				<script type="text/javascript">
				var $ = jQuery;
					function copyToClipboard(element) {
				  var $temp = $("<input>");
				  $("body").append($temp);
				  
				  $temp.val($(element).text()).select();
				  document.execCommand("copy");
				  
				  $temp.remove();

				  event.preventDefault();
				}
				</script>
			<?php
	}

	/**
	 * Function that handles the image ordering.
	 *
	 * @since    1.0.0
	 */
	function rtc_handle_image_order() {
		
		global $wpdb;

		$newOrder = $_REQUEST['order'];
		$post_id = $_REQUEST['post_id'];
		$post_title = $_REQUEST['post_title'];
		$post_status = $_REQUEST['post_status'];
		$post_type = 'rtc_ptype_image';
	

		$updatedPost = array(
			'post_title' => $post_title,
			'post_content' => $newOrder,
			'post_type' => $post_type,
			'post_status' => $post_status
		);

		if ( empty( $post_id ) ) {
			//inserting

			//check if it already exists
			$result = $wpdb->get_results( "select * from $wpdb->posts where post_type = 'rtc_ptype_image' and post_status = 'draft' and post_title = '$post_title' " );
			

			if ( !empty( $result ) ) {
				
				$updatedPost['ID'] = $result[0]->ID; 
			}

			wp_insert_post( $updatedPost );

		}else{
			$updatedPost['ID'] = $post_id;
			wp_update_post( $updatedPost );
		}

	}

	/**
	 * Function that provides markup for the slider shortcode.
	 * Shows slide show to the user
	 *
	 * @since    1.0.0
	 */
	function rtc_slider_shortcode( $atts = [], $content = null, $tags = [] ) {

		global $wpdb;

		ob_start();

		 // normalize attribute keys, lowercase
	    $atts = array_change_key_case((array)$atts, CASE_LOWER);
	 
	    // override default attributes with user attributes
	    $rtc_atts = shortcode_atts([
	                                     'id' => '',
	                                 ], $atts, $tag);

	    // when no id is provided return
	    if($rtc_atts['id']=='') return;
	 
	    // start output
	    echo '<div class="slideshow-container">';
	    $post_content;
	 
		$result = $wpdb->get_results( "select post_content from $wpdb->posts where id = ".$rtc_atts['id'] );

		if ( ! empty( $result ) ) {
			$post_content = $result[0]->post_content;
		}


		// when no such post exist return
		if(empty($post_content)) return;

	    
	    $arr = explode('<li ',$post_content);
	    
	    // var_dump($arr);
	    $counter = 0;
	    foreach ($arr as $value) {
	    	if(empty(trim($value))) continue;

	    	$temp = explode('<img ',$value);

	    	

	    	echo '<div class="mySlides fade"><div class="numbertext">  '.++$counter.' / '.(sizeof($arr)-1).'</div><img '.$temp[1].'<div class="text"></div></div>';
	    }

	    echo  '<a class="prev" onclick="plusSlides(-1)">&#10094;</a><a class="next" onclick="plusSlides(1)">&#10095;</a>';

	    echo '</div><br><div style="text-align:center">';
	    $counter = 1;
	    
	    foreach ($arr as $value) {
	    	if(empty(trim($value))) continue;
	    	echo '<span class="dot" onclick="currentSlide('.$counter++.')"></span>';
	    }

	    echo '</div>';



	    $o = ob_get_contents(); 

		    ob_end_clean();
	    // filtering
	    return do_shortcode( $o );
	}


	/**
	 * This function is a helper function to add the images to the
	 * post content in the database when post is published or saved
	 *
	 * @since    1.0.0
	 */
	function rtc_add_images_to_content( $post_id, $post, $update ) {
		global $wpdb;

			$images = array_map( 'stripslashes_deep', $_REQUEST);


		
		if ( !empty($images['images-hidden']) && $images['images-hidden'] != "" ) {
			$results = $wpdb->update( "wp_posts", [ "post_content" => $images['images-hidden'] ], [ "ID" => $post_id ] );
		}

		// var_dump( $results );

	}


	

	/**
	 * This function adds a new columne named 'Shortcode' to the post list table.
	 * To easily provide the shortcode.
	 *
	 * @since    1.0.0
	 */
	function rtc_add_column_header( $default ) {
		global $typenow;
		
		$new_default = [];
		if( $typenow == 'rtc_ptype_image' ){
			$new_default = ['shortcode' => 'Shortcode'];	
		}
		
		return array_merge( $default, $new_default );
	}

	/**
	 * This function adds shortcode to the newly created columm.
	 * To easily provide the shortcode.
	 *
	 * @since    1.0.0
	 */
	function rtc_add_column_content( $column_name, $post_id ) {
		global $typenow;
		// var_dump( $typenow );
		
		if( $column_name == 'shortcode' ) {
			?>
				<button style="cursor: pointer" onclick="copyToClipboard('#rt_<?php echo $post_id; ?>')"><p id="rt_<?php echo $post_id; ?>">[rtc_challenge_a id="<?php echo $post_id; ?>"]</p></button>

				<script type="text/javascript">
				var $ = jQuery;
					function copyToClipboard(element) {
				  var $temp = $("<input>");
				  $("body").append($temp);
				  
				  $temp.val($(element).text()).select();
				  document.execCommand("copy");
				  
				  $temp.remove();

				  event.preventDefault();
				}
				</script>
			<?php
			
		}

		
	}




}


