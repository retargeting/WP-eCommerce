<?php

class WPEC_Retargeting_Admin
{
    public function register_tab($settings_page)
    {
        WPEC_Retargeting::get_instance()->load_class('WPSC_Settings_Tab_Retargeting');

        $settings_page->register_tab(
            WPSC_Settings_Tab_Retargeting::TAB_KEY,
            WPSC_Settings_Tab_Retargeting::TAB_NAME
        );
    }

    public function register_action_links($links, $plugin_file)
    {
        if ($plugin_file === WPEC_Retargeting::get_instance()->get_plugin_name()) {
            $url = admin_url('options-general.php?page=wpsc-settings&tab=retargeting');
            $links[] = '<a href="' . esc_attr($url) . '">' . esc_html__('Settings') . '</a>';
        }
        return $links;
    }

    public function register_settings()
    {

        $allpages = get_pages();
        $pages = '';

        foreach ($allpages as $key => $page) {
            $pages .= '<li>' . $page->ID . ' for ' . $page->post_title . '</li>';
        }

        add_settings_section(
            'retargeting_general_settings',
            __('Retargeting Settings'),
            '',
            'retargeting'
        );

        add_settings_field(
            'retargeting_domain_api',
            __('Domain API Key'),
            array('WPSC_Settings_Tab_Retargeting', 'display_setting'),
            'retargeting',
            'retargeting_general_settings',
            array(
                'type' => 'text',
                'desc' => __('Insert retargeting Domain API Key. <a href="https://retargeting.biz/admin/module/settings/docs-and-api" target="_blank">Click here</a> to get your Domain API Key'),
                'html_options' => array(
                    'name' => 'wpsc_options[retargeting_domain_api]',
                    'value' => get_option('retargeting_domain_api', ''),
                    'size' => 40
                )
            )
        );

        add_settings_field(
            'retargeting_token',
            __('Token'),
            array('WPSC_Settings_Tab_Retargeting', 'display_setting'),
            'retargeting',
            'retargeting_general_settings',
            array(
                'type' => 'text',
                'desc' => __('Insert retargeting Token. <a href="https://retargeting.biz/admin/module/settings/docs-and-api" target="_blank">Click here</a> to get your Token'),
                'html_options' => array(
                    'name' => 'wpsc_options[retargeting_token]',
                    'value' => get_option('retargeting_token', ''),
                    'size' => 40
                )
            )
        );

        add_settings_field(
            'retargeting_help_pages',
            __('Help Pages'),
            array('WPSC_Settings_Tab_Retargeting', 'display_setting'),
            'retargeting',
            'retargeting_general_settings',
            array(
                'type' => 'text',
                'desc' => __('Insert all Help Pages Id\'s, comma separed (e.g. How to order?, FAQ, How I get the products?)'),
                'pageids' => $pages,
                'html_options' => array(
                    'id' => 'retargeting-help',
                    'name' => 'wpsc_options[retargeting_help_pages]',
                    'value' => get_option('retargeting_help_pages', ''),
                )
            )
        );

        register_setting(
            'retargeting',
            'retargeting_domain_api',
            array($this, 'validate_setting_account_id')
        );

        register_setting(
            'retargeting',
            'retargeting_token',
            array($this, 'validate_setting_token')
        );

        register_setting(
            'retargeting',
            'retargeting_help_pages',
            array($this, 'validate_setting_help_pages')
        );

    }

    public function validate_setting_account_id($account_id)
    {
        $valid = get_option('retargeting_domain_api');
        return $account_id;
    }

    public function validate_setting_token($token)
    {
        $valid = get_option('retargeting_token');
        return $token;
    }

    public function validate_setting_help_pages($pages)
    {
        $valid = get_option('retargeting_help_pages');
        return $pages;

    }
}
