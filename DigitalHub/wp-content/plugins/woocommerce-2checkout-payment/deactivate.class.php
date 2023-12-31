<?php
/**
 * Reason before deactivating the plugin
 * 
 **/

class CPAY_Deactivate {
    
    
    public function __construct() {
        
        $cpay_dir_name  = basename(dirname(__DIR__));
        $cpay_basename  = "{$cpay_dir_name}/woocommerce-2checkout-payment.php";
	    add_filter( "plugin_action_links_{$cpay_basename}", array($this, 'cpay_deactivate_link'), 99);
        
        add_action('admin_enqueue_scripts', array($this, 'load_script'));
        
        add_action('admin_footer', array($this, 'deactivate_scripts'));
        add_action('wp_ajax_cpay_submit_uninstall_reason', array($this, "send_uninstall_reason"));
    }
    
    function cpay_deactivate_link($links) {
        
        
        if (array_key_exists('deactivate', $links)) {
            $links['deactivate'] = str_replace('<a', '<a class="cpay-deactivate-link"', $links['deactivate']);
        }
        
        return $links;
    }
    
    
    public function send_uninstall_reason() {

        global $wpdb;

        if (!isset($_POST['reason_id'])) {
            wp_send_json_error();
        }

        $data = array(
            'reason_id' => sanitize_text_field($_POST['reason_id']),
            'plugin' => "2CO",
            'auth' => 'cpay_auth_uninstall',
            'date' => current_time('mysql'),
            'url' => '',
            'user_email' => '',
            'reason_info' => isset($_REQUEST['reason_info']) ? trim(stripslashes($_REQUEST['reason_info'])) : '',
            'software' => $_SERVER['SERVER_SOFTWARE'],
            'php_version' => phpversion(),
            'mysql_version' => $wpdb->db_version(),
            'wp_version' => get_bloginfo('version'),
            'wc_version' => (!defined('WC_VERSION')) ? '' : WC_VERSION,
            'locale' => get_locale(),
            'multisite' => is_multisite() ? 'Yes' : 'No',
            'ppom_version' => CPAY_VERSION
        );
        // Write an action/hook here in webtoffe to recieve the data
        // $endpoint_url = 'https://theproductionarea.net';
        $endpoint_url = 'https://clients.najeebmedia.com';
        $resp = wp_remote_post("{$endpoint_url}/wp-json/nmclient/v1/uninstall", array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'blocking' => false,
            'body' => $data,
            'cookies' => array()
                )
        );
        wp_send_json_success($resp);
    }

    public function deactivate_scripts() {

        global $pagenow;
        if ('plugins.php' != $pagenow) {
            return;
        }
        $reasons = $this->get_uninstall_reasons();
        ?>
        
        <div class="cpay-deactivate-modal" id="cpay-deactivate-modal">
            <div class="cpay-deactivate-modal-wrap">
                <div class="cpay-deactivate-modal-header">
                    <h3><?php _e('Please Help Us to Make it Better for You.', 'product-import-export-for-woo'); ?></h3>
                </div>
                <div class="cpay-deactivate-modal-body">
                    <ul class="reasons">
                        <?php foreach ($reasons as $reason) { ?>
                            <li data-type="<?php echo esc_attr($reason['type']); ?>" data-placeholder="<?php echo esc_attr($reason['placeholder']); ?>">
                                <label><input type="radio" name="selected-reason" value="<?php echo $reason['id']; ?>"> <?php echo $reason['text']; ?></label>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="cpay-deactivate-modal-footer">
                    <button class="button-primary pipe-model-submit"><?php _e('Submit & Deactivate', 'product-import-export-for-woo'); ?></button>
                    <button class="button-secondary pipe-model-cancel"><?php _e('Cancel', 'product-import-export-for-woo'); ?></button>
                </div>
            </div>
        </div>

        <style type="text/css">
            .cpay-deactivate-modal {
                position: fixed;
                z-index: 99999;
                top: 0;
                right: 0;
                bottom: 0;
                left: 0;
                background: rgba(0,0,0,0.5);
                display: none;
            }
            .cpay-deactivate-modal.modal-active {display: block;}
            .cpay-deactivate-modal-wrap {
                width: 50%;
                position: relative;
                margin: 5% auto;
                background: #fff;
            }
            .cpay-deactivate-modal-header {
                border-bottom: 1px solid #eee;
                padding: 8px 20px;
            }
            .cpay-deactivate-modal-header h3 {
                line-height: 150%;
                margin: 0;
            }
            .cpay-deactivate-modal-body {padding: 5px 20px 20px 20px;}
            .cpay-deactivate-modal-body .input-text,.cpay-deactivate-modal-body textarea {width:75%;}
            .cpay-deactivate-modal-body .reason-input {
                margin-top: 5px;
                margin-left: 20px;
            }
            .cpay-deactivate-modal-footer {
                border-top: 1px solid #eee;
                padding: 12px 20px;
                text-align: right;
            }
            .reviewlink{
                padding:10px 0px 0px 35px !important;
                font-size: 15px;
            }
            .review-and-deactivate{
                padding:5px;
            }
        </style>
    <?php
    }
    
    
    function load_script($hook) {
	
		if( $hook != 'plugins.php' ) return;
		
		// Preloader script
        wp_enqueue_script('cpay-deactivate', CPAY_URL."/js/cpay-deactivate.js", array('jquery'), CPAY_VERSION, true);
	}
	
	
    // ================= Helper Methods =================
    
    private function get_uninstall_reasons() {

        $reasons = array(
            array(
                'id' => 'temporary-disabled',
                'text' => __('Temporarily Disabled for Debugging.', 'product-import-export-for-woo'),
                'type' => 'reviewhtml',
                'placeholder' => __('Have used it successfully and aint in need of it anymore', 'product-import-export-for-woo')
            ),
            array(
                'id' => 'could-not-understand',
                'text' => __('I couldn\'t understand how to make it work', 'product-import-export-for-woo'),
                'type' => 'textarea',
                'placeholder' => __('Would you like us to assist you?', 'product-import-export-for-woo')
            ),
            array(
                'id' => 'found-better-plugin',
                'text' => __('I found a better plugin', 'product-import-export-for-woo'),
                'type' => 'text',
                'placeholder' => __('Which plugin?', 'product-import-export-for-woo')
            ),
            array(
                'id' => 'not-have-that-feature',
                'text' => __('The plugin is great, but I need specific feature that you don\'t support', 'product-import-export-for-woo'),
                'type' => 'textarea',
                'placeholder' => __('Could you tell us more about that feature?', 'product-import-export-for-woo')
            ),
            array(
                'id' => 'is-not-working',
                'text' => __('The plugin is not working', 'product-import-export-for-woo'),
                'type' => 'textarea',
                'placeholder' => __('Could you tell us a bit more whats not working?', 'product-import-export-for-woo')
            ),
            array(
                'id' => 'looking-for-other',
                'text' => __('It\'s not what I was looking for', 'product-import-export-for-woo'),
                'type' => 'textarea',
                'placeholder' => __('Could you tell us a bit more?', 'product-import-export-for-woo')
            ),
            array(
                'id' => 'did-not-work-as-expected',
                'text' => __('The plugin didn\'t work as expected', 'product-import-export-for-woo'),
                'type' => 'textarea',
                'placeholder' => __('What did you expect?', 'product-import-export-for-woo')
            ),
            array(
                'id' => 'rather-say-nothing',
                'text' => __( "I rather wouldn't say", 'product-import-export-for-woo'),
                'type' => 'textarea',
                'placeholder' => __('Could you tell us a bit more?', 'product-import-export-for-woo')
            ),
        );

        return $reasons;
    }
}

new CPAY_Deactivate;