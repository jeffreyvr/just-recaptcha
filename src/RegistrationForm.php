<?php

namespace Jeffreyvr\JustRecaptcha;

class RegistrationForm
{
    public function __construct()
    {
        add_action('login_enqueue_scripts', [$this, 'script']);
        add_action('register_form', [$this, 'field']);
        add_filter('registration_errors', [$this, 'verify'], 10);
    }

    function script()
    {
        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js', [], null);
    }

    function field()
    {
        $site_key = Admin\Options::get('site_key');

        echo '<div class="g-recaptcha" data-sitekey="'.esc_attr($site_key).'"></div>';
    }

    public function verify($errors)
    {
        if (empty($_POST['g-recaptcha-response'])) {
            $errors->add('recaptcha_error', '<strong>Error</strong>: Please complete the reCAPTCHA.');

            return $errors;
        }

        $site_secret = esc_attr(Admin\Options::get('site_secret'));

        if (empty($site_secret)) {
            $errors->add('recaptcha_error', '<strong>Error</strong>: reCAPTCHA is not configured correctly.');

            return $errors;
        }

        $response = wp_remote_get('https://www.google.com/recaptcha/api/siteverify?secret='.$site_secret.'&response=' . esc_attr($_POST['g-recaptcha-response']));

        $response_body = wp_remote_retrieve_body($response);

        $result = json_decode($response_body, true);

        if (!empty($result['success']) && $result['success'] === true) {
            // reCAPTCHA validated, proceed with registration
            return $errors;
        } else {
            $errors->add('recaptcha_error', '<strong>Error</strong>: reCAPTCHA verification failed.');
        }

        return $errors;
    }
}
