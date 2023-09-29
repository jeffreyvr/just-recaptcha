<?php

namespace Jeffreyvr\JustRecaptcha;

use Jeffreyvr\JustRecaptcha\RegistrationForm;

class JustRecaptcha
{
    private static $instance;

    public static function instance(): self
    {
        if (! isset(self::$instance) && ! (self::$instance instanceof JustRecaptcha)) {
            self::$instance = new JustRecaptcha();
        }

        return self::$instance;
    }

    public function __construct()
    {
        new RegistrationForm();
        new Admin\Options();

        add_action('plugins_loaded', [$this, 'loadTextdomain']);
    }

    public function loadTextdomain(): void
    {
        load_plugin_textdomain(
            'just-recaptcha',
            false,
            dirname(plugin_basename(JUST_RECAPTCHA_PLUGIN_FILE)).'/languages/'
        );
    }
}
