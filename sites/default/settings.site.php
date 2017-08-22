<?php

/**
 * @file
 * Includes settings that should apply to all site instances.
 */

// Redirect all Pantheon traffic to non-www, HTTPS protocol.
if (php_sapi_name() != "cli" && (isset($_ENV['PANTHEON_ENVIRONMENT']))) {
  // Logic to redirect traffic to HTTPS.
  $url = $_SERVER['HTTP_HOST'];
  $redirect = FALSE;
  $www = strpos($url, 'www.');
  if ($www === 0) {
    // The request begins with "www." . Rewrite the URL only to include
    // everything after "www." and trigger the redirect.
    $url = substr($url, 4);
    $redirect = TRUE;
  }
  // Determine the protocol across multiple methods.
  // HTTP_X_FORWARDED_PROTO is an available element on Pantheon
  // $_SERVER['HTTPS'] is what can be accessed on other servers.
  $protocol = "http";
  if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'OFF') {
    $protocol = "https";
  }
  if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
    $protocol = $_SERVER['HTTP_X_FORWARDED_PROTO'];
  }

  if ($protocol != 'https') {
    // The request is to HTTP. Trigger the redirect.
    $redirect = TRUE;
  }
  if ($redirect) {
    // Send all traffic to HTTPS.
    header('HTTP/1.0 301 Moved Permanently');
    header('Location: https://' . $url . $_SERVER['REQUEST_URI']);
    header('Cache-Control: public, max-age=3600');
    exit();
  }
}
