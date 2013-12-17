<?php
function asset_url() {
  return base_url() . 'assets/';
}

function assets_dir() {
  return getcwd() . '/assets/';
}

function views_dir() {
  return getcwd() . '/application/views/';
}

function emails_dir() {
  return getcwd() . '/application/emails/';
}

function generate_random_string($length = 50) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}
