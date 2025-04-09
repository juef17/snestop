<?php
function asset_url() {
  return rtrim(base_url(), '/') . '/assets/';
}

function assets_dir() {
  return rtrim(getcwd(), '/') . '/assets/';
}

function views_dir() {
  return rtrim(getcwd(), '/') . '/../app/Views/';
}

function emails_dir() {
  return rtrim(getcwd(), '/') . '/../app/emails/';
}

function generate_random_string($length = 50) {
  $characters = '23456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ';
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, strlen($characters) - 1)];
  }
  return $randomString;
}
