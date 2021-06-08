<?php

namespace dtp;

use dtp\data\DailyStats;
use dtp\data\MonthlyStats;
use VK\Client\VKApiClient;
use VK\Exceptions\VKApiException;
use VK\Exceptions\VKClientException;
use VK\Exceptions\VKOAuthException;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class Posting {

  /** @var VKApiClient */
  private $vk;

  public function __construct() {
    $this->vk = new VKApiClient();
  }

  public function postDailyStats(DailyStats $stats): bool {
    $text = $this->getDailyText($stats);
    try {
      $this->vk->wall()->post(Config::ACCESS_TOKEN,
        [
          'owner_id'   => Config::OWNER_ID,
          'from_group' => true,
          'message'    => $text,
        ]);
    } catch (VKApiException | VKClientException $e) {
      return false;
    }

    return true;
  }

  public function postMonthlyStats(MonthlyStats $stats): bool {
    $text = $this->getMonthlyText($stats);
    try {
      $this->vk->wall()->post(Config::ACCESS_TOKEN,
        [
          'owner_id'   => Config::OWNER_ID,
          'from_group' => true,
          'message'    => $text,
        ]);
    } catch (VKApiException | VKClientException $e) {
      return false;
    }

    return true;
  }

  private function getDailyText(DailyStats $stats): string {
    $date = date('d.m.Y', $stats->date);
    return <<<TXT
ДТП в России за $date
Всего ДТП: $stats->accidents
Погибли: $stats->deaths
Ранены: $stats->injured
Погибло детей: $stats->child_deaths
Ранено детей: $stats->deaths
TXT;
  }

  private function getMonthlyText(MonthlyStats $stats): string {
    $month = $this->convertMonthNames($stats->month);
    return <<<TXT
$month $stats->year: Статистика за месяц 
Всего ДТП: $stats->accidents
Погибли: $stats->deaths
Ранены: $stats->injured
Погибло детей: $stats->child_deaths
Ранено детей: $stats->deaths

TXT;
  }

  private function convertMonthNames(int $month): string {
    $map = [
      1  => 'Январь',
      2  => 'Февраль',
      3  => 'Март',
      4  => 'Апрель',
      5  => 'Май',
      6  => 'Июнь',
      7  => 'Июль',
      8  => 'Август',
      9  => 'Сентябрь',
      10 => 'Октябрь',
      11 => 'Ноябрь',
      12 => 'Декабрь',
    ];
    return $map[$month];
  }

  /** @noinspection PhpUnusedPrivateMethodInspection */
  private static function authGetAccessToken() {
//    $link_to_get_code = 'https://oauth.vk.com/authorize?client_id=CLIEND_ID&display=page&redirect_uri=https://vk.com/test&scope=wall,offline&response_type=code&v=5.124';
    $oauth = new VKOAuth();
    $client_id = Config::CLIENT_ID;
    $client_secret = Config::CLIENT_SECRET;
    $redirect_uri = 'https://vk.com/test';
    $code = ''; // todo: need code

    try {
      $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
    } catch (VKClientException | VKOAuthException $e) {
      return;
    }
    $access_token = $response['access_token'];
    echo $access_token;
  }

  /** @noinspection PhpUnusedPrivateMethodInspection */
  private static function authGetCodeByState() {
    $oauth = new VKOAuth();
    $client_id = Config::CLIENT_ID;
    $redirect_uri = 'https://vk.com/test';
    $display = VKOAuthDisplay::PAGE;
    $scope = [VKOAuthUserScope::WALL];
    $state = '';

    echo $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);
  }

}