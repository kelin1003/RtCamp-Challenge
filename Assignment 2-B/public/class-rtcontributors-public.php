<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       sociallyawkward.in
 * @since      1.0.0
 *
 * @package    Rtcontributors
 * @subpackage Rtcontributors/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rtcontributors
 * @subpackage Rtcontributors/public
 * @author     Kelin Chauhan <kelin1003@gmail.com>
 */
class Rtcontributors_Public {

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
		 * defined in Rtcontributors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rtcontributors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/rtcontributors-public.css', array(), $this->version, 'all' );

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
		 * defined in Rtcontributors_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Rtcontributors_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/rtcontributors-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Register the_content filter to add the contributors to the post
	 *
	 * @since    1.0.0
	 */
	public function rtcc_add_coauthors_to_post_public( $content ) {

		global $wpdb, $post;



		ob_start();

		$coAuthors = get_post_meta( $post->ID, 'rtcc_coauthors', true );
		if(!$coAuthors) return $content;

		$coAuthors = explode( ',', $coAuthors );
		


		echo '<br><br>
			<div class="custm">
			Contributors:<hr>
		';


		foreach ($coAuthors as $coAuthor) {

			$author = get_userdata( $coAuthor );

			echo '
				
				<div style="display:inline-block;margin-right:10px">
					<a class="rtcc-author-link" href="'.get_author_posts_url($author->data->ID).'">
						<div class="author-avatar">
							'.get_avatar( $author->data->ID ).'
						</div>
					<center>'.explode(' ',$author->data->display_name)[0].'<center>
					</a>
				</div>
				
					
			';
			

		}

		echo '</div>';
		
		
		return $content.ob_get_clean();

	}


}
