<?php

namespace Jeffreyvr\JustRecaptcha;

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
        new Admin\Options();

        add_action('init', [$this, 'loadCaptcha'], 10);
        add_action('plugins_loaded', [$this, 'loadTextdomain']);
    }

    public function loadCaptcha()
    {
        if (! isset($_REQUEST['action'])) {
            return;
        }

        if ($_REQUEST['action'] !== 'register') {
            return;
        }

        $version = Admin\Options::get('version');

        if ($version === 'v2') {
            new Implementations\V2\RegistrationForm;
        } elseif ($version === 'v3') {
            new Implementations\V3\RegistrationForm;
        }
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
