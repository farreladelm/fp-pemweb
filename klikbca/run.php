<?php
// SPDX-License-Identifier: GPL-2.0-only

require __DIR__ . "/src/KlikBCA/KlikBCA.php";
require __DIR__ . "/credentials.tmp";

define("COOKIE_FILE", __DIR__ . "/cookie.tmp");

/*
 * Uncomment this define() to use proxy.
 */
// define("PROXY", "socks5://139.180.140.164:1080");

/**
 * Show the account balance information.
 *
 * @param string $username
 * @param string $password
 * @return bool
 */
function show_balance($username, $password)
{
  $bca = new KlikBCA\KlikBCA($username, $password, COOKIE_FILE);

  /*
   * Use proxy if the PROXY constant is defined.
   */
  if (defined("PROXY")) {
    $bca->setProxy(PROXY);
  }

  $ret = $bca->login();
  if (!$ret) {
    goto err;
  }

  $ret = $bca->balanceInquiry();
  if (!$ret) {
    goto err;
  }

  printf("Balance information:\n%s\n\n", json_encode($ret, JSON_PRETTY_PRINT));
  return;

  err:
  printf("Error: %s\n", $bca->getErr());
}
show_balance($username, $password);

/**
 * Show account statements given the date range.
 *
 * @param string $username
 * @param string $password
 * @param string $startDate
 * @param string $endDate
 * @return bool
 */
function show_account_statements($username, $password, $startDate, $endDate)
{
  $bca = new KlikBCA\KlikBCA($username, $password, COOKIE_FILE);

  /*
   * Use proxy if the PROXY constant is defined.
   */
  if (defined("PROXY")) {
    $bca->setProxy(PROXY);
  }

  $ret = $bca->login();
  if (!$ret) {
    goto err;
  }

  $ret = $bca->accountStatement($startDate, $endDate);
  if (!$ret) {
    goto err;
  }

  printf(json_encode($ret, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
  return;

  err:
  printf("Error: %s\n", $bca->getErr());
}
$startDate = date("Y-m-d", strtotime("-1 week"));
//$startDate = date("Y-m-d");
$endDate = date("Y-m-d");
show_account_statements($username, $password, $startDate, $endDate);
