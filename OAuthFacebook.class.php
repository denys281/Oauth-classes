<?php
/**
 * Class for OAuth Facebook
 * @see http://developers.facebook.com/docs/authentication/server-side/
 *
 *
 * @param string $appId              Application ID
 * @param string $secretKey          Secret key
 * @param string $code               Authorization code, received with user return the url
 * @param string $redirectUri        Redirect url

 * 
 * 
 * @return array    $user             Include next field:
 * 
 * @return string   id                Unique user id in Facebook
 * @return string   name              Full user name
 * @return string   first_name        First name
 * @return string   middle_name       Middle name
 * @return string   last_name         Last name
 * @return string   locale            User locale
 * @return string   email             User email
 * @return string   gender            User gender
 * @return string   updated_time      Time
 * 
 */

class OAuthFacebook {

  public $clientId;
  public $clientSecret;
  public $code;
  public $redirectUri;


  public function __construct($clientId, $clientSecret, $code, $redirectUri) {

    $this->clientId = $clientId;
    $this->clientSecret = $clientSecret;
    $this->code = $code;
    $this->redirectUri = $redirectUri;
  }
  
 public function getUserData() {
    $curl = curl_init('https://graph.facebook.com/oauth/access_token?');
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'client_id=' . $this->clientId. '&client_secret=' . $this->clientSecret . '&code=' . $this->code. '&redirect_uri=' . urlencode($this->redirectUri));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $s = curl_exec($curl);
    curl_close($curl);
    parse_str($s, $auth);

    // Only get request 
    $curl = curl_init('https://graph.facebook.com/me?access_token=' .$auth['access_token']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $s = curl_exec($curl);
    curl_close($curl);
    $user = json_decode($s, true);
     
    
    return $user;
  }
  
}