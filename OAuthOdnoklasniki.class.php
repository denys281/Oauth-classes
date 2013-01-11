<?php
/**
 * Class for OAuth Odnoklasniki
 * @see http://dev.odnoklassniki.ru/wiki/pages/viewpage.action?pageId=12878032  
 *
 * @param string $clientId           App id 
 * @param string $clientSecret       Secret key
 * @param string $applicationKey     Application ID
 * @param string $code               Authorization code, received with user return the url
 * @param string $host               Url auth (request from  this url, and response to this url)

 * 
 * 
 * @return array    $user             Include next field:
 * 
 * @return string   uid               Unique user id in Odnoklasniki
 * @return string   first_name        First name
 * @return string   last_name         Last name
 * @return mixed    birthday          Birthday
 * @return string   gender            User gender
 * @return string   pic_1             Small photo
 * @return string   pic_2             Big photo 
 */
class OAuthOdnoklasniki {

  public $clientId;
  public $clientSecret;
  public $applicationKey;
  public $code;
  public $host;

  public function __construct($clientId, $clientSecret, $applicationKey, $code, $host) {

    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->applicationKey = $applicationKey;
    $this->code = $code;
    $this->host = $host;
  }

  public function getUserData() {
    
    $curl = curl_init('http://api.odnoklassniki.ru/oauth/token.do');
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'code=' . $this->code . '&redirect_uri=' . urlencode($this->host) . '&grant_type=authorization_code&client_id=' . $this->clientId . '&client_secret=' . $this->clientSecret);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $s = curl_exec($curl);
    curl_close($curl);
    $auth = json_decode($s, true);
    $curl = curl_init('http://api.odnoklassniki.ru/fb.do?access_token=' . $auth['access_token'] . '&application_key=' . $this->applicationKey . '&method=users.getCurrentUser&sig=' . md5('application_key=' . $this->applicationKey . 'method=users.getCurrentUser' . md5($auth['access_token'] . $this->clientSecret)));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $s = curl_exec($curl);
    curl_close($curl);
    $user = json_decode($s, true);

    return $user;
  }

}

