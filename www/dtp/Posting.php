<?php

namespace dtp;

use VK\Client\VKApiClient;
use VK\Exceptions\VKClientException;
use VK\Exceptions\VKOAuthException;
use VK\OAuth\Scopes\VKOAuthUserScope;
use VK\OAuth\VKOAuth;
use VK\OAuth\VKOAuthDisplay;
use VK\OAuth\VKOAuthResponseType;

class Posting {

  /** @var DailyStats */
  private $stats;

  /**
   * @param DailyStats $stats
   */
  public function __construct(DailyStats $stats) {
    $this->stats = $stats;
  }


  public function post(): bool {
    $vk = new VKApiClient();

    $text = $this->getText();
    $vk->wall()->post(Config::ACCESS_TOKEN,
      [
        'owner_id'   => Config::OWNER_ID,
        'from_group' => true,
        'message'    => $text,
      ]);

    return true;
  }

  private function getText(): string {
    $date = date('d.m.Y', $this->stats->date);
    return <<<TXT
ДТП в России за {$date}
Всего ДТП: {$this->stats->accidents}
Погибли: {$this->stats->deaths}
Ранены: {$this->stats->injured}
Погибли детей: {$this->stats->child_deaths}
Ранены детей: {$this->stats->deaths}
TXT;
  }

  private static function authGetAccessToken() {
//    $link_to_get_code = 'https://oauth.vk.com/authorize?client_id=CLIEND_ID&display=page&redirect_uri=https://vk.com/test&scope=wall,offline&response_type=code&v=5.124';
    $oauth = new VKOAuth();
    $client_id = Config::CLIENT_ID;
    $client_secret = Config::CLIENT_SECRET;
    $redirect_uri = 'https://vk.com/test';
    $code = ''; // todo: need code

    try {
      $response = $oauth->getAccessToken($client_id, $client_secret, $redirect_uri, $code);
    } catch (VKClientException $e) {
    } catch (VKOAuthException $e) {
    }
    $access_token = $response['access_token'];
    echo $access_token;
  }

  private static function authGetCodeByState() {
    $oauth = new VKOAuth();
    $client_id = Config::CLIENT_ID;
    $redirect_uri = 'https://vk.com/test';
    $display = VKOAuthDisplay::PAGE;
    $scope = [VKOAuthUserScope::WALL];
    $state = '';

    $browser_url = $oauth->getAuthorizeUrl(VKOAuthResponseType::CODE, $client_id, $redirect_uri, $display, $scope, $state);
  }

}