<?php

namespace Jeffreyvr\JustRecaptcha\Implementations\V2;

use Jeffreyvr\JustRecaptcha\Implementations\Implementation;

class RegistrationForm extends Implementation
{
    public $version = 'v2';

    public function __construct()
    {
        add_action('login_enqueue_scripts', [$this, 'script'], 20);
        add_action('register_form', [$this, 'field']);
        add_filter('registration_errors', [$this, 'verify'], 10);
    }

    public function script()
    {
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js', [], null);
    }

    public function field()
    {
        $site_key = $this->get_option('site_key');

        if (empty($site_key)) {
            return;
        }

        echo '<div class="g-recaptcha" data-sitekey="'.esc_attr($site_key).'"></div>';
    }

    public function verify($errors)
    {
        $site_secret = $this->get_option('site_secret');

        if (empty($site_secret)) {
            $errors->add('recaptcha_error', __('<strong>Error</strong>: reCAPTCHA is not configured correctly.', 'just-recaptcha'));

            return $errors;
        }

        if (empty($_POST['g-recaptcha-response'])) {
            $errors->add('recaptcha_error', __('<strong>Error</strong>: Please complete the reCAPTCHA.', 'just-recaptcha'));

            return $errors;
        }

        $response = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$site_secret.'&response='.esc_attr($_POST['g-recaptcha-response']));

        $response_body = wp_remote_retrieve_body($response);

        $result = json_decode($response_body, true);

        if (! empty($result['success']) && $result['success'] === true) {
            // reCAPTCHA validated, proceed with registration
            return $errors;
        } else {
            $errors->add('recaptcha_error', __('<strong>Error</strong>: Failed reCAPTCHA verification.', 'just-recaptcha'));
        }

        return $errors;
    }
}
