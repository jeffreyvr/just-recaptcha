<?php
/**
 * Just Recaptcha
 *
 * @wordpress-plugin
 * Plugin name:         Just reCAPTCHA
 * Description:         Simply add a reCAPTCHA v2 or v3 to your WordPress registration form.
 * Version:             0.1.0
 * Requires at least:   6.0
 * Requires PHP:        8.0
 * Author:              Jeffrey van Rossum
 * Author URI:          https://vanrossum.dev
 * Text Domain:         just-recaptcha
 * Domain Path:         /languages
 * License:             MIT
 */

use Jeffreyvr\JustRecaptcha\JustRecaptcha;

define('JUST_RECAPTCHA_PLUGIN_VERSION', '0.1.0');
define('JUST_RECAPTCHA_PLUGIN_FILE', __FILE__);
define('JUST_RECAPTCHA_PLUGIN_DIR', __DIR__);

if (! class_exists(JustRecaptcha::class)) {
    if (is_file(__DIR__.'/vendor/autoload_packages.php')) {
        require_once __DIR__.'/vendor/autoload_packages.php';
    }
}

function just_recaptcha()
{
    return JustRecaptcha::instance();
}

just_recaptcha();
