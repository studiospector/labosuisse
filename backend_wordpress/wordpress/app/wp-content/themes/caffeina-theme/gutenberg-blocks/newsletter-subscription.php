<?php

use Caffeina\LaboSuisse\Blocks\NewsletterSubscription;

$blockNewsletterSubscription = (new NewsletterSubscription($block, null))
    ->render();
