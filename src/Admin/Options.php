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
        $settings = (new WPSettings('reCAPTCHA', 'just-recaptcha'))
            ->set_option_name('just_recaptcha');

        $settings->set_menu_parent_slug('options-general.php');

        $tab = $settings->add_tab(__('General', 'just-recaptcha'));

        $section = $tab->add_section(__('Version', 'just-recaptcha'))
            ->add_option('select', [
                'name' => 'version',
                'label' => __('Version', 'just-recaptcha'),
                'description' => sprintf(__('Select the version of reCAPTCHA you want to use. <a href="%s" target="_blank">More information</a>.', 'just-recaptcha'), 'https://www.google.com/recaptcha/about/'),
                'options' => [
                    '' => __('Choose a version', 'just-recaptcha'),
                    'v2' => 'V2',
                    'v3' => 'V3',
                ],
            ]);

        $section = $tab->add_section('V2')
            ->option_level();

        $section->add_option('text', [
            'name' => 'site_key',
            'label' => __('Site key', 'just-recaptcha'),
        ]);

        $section->add_option('text', [
            'name' => 'site_secret',
            'label' => __('Site Secret', 'just-recaptcha'),
        ]);

        $section = $tab->add_section('V3')
            ->option_level();

        $section->add_option('text', [
            'name' => 'site_key',
            'label' => __('Site key', 'just-recaptcha'),
        ]);

        $section->add_option('text', [
            'name' => 'site_secret',
            'label' => __('Site Secret', 'just-recaptcha'),
        ]);

        $settings->make();
    }
}
