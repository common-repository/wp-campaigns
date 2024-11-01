<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}


// Send MIME Type header like WP admin-header.
@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));

function get_locked_user($post_id)
{
    if (!function_exists('wp_check_post_lock')) {
        require_once ABSPATH . 'wp-admin/includes/post.php';
    }

    $locked_user = wp_check_post_lock($post_id);
    if (!$locked_user) {
        return false;
    }

    return get_user_by('id', $locked_user);
}

function lock_post($post_id)
{
    if (!function_exists('wp_set_post_lock')) {
        require_once ABSPATH . 'wp-admin/includes/post.php';
    }

    wp_set_post_lock($post_id);
}

if (empty($_REQUEST['post'])) {
    return;
}
$post_id = absint($_REQUEST['post']);

$GLOBALS['post'] = get_post($post_id);

setup_postdata($GLOBALS['post']);

$locked_user = get_locked_user($post_id);
if (!$locked_user) {
    lock_post($post_id);
}


remove_all_actions('wp_enqueue_scripts');
remove_all_actions('after_wp_tiny_mce');
remove_all_actions('wp_print_styles');
remove_all_actions('wp_print_head_scripts');
wp_enqueue_media();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?php echo get_the_title(); ?></title>
    <?php wp_head(); ?>
    <script>
        var ajaxurl = '<?php echo admin_url('admin-ajax.php', 'relative'); ?>';
    </script>
</head>
<body width="100%" height="100%">
<div class="site-branding"></div>
<style>
    body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
    }

    body, html, iframe {
        width: 100%;
        height: 100%;
        margin: 0 !important;
        padding: 0
    }

    #wpadminbar {
        display: none
    }

    iframe {
        border: none
    }

    .back-to-admin {
        display: -webkit-box;
        display: -webkit-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        position: fixed;
        top: 50px;
        right: 50px;
        z-index: 100000;
    }

    .MuiButtonBase-root-27 {
        color: inherit;
        border: 0;
        margin: 0;
        cursor: pointer;
        display: inline-flex;
        outline: none;
        padding: 0;
        position: relative;
        align-items: center;
        user-select: none;
        border-radius: 0;
        vertical-align: middle;
        justify-content: center;
        -moz-appearance: none;
        text-decoration: none;
        background-color: transparent;
        -webkit-appearance: none;
        -webkit-tap-highlight-color: transparent;
    }

    .MuiButton-outlined-9 {
        color: rgba(0, 0, 0, 0.87);
        padding: 6px 16px;
        font-size: 0.875rem;
        min-width: 64px;
        box-sizing: border-box;
        transition: background-color 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, box-shadow 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms, border 250ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        line-height: 1.75;
        font-family: "Roboto", "Helvetica", "Arial", sans-serif;
        font-weight: 500;
        border-radius: 4px;
        letter-spacing: 0.02857em;
        text-transform: uppercase;
    }

    .MuiButton-root-1 {
        border: 1px solid rgba(0, 0, 0, 0.23);
        padding: 5px 16px;

    }

    .back-to-admin svg:not(:root).svg-inline--fa {
        overflow: visible;
    }

    .back-to-admin .svg-inline--fa.fa-w-10 {
        width: 0.625em;
    }

    .back-to-admin .svg-inline--fa {
        display: inline-block;
        font-size: inherit;
        height: 1em;
        overflow: visible;
        vertical-align: -0.125em;
    }

</style>


<?php

if (DEVELOPMENT_MODE) {
    ?>
    <iframe src="http://localhost:3000/?<?php echo $_SERVER['QUERY_STRING']; ?>"></iframe>
    <?php
} else {
    require_once plugin_dir_path( __FILE__ ) . '../../builder/dist/index.php';
}
?>

<div class="back-to-admin">
    <a title='Back to admin' class="MuiButtonBase-root-27 MuiButton-root-1 MuiButton-outlined-9"
       tabindex="0" role="button"
       href="<?php echo admin_url();?>edit.php?post_type=wp-campaigns"><span class="MuiButton-label-2"><svg
                    aria-hidden="true" focusable="false" data-prefix="fas" data-icon="chevron-left"
                    class="svg-inline--fa fa-chevron-left fa-w-10 " role="img"
                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" style="margin-right: 15px;"><path
                        fill="currentColor"
                        d="M34.52 239.03L228.87 44.69c9.37-9.37 24.57-9.37 33.94 0l22.67 22.67c9.36 9.36 9.37 24.52.04 33.9L131.49 256l154.02 154.75c9.34 9.38 9.32 24.54-.04 33.9l-22.67 22.67c-9.37 9.37-24.57 9.37-33.94 0L34.52 272.97c-9.37-9.37-9.37-24.57 0-33.94z"></path></svg>
                                    <span><?php echo __('Back to admin', 'wp-campaigns'); ?></span></span>
        <span
                class="MuiTouchRipple-root-104"></span></a>
</div>

<?php
wp_footer();
do_action('admin_print_footer_scripts');
?>
</body>
</html>
