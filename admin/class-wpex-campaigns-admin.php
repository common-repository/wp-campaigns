<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WpexCampaigns
 * @subpackage WpexCampaigns/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    WpexCampaigns
 * @subpackage WpexCampaigns/admin
 * @author     Your Name <email@example.com>
 */
class WpexCampaigns_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     *
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WpexCampaigns_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WpexCampaigns_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/wpex-campaigns-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in WpexCampaigns_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WpexCampaigns_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/wpex-campaigns-admin.js', array('jquery'), $this->version, false);

    }

    public function register_custom_post_type()
    {
        register_post_type($this->plugin_name, array(
            'labels' => array(
                'name' => __('wp campaigns', $this->plugin_name),
                'singular_name' => __('campaign', $this->plugin_name),
                'menu_name' => __('Campaigns', $this->plugin_name),
                'name_admin_bar' => __('campaign', $this->plugin_name),
                'add_new' => __('Add New campaign', $this->plugin_name),
                'add_new_item' => __('Add New campaign', $this->plugin_name),
                'new_item' => __('New campaign'),
                'edit_item' => __('Edit campaign', $this->plugin_name),
                'view_item' => __('View campaign', $this->plugin_name),
                'all_items' => __('All campaigns', $this->plugin_name),
                'search_items' => __('Search campaigns', $this->plugin_name),
                'parent_item_colon' => __('Parent campaigns:', $this->plugin_name),
                'not_found' => __('No campaigns found.', $this->plugin_name),
                'not_found_in_trash' => __('No campaigns found in Trash.', $this->plugin_name),
            ),

            // Frontend
            'has_archive' => false,
            'public' => true,
            'publicly_queryable' => false,
            // Admin
            'capability_type' => 'post',
            'menu_icon' => plugins_url('/images/wp-campaigns-envelope.png', __FILE__), //'dashicons-businessman',
            'menu_position' => 10,
            'query_var' => true,
            'show_in_menu' => true,
            'show_ui' => true,
            'rewrite' => array('slug' => $this->plugin_name, 'with_front' => false),
            'supports' => array(
                'title',
                'author' => false,
                'comments' => false,
                'revisions' => true,
            ),
            'can_export' => true,
            'taxonomies' => array('wp_campaign_categories'),

        ));
    }

    public function register_campaign_taxonomy()
    {
        $labels = array(
            'name' => __('Campaign\'s status', $this->plugin_name),
            'singular_name' => __('Campaign\'s status', $this->plugin_name),
            'search_items' => __('Search Campaign\'s statuses', $this->plugin_name),
            'all_items' => __('All Campaign\'s statuses', $this->plugin_name),
            'parent_item' => __('Parent Campaign\'s status', $this->plugin_name),
            'parent_item_colon' => __('Parent Campaign\'s status:', $this->plugin_name),
            'edit_item' => __('Edit Campaign\'s status', $this->plugin_name),
            'update_item' => __('Update Campaign\'s status', $this->plugin_name),
            'add_new_item' => __('Add New Campaign\'s statuses', $this->plugin_name),
            'new_item_name' => __('New Campaign\'s statuses', $this->plugin_name),
            'menu_name' => __('Campaign\'s statuses', $this->plugin_name),
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => $this->plugin_name, 'with_front' => false),
            'show_count' => true, // Show # listings in parens
        );

        register_taxonomy('wpex_campaign_categories', $this->plugin_name, $args);
    }

    public function action_load_edit_post()
    {
        ?>
        <script>console.log('load-edit.php');</script>
        <?php

    }

    public function action_load_post()
    {
        ?>
        <script>console.log('load-post.php');</script>
        <?php

    }

    public function render_react_app()
    {

        global $pagenow;
        if (is_admin() && 'post.php' == $pagenow && isset($_GET['action']) && WP_CAMPAIGNS_PLUGIN_NAME === $_GET['action']):
            require_once 'partials/campaign-app.php';
            die();
        endif;
    }

    public function add_edit_link($actions, WP_Post $post)
    {
        if ('wp-campaigns' !== $post->post_type) {
            return $actions;
        }

        $actions['Edit wp campaigns'] = "<a href='". esc_html(admin_url()) ."post.php?post=" . esc_html($post->ID) . "&action=wp-campaigns'>Edit with wpcampaigns</a>";

        return $actions;
    }

    public function reorder_column($columns)
    {
        $date = $columns['date'];
        unset( $columns['date'] );
        $columns['date'] = $date;

        return $columns;
    }

    public function set_table_columns($columns)
    {
        $columns['mailchimp_link'] = __('Edit in Mailchimp', $this->plugin_name);

        return $columns;
    }

    public function output_table_columns_data($columnName, $post_id)
    {
        if ( 'mailchimp_link' == $columnName) {
            $campaign = $this->get_campaign($post_id, false);
            $wpex_m_p_s = $this->getMailProviderSettings();

            if (empty($wpex_m_p_s->private_key) || $campaign === null || $campaign->row->mailchimp_web_id === '') {
                echo '----';
            }

            $dc = explode('-', $wpex_m_p_s->private_key)[1];
            $web_id = $campaign->row->mailchimp_web_id;
            echo "<a href='https://" . $dc . ".admin.mailchimp.com/campaigns/show/?id=" . $web_id . "' target='_blank'>" . $web_id . "</a>";
        }

    }

    public function add_plugin_admin_menu()
    {
        add_submenu_page(
            'edit.php?post_type=wp-campaigns',
            $this->plugin_name,
            __('Integrations', $this->plugin_name),
            'manage_options',
            'wp_campaigns_integrations',
            array(
                $this,
                'render_campaigns_integrations'
            ));
    }

    public function render_admin()
    {
        ?>
        <h1>main page</h1>
        <?php
    }


    public function render_campaigns_sent()
    {
        ?>
        <h1>render_campaigns_sent</h1>
        <?php
    }

    public function render_campaigns_integrations()
    {
        $wpex_m_p_s = get_option('wpex_m_p_s', array());
        require_once 'partials/integrations.php';
    }

    public function wpex_save_mail_provider_settings()
    {
        if ((isset($_POST['wpex_mail_provider_private_key']))) {
            $wpex_mail_provider_private_key = sanitize_text_field($_POST['wpex_mail_provider_private_key']);
            $wpex_m_p_s = array(
                'private_key' => $wpex_mail_provider_private_key
            );
            update_option('wpex_m_p_s', json_decode(json_encode($wpex_m_p_s)));
            wp_die(json_encode(array('success' => true)));
        }
    }

    public function render_switch_mode_button()
    {
        global $post;

        if ($post->post_type !== $this->plugin_name) {
            return;
        }
        ?>
        <style>
            .wrapper-edit-button {
                padding: 100px;
                background: #f7f7f7;
                border: 1px solid #dedede;
                text-align: center;
            }
        </style>
        <div class="wrapper-edit-button">
            <a href='<?php echo admin_url();?>post.php?post=<?php echo $post->ID; ?>&action=wp-campaigns' class="button-primary">Edit
                with
                wp campaigns</a>
        </div>


        <?php

    }

    // #todo add validation before save
    public function wpex_save_campaign()
    {

        global $wpdb;

        $errors = array();
        $campaign = isset($_REQUEST['campaign']) ? $_REQUEST['campaign'] : null;

        $this->validateCampaign($campaign);

        $post_id = isset($campaign) ? sanitize_text_field($campaign['post_id']) : null;
        $status = isset($campaign['status']) ? sanitize_text_field($campaign['status']) : null;
        $name = isset($campaign['name']) ? sanitize_text_field($campaign['name']) : null;

        if ($campaign !== null && $post_id !== null && $status !== null && $name !== null) {
            $json = json_encode($campaign);
            $saveCampaignResponse = $this->saveCampaign($campaign);

            if ($saveCampaignResponse) {
                $json = $this->decode($json);
            } else {
                $errors = $saveCampaignResponse;
            }

            if ($wpdb->insert_id) {
                $json->id = $wpdb->insert_id;
            }

            $post = array(
                'ID' => $post_id,
                'post_title' => $name,
            );

            wp_update_post($post);

            wp_die(json_encode(array(
                'errors' => $errors,
                'campaign' => $json,
            )));
        }
    }

    private function isString($string)
    {
        return !preg_match('/[^A-Za-z0-9]/', $string);
    }

    private function validateCampaign($campaign = null, $die = true)
    {
        $errors = array();


        if (!is_object($campaign)) {
            $errors[] = 'Invalid campaign';
        }

        if (!empty($campaign->id) && !is_numeric($campaign->id)) {
            $errors[] = 'Invalid campaign id';
        }

        if (!empty($campaign->post_id) && !is_numeric($campaign->post_id)) {
            $errors[] = 'Invalid post id';
        }

        if (!empty($campaign->mailchimpWebId) && !is_numeric($campaign->mailchimpWebId)) {
            $errors[] = 'Invalid mailchimp id';
        }

        if (!empty($campaign->status) && !$this->isString($campaign->status)) {
            $errors[] = 'Invalid mailchimp id';
        }

        if (!empty($campaign->mailServiceFields) && !is_object($campaign->mailServiceFields)) {
            $errors[] = 'Invalid mail service fields';
        }

        if (!empty($campaign->mailServiceFields->subjectLine) && !$this->isString($campaign->mailServiceFields->subjectLine)) {
            $errors[] = 'Invalid subjectLine';
        }

        if (!empty($campaign->mailServiceFields->from) && !$this->isString($campaign->mailServiceFields->from)) {
            $errors[] = 'Invalid from';
        }

        if (!empty($campaign->mailServiceFields->replayTo) && !filter_var($campaign->mailServiceFields->replayTo, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid replayTo';
        }

        if (!empty($campaign->mailServiceFields->title) && !$this->isString($campaign->mailServiceFields->title)) {
            $errors[] = 'Invalid title';
        }

        if (!empty($campaign->mailchimpCampaignId) && !$this->isString($campaign->mailchimpCampaignId)) {
            $errors[] = 'Invalid mailchimp campaign id';
        }

        if (!empty($campaign->mailchimpListId) && !$this->isString($campaign->mailchimpListId)) {
            $errors[] = 'Invalid mailchimp list id';
        }


        return $errors;
    }

    private function saveCampaign($campaign)
    {
        global $wpdb;

        $errors = array();

        try {
            if (isset($campaign['template']) && $campaign['template']['html']) {
                unset($campaign['template']['html']);
            }
            $id = sanitize_text_field($campaign['id']);
            $post_id = sanitize_text_field($campaign['post_id']);
            $status = sanitize_text_field($campaign['status']);
            $mailchimp_web_id = sanitize_text_field($campaign['mailchimpWebId']);
            $json = sanitize_text_field(wp_json_encode($campaign));
            $sql = "INSERT INTO {$wpdb->prefix}wpex_campaigns (`id`,`post_id`,`json`,`status`,`mailchimp_web_id`) VALUES (%d,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE `id`=%s,`post_id`=%s,`json`=%s,`status`=%s,`mailchimp_web_id`=%s";
            $sql = $wpdb->prepare($sql, $id, $post_id, $json, $status, $mailchimp_web_id, $id, $post_id, $json, $status, $mailchimp_web_id);
            $wpdb->query($sql);

        } catch (Exception $exception) {
            $errors[] = $exception;
        }

        return count($errors) === 0 ? true : $errors;
    }

    public function get_campaign($post_id = '', $die = true)
    {

        global $wpdb;

        $table = $wpdb->prefix . 'wpex_campaigns';
        if ($post_id === '') {
            $post_id = isset($_REQUEST['post_id']) ? $_REQUEST['post_id'] : '';
        }

        if ($post_id !== '') {
            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$table} WHERE post_id = %s LIMIT 1", $post_id));
            $campaign = null;
            if (!empty($result)) {
                $campaign = $this->decode($result[0]->json);

                $campaign->id = $result[0]->id;
            }

            if (false === $die) {
                if (!empty($result)) {
                    $campaign->row = $result[0];
                }

                return $campaign;
            }

            wp_die(json_encode(array(
                'campaign' => $campaign,
            )));
        }
    }

    private function createOrUpdateMailchimpCampaign($mailchimp_fields, $list_id, $campaign_id = null)
    {

        $mailchimp = new MailChimp_3($this->getPrivateKey());
        $args = new stdClass();
        $args->type = 'regular';
        $args->tracking = new stdClass();
        $args->tracking->opens = true;
        $args->tracking->html_clicks = true;
        $args->tracking->text_clicks = true;
        $args->settings = new stdClass();
        $args->settings->subject_line = sanitize_text_field($mailchimp_fields['subjectLine']);
        $args->settings->from_name = sanitize_text_field($mailchimp_fields['from']);
        $args->settings->reply_to = sanitize_text_field($mailchimp_fields['replayTo']);
        $args->settings->title = sanitize_text_field($mailchimp_fields['title']);
        $args->settings->inline_css = true;
        $args->recipients = new stdClass();
        $args->recipients->list_id = $list_id;

        $method = $campaign_id === null ? 'post' : 'patch';

        return $mailchimp->$method("/campaigns/{$campaign_id}", $args);
    }

    private function getPrivateKey($throw = true)
    {
        // m_p_s -> mail provider settings
        $wpex_m_p_s = $this->getMailProviderSettings();

        if (empty($wpex_m_p_s->private_key) && $throw) {
            $error = new WP_Error('mailchimp', 'Invalid private key');
            wp_die(json_encode($error));
        }

        return $wpex_m_p_s->private_key;
    }

    private function getMailProviderSettings()
    {
        return get_option('wpex_m_p_s', new stdClass());
    }

    private function updateMailchimpCampaignHTML($campaign_id, $html)
    {
        $mailchimp = new MailChimp_3($this->getPrivateKey());
        $data = new stdClass();
        $html = urldecode_deep($html);

        $data->plain_text = $data->archive_html = $data->html = stripslashes($html);

        return $mailchimp->put('/campaigns/' . $campaign_id . '/content', $data);
    }

    private function sendMailchimpTest($campaign_id, $addresses)
    {

//		return;

        $mailchimp = new MailChimp_3($this->getPrivateKey());

        return $mailchimp->post('/campaigns/' . $campaign_id . '/actions/test', $addresses);
    }

    // #todo add validation inputs
    public function send_campaign_test()
    {
        $campaign = isset($_REQUEST['campaign']) ? $_REQUEST['campaign'] : null;

        $this->validateCampaign($campaign);

        $html = isset($campaign['template']['html']) ? $campaign['template']['html'] : null;

        $emails = isset($_REQUEST['emails']) ? $_REQUEST['emails'] : null;

        if (!is_array($emails)) {
            wp_die(json_encode(array(
                'errors' => array('Invalid emails list'),
            )));
        }

        foreach ($emails as &$email) {
            $email = sanitize_email($email);
        }

        $mailchimp_fields = isset($_REQUEST['campaign']['mailServiceFields']) ? $_REQUEST['campaign']['mailServiceFields'] : null;
        $mailchimp_campaign_id = isset($_REQUEST['campaign']['mailchimpCampaignId']) ? sanitize_text_field($_REQUEST['campaign']['mailchimpCampaignId']) : null;
        $mailchimp_list_id = isset($_REQUEST['campaign']['mailchimpListId']) ? sanitize_text_field($_REQUEST['campaign']['mailchimpListId']) : null;

        $errors = array();

        if ($campaign !== null && $mailchimp_fields !== null && $emails !== null && $html !== null && $mailchimp_list_id !== null) {
            if (empty($mailchimp_campaign_id)) {
                $newCampaign = $this->createOrUpdateMailchimpCampaign($mailchimp_fields, $mailchimp_list_id);
                $mailchimp_campaign_id = $newCampaign['id'];
                $campaign['mailchimpCampaignId'] = $mailchimp_campaign_id;
                $campaign['mailchimpWebId'] = $newCampaign['web_id'];
                $saveCampaignResponse = $this->saveCampaign($campaign);
                if (true !== $saveCampaignResponse) {
                    array_merge($errors, $saveCampaignResponse);
                }
            }

            $updateCampaignResponse = $this->updateMailchimpCampaignHTML($mailchimp_campaign_id, $html);
            $sendTestResponse = $this->sendMailchimpTest($mailchimp_campaign_id, array(
                    'test_emails' => $emails,
                    'send_type' => 'html'
                )
            );

            $campaign = $this->decode(json_encode($campaign));

            wp_die(json_encode(array(
                'errors' => $errors,
                'success_send_test' => $sendTestResponse === false,
                'campaign' => $campaign,
                'update_campaign_response' => $updateCampaignResponse,
                'send_test_response' => $sendTestResponse
            )));
        }
    }

    public function get_mailchimp_config()
    {
        wp_die(json_encode($this->getMailProviderSettings()));
    }

    public function send_campaign()
    {
        $response = new stdClass();

        if (isset($_REQUEST['campaign'])
            && isset($_REQUEST['campaign']['mailServiceFields'])
            && isset($_REQUEST['campaign']['mailchimpCampaignId'])
            && isset($_REQUEST['campaign']['mailchimpListId'])
            && isset($_REQUEST['campaign']['template']['html'])
        ) {

            $campaign = $_REQUEST['campaign'];

            $this->validateCampaign($campaign);

            $mailchimp_fields = $_REQUEST['campaign']['mailServiceFields'];
            $mailchimp_campaign_id = sanitize_text_field($_REQUEST['campaign']['mailchimpCampaignId']);
            $list_id = sanitize_text_field($_REQUEST['campaign']['mailchimpListId']);
            $updateCampaignInMailchimpResponse = $this->createOrUpdateMailchimpCampaign($mailchimp_fields, $list_id, $mailchimp_campaign_id);

            if (isset($updateCampaignInMailchimpResponse['status']) && $updateCampaignInMailchimpResponse['status'] === 404) {
                $response->error = $updateCampaignInMailchimpResponse['detail'];
                wp_die(json_encode($response));
            }

            $updateTemplateCampaignResponse = $this->updateMailchimpCampaignHTML($mailchimp_campaign_id, $campaign['template']['html']);
            $mailchimp = new MailChimp_3($this->getPrivateKey());
            $sendCampaignResponse = $mailchimp->post('/campaigns/' . $mailchimp_campaign_id . '/actions/send');

            if (false !== $sendCampaignResponse) {
                $response->error = $sendCampaignResponse['detail'];
                wp_die(json_encode($response));
            }

            $updateCampaignResponse = $this->saveCampaign($campaign);

            $response->updateCampaignInMailchimpResponse = $updateCampaignInMailchimpResponse;
            $response->updateTemplateCampaignResponse = $updateTemplateCampaignResponse;
            $response->updateCampaignResponse = $updateCampaignResponse;
            $response->send_response = $sendCampaignResponse;
        }

        wp_die(json_encode($response));
    }

    private function decode($campaign)
    {
        if (!$campaign) {
            return null;
        }

        $campaign = preg_replace('/(\"false\")/i', 'false', $campaign);
        $campaign = preg_replace('/(\"true\")/i', 'true', $campaign);
        $campaign = preg_replace('/\"([\d]+)\"/i', '$1', $campaign);

        return stripslashes_deep(json_decode(($campaign)));
    }

    public function get_mailchimp_lists()
    {
        $mailchimp = new MailChimp_3($this->getPrivateKey());
        $response = $mailchimp->get('/lists'); #todo can br return false

        wp_die(json_encode(array(
            'lists' => $response['lists'],
            'error' => array()
        )));
    }

    /**
     * GET wp-admin/admin-ajax.php
     * action = search_post_by_title
     * query = '{post title example}'
     */
    public function search_post_by_title()
    {
        global $wpdb;

        $query = isset($_REQUEST['query']) ? sanitize_text_field($_REQUEST['query']) : null;

        $a = preg_match('/^[a-z0-9\!\@\#\$\%\^\&\*\(\)\_\+\=]$/i', $query, $result);


        $posts = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->posts WHERE post_title LIKE '%s' AND `post_status`='publish' AND `post_type`='post' ORDER BY $wpdb->posts.`ID` DESC LIMIT 50", '%' . $wpdb->esc_like($query) . '%'));

        foreach ($posts as &$post) {

            // set post meta data
            $post_id = $post->ID;
            $result = new stdClass();
            $tags = wp_get_post_tags($post_id);
            $result->tags = $tags;
            if (isset($result->tags[0])) {
                $result->tag = $tags[0];
            }
            $result->image_src = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full');
            if (isset($result->image_src[0])) {
                $result->image_src = $result->image_src[0];
            }

            $result->cta = $this->getField($post->ID, '_cta_label');
            $result->snippet = $this->getField($post->ID, 'post_snippet');

            $post = array_merge((array)$post, (array)$result);
            //run your output code here
        }

        echo json_encode([
            'posts' => $posts,
            '$query' => $_REQUEST['query'],
        ]);
        wp_die();
    }

    public function get_locale()
    {
        wp_die(json_encode(array('locale' => get_locale())));
    }

    private function getField($postId, $fieldName)
    {
        $fields = get_post_meta($postId, $fieldName);

        return isset($fields[0]) ? $fields[0] : '';
    }

    public function wpex_get_menu_items()
    {
        $menuList = get_terms('nav_menu');
        $menuList = array_combine(wp_list_pluck($menuList, 'term_id'), wp_list_pluck($menuList, 'name'));
        $menus = array();
        foreach ($menuList as $term_id => $name) {
            $items = wp_get_nav_menu_items($term_id);
            $menu = array(
                'term_id' => $term_id,
                'name' => $name,
                'value' => $name,
                'label' => $name,
                'items' => array(),
            );

            foreach ($items as $item) {
                $menu['items'][] = array(
                    'ID' => $item->ID,
                    'title' => $item->title,
                    'guid' => $item->guid,
                );
            }
            $menus[] = $menu;
        }
        wp_die(json_encode($menus));
    }

    public function get_short_code(){
	    $response = new stdClass();
	    ob_start();
	    echo do_shortcode('[elementor-template id="56226"]');
	    echo "<link rel='stylesheet'
                    id='elementor-animations-css'
                    href='http://giladt/wp-content/plugins/elementor/assets/css/frontend.min.css?ver=2.5.16'
                    type='text/css'
                    media='all' />";
	    $response->html = ob_get_clean();
	    wp_die(json_encode($response));
    }

    //		$return = $wpdb->replace(
//			'wp_wpex_campaigns',
//			array(
//				'id'      => $id,
//				'json'    => json_encode( $campaign ),
//				'post_id' => $post_id,
//				'status'  => $status,
//			),
//			array(
//				'%d',
//				'%s',
//				'%d',
//				'%s',
//			)
//		);


//		if ( $id === null ) {
//			$table  = $wpdb->prefix . 'wpex_campaigns';
//			$data   = array(
//				'json'    => json_encode( $campaign ),
//				'status'  => $campaign['status'],
//				'post_id' => $post_id,
//			);
//			$format = array( '%s', '%s', '%d' );
//			$wpdb->insert( $table, $data, $format );
//		}
}
