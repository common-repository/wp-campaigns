<?php

if ( ! class_exists( 'WDS_Taxonomy_Radio' ) ) {
	/**
	 * Removes and replaces the built-in taxonomy metabox with our radio-select metabox.
	 * @link  http://codex.wordpress.org/Function_Reference/add_meta_box#Parameters
	 */
	class WDS_Taxonomy_Radio {

		// Post types where metabox should be replaced (defaults to all post_types associated with taxonomy)
		public $post_types = array( '' );
		// Taxonomy slug
		public $slug = '';
		// Taxonomy object
		public $taxonomy = false;
		// New metabox title. Defaults to Taxonomy name
		public $metabox_title = '';
		// Metabox priority. (vertical placement)
		// 'high', 'core', 'default' or 'low'
		public $priority = 'high';
		// Metabox position. (column placement)
		// 'normal', 'advanced', or 'side'
		public $context = 'side';
		// Set to true to hide "None" option & force a term selection
		public $force_selection = false;


		/**
		 * Initiates our metabox action
		 *
		 * @param string $tax_slug Taxonomy slug
		 * @param array $post_types post-types to display custom metabox
		 */
		public function __construct( $tax_slug, $post_types = array() ) {

			$this->slug       = $tax_slug;
			$this->post_types = is_array( $post_types ) ? $post_types : array( $post_types );

			add_action( 'add_meta_boxes', array( $this, 'add_radio_box' ) );
		}

		/**
		 * Removes and replaces the built-in taxonomy metabox with our own.
		 */
		public function add_radio_box() {
			foreach ( $this->post_types() as $key => $cpt ) {
				// remove default category type metabox
				remove_meta_box( $this->slug . 'div', $cpt, 'side' );
				// remove default tag type metabox
				remove_meta_box( 'tagsdiv-' . $this->slug, $cpt, 'side' );
				// add our custom radio box
				add_meta_box( $this->slug . '_radio', $this->metabox_title(), array(
					$this,
					'radio_box'
				), $cpt, $this->context, $this->priority );
			}
		}

		/**
		 * Displays our taxonomy radio box metabox
		 */
		public function radio_box() {

			// uses same noncename as default box so no save_post hook needed
			wp_nonce_field( 'taxonomy_' . $this->slug, 'taxonomy_noncename' );

			// get terms associated with this post
			$names = wp_get_object_terms( get_the_ID(), $this->slug );
			// get all terms in this taxonomy
			$terms = (array) get_terms( $this->slug, 'hide_empty=0' );
			// filter the ids out of the terms
			$existing = ( ! is_wp_error( $names ) && ! empty( $names ) )
				? (array) wp_list_pluck( $names, 'term_id' )
				: array();
			// Check if taxonomy is hierarchical
			// Terms are saved differently between types
			$h = $this->taxonomy()->hierarchical;

			// default value
			$default_val = $h ? 0 : '';
			// input name
			$name = $h ? 'tax_input[' . $this->slug . '][]' : 'tax_input[' . $this->slug . ']';

			echo '<div style="margin-bottom: 5px;">
         <ul id="' . $this->slug . '_taxradiolist" data-wp-lists="list:' . $this->slug . '_tax" class="categorychecklist form-no-clear">';

			// If 'category,' force a selection, or force_selection is true
			if ( $this->slug != 'category' && ! $this->force_selection ) {
				// our radio for selecting none
				echo '<li id="' . $this->slug . '_tax-0"><label><input value="' . $default_val . '" type="radio" name="' . $name . '" id="in-' . $this->slug . '_tax-0" ';
				checked( empty( $existing ) );
				echo '> ' . sprintf( __( 'No %s', 'wds' ), $this->taxonomy()->labels->singular_name ) . '</label></li>';
			}

			// loop our terms and check if they're associated with this post
			foreach ( $terms as $term ) {

				$val = $h ? $term->term_id : $term->slug;

				echo '<li id="' . $this->slug . '_tax-' . $term->term_id . '"><label><input value="' . $val . '" type="radio" name="' . $name . '" id="in-' . $this->slug . '_tax-' . $term->term_id . '" ';
				// if so, they get "checked"
				checked( ! empty( $existing ) && in_array( $term->term_id, $existing ) );
				echo '> ' . $term->name . '</label></li>';
			}
			echo '</ul></div>';

		}

		/**
		 * Gets the taxonomy object from the slug
		 * @return object Taxonomy object
		 */
		public function taxonomy() {
			$this->taxonomy = $this->taxonomy ? $this->taxonomy : get_taxonomy( $this->slug );

			return $this->taxonomy;
		}

		/**
		 * Gets the taxonomy's associated post_types
		 * @return array Taxonomy's associated post_types
		 */
		public function post_types() {
			$this->post_types = ! empty( $this->post_types ) ? $this->post_types : $this->taxonomy()->object_type;

			return $this->post_types;
		}

		/**
		 * Gets the metabox title from the taxonomy object's labels (or uses the passed in title)
		 * @return string Metabox title
		 */
		public function metabox_title() {
			$this->metabox_title = ! empty( $this->metabox_title ) ? $this->metabox_title : $this->taxonomy()->labels->name;

			return $this->metabox_title;
		}


	}

	$custom_tax_mb                  = new WDS_Taxonomy_Radio( 'wpex_campaign_categories' );
	$custom_tax_mb->priority        = 'low';
	$custom_tax_mb->force_selection = true;

}


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WpexCampaigns
 * @subpackage WpexCampaigns/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    WpexCampaigns
 * @subpackage WpexCampaigns/includes
 * @author     Your Name <email@example.com>
 */
class WpexCampaigns {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      WpexCampaigns_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_CAMPAIGNS_VERSION' ) ) {
			$this->version = WP_CAMPAIGNS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wp-campaigns';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - WpexCampaigns_Loader. Orchestrates the hooks of the plugin.
	 * - WpexCampaigns_i18n. Defines internationalization functionality.
	 * - WpexCampaigns_Admin. Defines all hooks for the admin area.
	 * - WpexCampaigns_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpex-campaigns-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpex-campaigns-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpex-campaigns-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpex-campaigns-public.php';

		$this->loader = new WpexCampaigns_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the WpexCampaigns_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new WpexCampaigns_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WpexCampaigns_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		$this->loader->add_action( 'init', $plugin_admin, 'register_custom_post_type' );
		$this->loader->add_action( 'load-post.php', $plugin_admin, 'render_react_app' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'render_switch_mode_button' );

		$this->loader->add_filter( 'init', $plugin_admin, 'register_campaign_taxonomy' );
		$this->loader->add_filter( 'post_row_actions', $plugin_admin, 'add_edit_link', 10, 2 );
//        $this->loader->add_action( 'manage_wp-campaigns_posts_custom_column', $plugin_admin, 'output_table_columns_data', 10, 2 );
//        $this->loader->add_filter( 'manage_edit-wp-campaigns_columns', $plugin_admin, 'set_table_columns');
//        $this->loader->add_filter( 'manage_edit-wp-campaigns_columns', $plugin_admin, 'reorder_column');

		$this->loader->add_action( 'wp_ajax_wpex_get_short_code', $plugin_admin, 'get_short_code', 10, 1 );

		// API
		$this->loader->add_action( 'wp_ajax_wpex_save_campaign', $plugin_admin, 'wpex_save_campaign', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wpex_get_campaign', $plugin_admin, 'get_campaign', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wpex_get_mailchimp_lists', $plugin_admin, 'get_mailchimp_lists', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wpex_save_mail_provider_settings', $plugin_admin, 'wpex_save_mail_provider_settings', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wpex_search_post_by_title', $plugin_admin, 'search_post_by_title', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wpex_get_mailchimp_config', $plugin_admin, 'get_mailchimp_config', 10, 1 );
		$this->loader->add_action( 'wp_ajax_wpex_get_locale', $plugin_admin, 'get_locale', 10, 1 ); // en_US / he_IL
		$this->loader->add_action( 'wp_ajax_wpex_get_menu_items', $plugin_admin, 'wpex_get_menu_items', 10, 1 ); // en_US / he_IL
		$this->loader->add_action( 'wp_ajax_wpex_send_campaign_test', $plugin_admin, 'send_campaign_test', 10, 1 );

		if(DEVELOPMENT_MODE){
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_get_menu_items', $plugin_admin, 'wpex_get_menu_items', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_save_campaign', $plugin_admin, 'wpex_save_campaign', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_get_campaign', $plugin_admin, 'get_campaign', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_get_mailchimp_lists', $plugin_admin, 'get_mailchimp_lists', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_save_mail_provider_settings', $plugin_admin, 'wpex_save_mail_provider_settings', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_send_campaign', $plugin_admin, 'send_campaign', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_send_campaign_test', $plugin_admin, 'send_campaign_test', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_search_post_by_title', $plugin_admin, 'search_post_by_title', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_get_mailchimp_config', $plugin_admin, 'get_mailchimp_config', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_get_locale', $plugin_admin, 'get_locale', 10, 1 );
			$this->loader->add_action( 'wp_ajax_nopriv_wpex_get_short_code', $plugin_admin, 'get_short_code', 10, 1 );
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new WpexCampaigns_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    WpexCampaigns_Loader    Orchestrates the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

}



