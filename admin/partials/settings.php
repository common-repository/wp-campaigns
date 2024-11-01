<?php

?>
<div class="wrap">
    <h1><?php _e( 'WPex newsletter campaigns setting page!', 'wpex_newsletter_campaigns' ); ?></h1>
    <form class="postbox">

        <div class="form-group inside">
            <h3>
				<?php _e( 'Mailchimp API settings', 'wpex_newsletter_campaigns' ); ?>
            </h3>

            <table class="form-table">
                <tbody>
                <tr>
                    <td scope="row">
                        <label><?php _e( 'Private key', 'wpex_newsletter_campaigns' ); ?></label>
                    </td>
                    <td>
                        <input name="wpex_newsletter_campaigns_private_key"
                               id="wpex_newsletter_campaigns_private_key"
                               class="regular-text"
                               type="text"
                               value="<?php echo ( isset( $wpex_m_p_s->private_key ) ) ? $wpex_m_p_s->private_key : ''; ?>"/>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>


    </form>

	<?php
	if ( false ):
		?>
        <p class="notice notice-error">
			<?php _e( 'An error happened on the WordPress side. Make sure your server allows remote calls.', 'wpex_newsletter_campaigns' ); ?>
        </p>
	<?php
	endif;
	?>

    <div class="inside">
        <button class="button button-primary" id="wpex_newsletter_campaigns-admin-save" type="submit">
			<?php _e( 'Save', 'wpex_newsletter_campaigns' ); ?>
        </button>
    </div>
</div>

<script>
    (function ($) {


        $('#wpex_newsletter_campaigns-admin-save').on('click', function (event) {
            $.ajax({
                method: 'POST',
                url: ajaxurl,
                data: {
                    action: 'wpex_save_mail_provider_settings',
                    wpex_mail_provider_private_key: $('[name="wpex_newsletter_campaigns_private_key"]')[0].value,
                },
                success: function (response) {
                    alert('Successfully saved')
                },
                error: function (request, status, error) {
                    alert('Error occurred');
                }
            });
        })
    })(jQuery);
</script>
