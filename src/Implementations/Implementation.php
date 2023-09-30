<?php

namespace Jeffreyvr\JustRecaptcha\Implementations;

use Jeffreyvr\JustRecaptcha\Admin\Options;

abstract class Implementation
{
    public function get_option($key, $fallback = null)
    {
        $options = Options::get($this->version, []);

        return $options[$key] ?? $fallback;
    }
}
