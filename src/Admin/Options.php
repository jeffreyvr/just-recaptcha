<?php

namespace Jeffreyvr\JustRecaptcha\Admin;

use Jeffreyvr\WPSettings\WPSettings;

class Options
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register']);
    }

    public static function get($key, $fallback = null)
    {
        $options = get_option('just_recaptcha', []);

        return $options[$key] ?? $fallback;
    }

    public function register()
    {
        $settings = new WPSettings(__('Just reCAPTCHA', 'just-recaptcha'));

        $settings->set_menu_parent_slug('options-general.php');

        $tab = $settings->add_tab(__('General', 'just-recaptcha'));

        $section = $tab->add_section('Keys');

        $section->add_option('text', [
            'name' => 'site_key',
            'label' => __('Site key', 'just-recaptcha')
        ]);

        $section->add_option('text', [
            'name' => 'site_secret',
            'label' => __('Site Secret', 'just-recaptcha')
        ]);

        $settings->make();
    }
}
