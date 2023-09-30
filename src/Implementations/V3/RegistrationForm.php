<?php

namespace Jeffreyvr\JustRecaptcha\Implementations\V3;

use Jeffreyvr\JustRecaptcha\Implementations\Implementation;

class RegistrationForm extends Implementation
{
    public $version = 'v3';

    public function __construct()
    {
        add_action('login_enqueue_scripts', [$this, 'script'], 20);
        add_action('register_form', [$this, 'field']);
        add_filter('registration_errors', [$this, 'verify'], 10);
    }

    public function script()
    {
        $site_key = $this->get_option('site_key');

        if (empty($site_key)) {
            return;
        }

        wp_enqueue_script('recaptcha', 'https://www.google.com/recaptcha/api.js?render='.$site_key, [], null);

        $script = <<<'JS'
            grecaptcha.ready(function() {
                grecaptcha.execute('%s', {action: 'submit'}).then(function(token) {
                    var recaptchaResponse = document.getElementById('recaptchaResponse');
                    recaptchaResponse.value = token;
                });
            });
        JS;

        wp_add_inline_script('recaptcha', sprintf($script, $site_key), 'after');
    }

    public function field()
    {
        echo '<input type="hidden" id="recaptchaResponse" name="recaptcha-response">';
    }

    public function verify($errors)
    {
        $site_secret = $this->get_option('site_secret');

        if (empty($site_secret)) {
            $errors->add('recaptcha_error', __('<strong>Error</strong>: reCAPTCHA is not configured correctly.', 'just-recaptcha'));

            return $errors;
        }

        if (empty($_POST['recaptcha-response'])) {
            $errors->add('recaptcha_error', __('<strong>Error</strong>: Please complete the reCAPTCHA.', 'just-recaptcha'));

            return $errors;
        }

        $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', [
            'body' => [
                'secret' => $site_secret,
                'response' => $_POST['recaptcha-response'],
            ],
        ]);

        $body = wp_remote_retrieve_body($response);
        $result = json_decode($body, true);

        if (! $result['success'] || $result['score'] < 0.5) {  // you can adjust the score as per your need
            $errors->add('recaptcha_error', __('<strong>Error</strong>: Failed reCAPTCHA verification.', 'just-recaptcha'));
        }

        return $errors;
    }
}
