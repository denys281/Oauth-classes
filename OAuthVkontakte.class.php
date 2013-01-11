<?php
/**
 * Class for OAuth Vkontakte
 * @see http://vk.com/developers.php?id=-1_37230422&s=1
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
 * @return string   uid               Unique user id in Vkontakte
 * @return string   first_name        First name
 * @return string   last_name         Last name
 * @return string   nickname          User nickname
 * @return mixed    bdate             Birthday
 * @return boolean  sex               User gender
 * @return mixed    city              User city
 * @return string   photo             Photo
 * @return boolean  has_mobile        Has modile
 */

class OAuthVkontakte {

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

    $curl = curl_init('https://oauth.vk.com/access_token');
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'client_id=' . $this->clientId. '&client_secret=' . $this->clientSecret . '&code=' . $this->code.'&redirect_uri='. $this->redirectUri);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $s = curl_exec($curl);
    curl_close($curl);
    $auth = json_decode($s, true);

    $curl = curl_init('https://api.vk.com/method/getProfiles');
    curl_setopt($curl, CURLOPT_POSTFIELDS, 'method=getProfiles&uid=' .$auth['user_id'] . '&access_token=' . $auth['access_token'].'&fields=uid, first_name, last_name');
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $s = curl_exec($curl);
    curl_close($curl);
    $user = json_decode($s, true);
    $user = $user['response']['0'];
    
    return   array('user' => $user,
        'token'=> $auth['access_token']);
    
  }
  
}