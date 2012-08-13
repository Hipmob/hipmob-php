hipmob-php
==========

Hipmob PHP bindings

## Installation

Download the latest version of the Hipmob server API PHP bindings with:

    git clone https://github.com/Hipmob/hipmob-php

To get started, add the following to your PHP script:

    require_once("/path/to/hipmob-php/lib/Hipmob.php");

Simple usage looks like:

    $hipmob = new Hipmob('your-username','your-api-key');
    $apps = $hipmob->get_applications();
    print_r($apps);

## Documentation

For details examples of how to use the various functions, see the test files.
Please see https://www.hipmob.com/documentation/api.html for detailed documentation.

## Inspiration

The design/structure of the Hipmob API is strongly influenced by the very excellent Stripe PHP API (https://github.com/stripe/stripe-php).