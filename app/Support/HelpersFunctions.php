<?php

declare(strict_types = 1);

function obfucate_email(?string $email = null): string
{
    if (! $email) {
        return '';
    }
    $splt = explode('@', $email);

    if (sizeof($splt) != 2) {
        return '';
    }
    $name   = $splt[0];
    $domain = $splt[1];

    $qty       = intval(floor(strlen($name) * 0.65));
    $remaining = strlen($name) - $qty;

    $name = substr($name, 0, $remaining) . str_repeat('*', $qty);

    $qty       = intval(floor(strlen($domain) * 0.65));
    $remaining = strlen($domain) - $qty;

    $domain = str_repeat('*', $qty) . substr($domain, $remaining * -1, $remaining);

    $email = $name . '@' . $domain;

    return $email;
}
