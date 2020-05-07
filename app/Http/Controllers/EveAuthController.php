<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Providers\EveAuthProvider;
use App\Providers\EveOnlinePersonal;
use App\Providers\EveOnlineCorporation;
use Auth;
use App\User;
use App\Models\Corporations as Corporation;
use App\Models\Invites;
use Illuminate\Support\Facades\Hash;
use Evelabs\OAuth2\Client\Provider\EveOnline;
use Exception;
use Carbon\Carbon;
use App\Jobs\SendEveMailInvite;

class EveAuthController extends Controller
{
//    private $evename;
    private $providerP;
    private $providerC;
    public $owner;
    public function __construct()
    {
//      $this->providerP = $providerP;
      $this->providerP 
      = new \Evelabs\OAuth2\Client\Provider\EveOnline([
            'clientId'          => '6c37f7a5d84e4185afe47a623d947d70',
            'clientSecret'      => 'yejgJmApQXLhe40IPn1d1x0A8xrZqDvFZ34nBzNq',
            'redirectUri'       => 'http://spy3.antiqb.ru/auth/callback',
            ]);
      $this->providerC = 
      new \Evelabs\OAuth2\Client\Provider\EveOnline([
            'clientId'          => 'de84a7a509d24fa2b0562be5274cb07a',
            'clientSecret'      => 'ZBpe7vVAs25d0DxClitGYPJ4FBhpL19gi0d15zDp',
            'redirectUri'       => 'http://spy3.antiqb.ru/auth/callback2',
            ]);
    }

    public function geturl(Request $request) {
        $provider = $this->providerP;
        $authUrl = $provider->getAuthorizationUrl();
        $_SESSION['oauth2state'] = $provider->getState();
//        header('Location: '.$authUrl);
        return redirect($authUrl);
    }
    public function geturl2($corpid,Request $request) {
        $provider = $this->providerC;
        $options = [
            'scope' => ['publicData',
                        'esi-mail.send_mail.v1',
                        'esi-corporations.read_corporation_membership.v1',
                        'esi-corporations.read_structures.v1',
                        'esi-characters.read_corporation_roles.v1',
                        'esi-killmails.read_corporation_killmails.v1',
                        'esi-corporations.track_members.v1',
                        'esi-wallet.read_corporation_wallets.v1',
                        'esi-corporations.read_divisions.v1'] // array or string
        ];
        $authUrl = $provider->getAuthorizationUrl($options);
        $_SESSION['oauth2state'] = $provider->getState();
        $request->session()->put('corpid', $corpid);
        return redirect($authUrl);
    }
    public function geturl3($ret,Request $request) {
        $provider = $this->providerP;
        $options = [
            'scope' => ['publicData',
                        'esi-characters.read_notifications.v1',
                        'esi-universe.read_structures.v1'] // array or string
        ];
        $authUrl = $provider->getAuthorizationUrl($options);
        $_SESSION['oauth2state'] = $provider->getState();
        $request->session()->put('ret', $ret);
        return redirect($authUrl);
    }

    public function gettoken(Request $request) {
        $provider = $this->providerP;
        if (!isset($_GET['code'])) {
        
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;
        
        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
        
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        
        } else {
        
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);
        
            // Optional: Now you have a token you can look up a users profile data
            try {
        
                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);
                $user_ = $user;
                $this->owner = $user;
                // Use these details to create a new profile
//                printf('Hello %s!', $user->getCharacterName());
                $request->session()->put('user', $user);
                $request->session()->put('token', $token);
                $request->session()->put('evename', $user->getCharacterName());
                $request->session()->put('eveid', $user->getCharacterID());
//                echo dd($request);
        
            } catch (Exception $e) {
        
                // Failed to get user details
                exit('Oh dear...');
            }
        
            // Use this to interact with an API on the users behalf
//            echo $token->getToken();
        }        
    }
    public function callback(Request $request) {
        $provider = $this->providerP;
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {
        
                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);
                // Use these details to create a new profile
                $this->owner = $user;

            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
        
                // Failed to get user details
                exit($e->getMessage());
            }
//        return view('auth.sso',['user' => $user->getCharacterName()]);
        $username = $user->getCharacterName();
        $userid = $user->getCharacterID();
        $evedata = $this->getcharacter($userid);
        if(!Auth::check()&&$evedata){
            $password = $token;
            $user = User::where('name', 'LIKE', "%$username%")->first();
//            $character = \DB::table('characters')->where([
//                        ['id', '=', $userid],
//                        ['corporationID', '<>', '0']
//                        ])->first();
            $corporation = \DB::table('corporations')->where([
                        ['corpID', '=', $evedata['corporation_id']],
                        ])->first();
            if (($user)&&(!empty($corporation))) {
                Auth::login($user,true);
//                Auth::attempt(['name' => $username, 'password' => $user->password], true);
            } elseif ((empty($user))&&(!empty($corporation))) {
                $user = User::create([
                    'name' => $username,
                    'characterid' => $userid,
                    'password' => Hash::make($password),
                    ]);
                Auth::login($user);
            } elseif ($user) {
                $user->delete();
            }
        }
        if($request->session()->get('ret') == 1){
            $refresh_token = $token->getRefreshToken();
            return redirect()->route('notificaton_eves.notificaton_eves.create')
                    ->withInput()
                    ->with('userid',$userid)
                    ->with('username',$username)
                    ->with('token',$refresh_token);
        }
        elseif($request->session()->get('ret') == 2){
            $refresh_token = $token->getRefreshToken();
            return redirect()->route('notificaton_eves.notificaton_eves.edit',$userid)
                    ->withInput()
                    ->with('token',$refresh_token);
        } else {
//        dd(url()->previous());
        return redirect()->intended(url()->previous());
//        return redirect()->route('welcome');

//            return redirect()->back();
        }
//        return redirect()->action('EveAuthController@getuser',$request);        
//        return $this->getuser($request);        
    }

    public function callback2(Request $request) {
        $corpid = $request->session()->get('corpid');
//        $corp = Corporation::where('corpID','=',$corpid)->first();
        $provider = $this->providerC;
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code'],
            ]);
        try {
            $refresh_token = $token->getRefreshToken();
        } catch (Exception $e) {
        
                // Failed to get user details
                exit('Oh dear...');
        }
//        $request->session()->put('token2', $refresh_token);
//        if($corp) {
//            $corp->token = $refresh_token;
//            $corp->save();
//        }
        return redirect()->route('corporations.corporations.edit',$corpid)
                ->withInput()
                ->with('token',$refresh_token);
//                ->back();
//        return redirect('https://login.eveonline.com/Account/LogOff?ReturnUrl='.url('admin/corporations'));
    }

    public function getuser(Request $request) {
        $username = $request->session()->get('evename');
        $userid = $request->session()->get('eveid');
//        $username = $evename->getCharacterName();
        $password = $request->session()->get('token');
        $user = User::where('name', 'LIKE', "%$username%")->first();
//        echo dd($user);
        if (!$user) {
            $user = User::create([
                'name' => $username,
                'characterid' => $userid,
                'password' => Hash::make($password),
                ]);
        }
        
        Auth::login($user);
//        $request->session()->flush();
        return redirect()->route('main');
    }
    protected function run_gather() {
      $corps = Corporation::where('token', '<>', '')->get();
          $provider = new \Evelabs\OAuth2\Client\Provider\EveOnline([
            'clientId'          => 'de84a7a509d24fa2b0562be5274cb07a',
            'clientSecret'      => 'ZBpe7vVAs25d0DxClitGYPJ4FBhpL19gi0d15zDp',
            'redirectUri'       => 'http://spy2.rspace.akturide.beget.tech/auth/callback2',
            ]);
      foreach($corps as $corp){
          $old_token = $corp->token;
          $new_token = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $old_token
          ]);
          $request = $provider->getAuthenticatedRequest(
                'GET',
                'https://esi.tech.ccp.is/latest/corporations/'.$corp->corpID.'/members/?datasource=tranquility',
                $new_token
          );    
          $count = count($provider->getResponse($request));
          echo 'Members for '.$corp->name.': '.$count.'<br>';
      }      
    }
    public function logout() {
        Auth::logout();
        return redirect()->back();
    }
    
    public function sendmail($id,$refresh_token,$post) {
      $provider = new \Evelabs\OAuth2\Client\Provider\EveOnline([
        'clientId'          => '4444e5ff80c24cf391b3919e734710f7',
        'clientSecret'      => 'jqxJbzwjWVFFq7pfrHDiIZn48XhxJQQNhqqqqe3z',
        'redirectUri'       => 'http://spy3.antiqb.ru/auth/callback',
        ]);

//      $refresh_token = 'b5AXAWrL2TVNdEBimZVa4kVSnoDLzxtIBloGlsnfhCk1';
      $new_token = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $refresh_token
      ]);
        try {
          $request = $provider->getAuthenticatedRequest(
                'POST',
                'https://esi.evetech.net/latest/characters/'.$id.'/mail/?datasource=tranquility',
                $new_token,
                $post
          ); 
            $response = $provider->getParsedResponse($request);
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {

        // Failed to get the access token or user details.
            return 520;
        }
            
      return $response;
        
    }

    public function setprovidermail() {
      $provider = new \Evelabs\OAuth2\Client\Provider\EveOnline([
        'clientId'          => 'fdb22fbabfc846eebf1789468a0a4401',
        'clientSecret'      => 'pdeQcSlqSEHCS39hgf14UN0fnWGhcT76R0OYE4BZ',
        'redirectUri'       => 'http://spy3.antiqb.ru/auth/callback',
        ]);
      return $provider;
    }    

    public function getevemails() {
      $provider = $this->setprovidermail();
      $refresh_token = '_EH1mBzgnODTpvnE3I3u0hzBBT1-aejzhqEYz03Sor0';
      $new_token = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $refresh_token
      ]);
      $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://esi.evetech.net/latest/characters/94557804/mail/?datasource=tranquility',
            $new_token
      );    
      return $provider->getParsedResponse($request);
    }    
    
    public function getevemail($id) {
      $provider = $this->setprovidermail();
      $refresh_token = '_EH1mBzgnODTpvnE3I3u0hzBBT1-aejzhqEYz03Sor0';
      $new_token = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $refresh_token
      ]);
      $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://esi.evetech.net/latest/characters/94557804/mail/'.$id.'/?datasource=tranquility',
            $new_token
      );    
      return $provider->getParsedResponse($request);
    }    

    public function getcharacter($id) {
      $provider = $this->setprovidermail();
      $refresh_token = 'vfKZxw7AkluRLhBtLECQiuzqyl3DP2vJS6IE4AEuCG4';
      $new_token = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $refresh_token
      ]);
      $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://esi.evetech.net/latest/characters/'.$id.'/?datasource=tranquility',
            $new_token
      );    
      $response = $provider->getParsedResponse($request);
      return array_key_exists('error', $response)?null:$response;
    }    

    public function getsolar($id) {
      $provider = $this->providerP;
      $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://esi.evetech.net/latest/universe/systems/'.$id.'/?datasource=tranquility&language=en-us',
            ''
      );    
      return $provider->getParsedResponse($request);
    }    
    
    public function getstructure($id,$refresh_token) {
      $provider = $this->providerP;
      $token = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $refresh_token
      ]);
      $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://esi.evetech.net/latest/universe/structures/'.$id.'/?datasource=tranquility',
            $token
      );    
      return $provider->getParsedResponse($request);
    }    
    
    public function getTypeID($id) {
      $provider = $this->providerP;
      $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://esi.evetech.net/latest/universe/types/'.$id.'/?datasource=tranquility',
            ''
      );    
      $response = $provider->getParsedResponse($request);
      return array_key_exists('error', $response)?null:$response;
    }    

    public function searchcharacter($pilot) {
        try {
            $request = $this->providerP->getAuthenticatedRequest(
                    'GET',
                    'https://esi.evetech.net/latest/search/?categories=character&datasource=tranquility&language=en-us&search='.$pilot.'&strict=true',
                    ''
            );    
            $character = $this->providerP->getParsedResponse($request);
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            $character = '';        }
        return $character;
    }

    public function corporations_history_character($pilot) {
        try {
            $request = $this->providerP->getAuthenticatedRequest(
                    'GET',
                    'https://esi.evetech.net/latest/characters/'.$pilot.'/corporationhistory/?datasource=tranquility',
                    ''
            );    
            $corporations = $this->providerP->getParsedResponse($request);
        } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
            $corporations = '';        
        }
        return $corporations;
    }

    public function sendinvitemail($pilots,$check) {
        $sended = array();
//        $provider = $this->setprovidermail();
        foreach($pilots as $key=>$pilot) {
          $recipients = array();
          $options = array();
          $sended[$key] = ['pilot'=>$pilot,'searched'=>0,'invited'=>0,'days'=>0];
          $character = $this->searchcharacter($pilot);
          if(isset($character['character'][0])) {
              $sended[$key]['searched'] = 1;
              $character_id = $character['character'][0];
//              $invited_pilot = Invites::where('character_id',$character_id)->where('invited',true)->first();
              $invited_pilot = Invites::where('character_id',$character_id)->first();
              //if($invited_pilot->invited==false){
              if(!$invited_pilot){
                  $corporations = $this->corporations_history_character($character_id);
                  if(!isset($corporations[0])) {
                      $sended[$key]['searched'] = 2;
                      continue;
                  }
                  $start = Carbon::parse($corporations[0]['start_date']);
                  $now = new Carbon();
                  $sended[$key]['days'] = $start->diffInDays($now);
                  $corporations_npc = array_filter($corporations,
                                    function ($val)  { // N.b. $val, $key not $key, $val
                                        return $val['corporation_id'] < 2000000; } );
                  $corporations_pvp = array_filter($corporations,
                                    function ($val)  { // N.b. $val, $key not $key, $val
                                        return $val['corporation_id'] > 2000000; } );
                  if(count($corporations_pvp)>0) {
                    $sended[$key]['searched'] = 2;
                    continue;
                  }
                  if(count($corporations_npc)>1) {
                    $sended[$key]['searched'] = 2;
                    continue;
                  }
                  if($sended[$key]['days']>120) continue;
                  $recipients[] = ['recipient_id'=>$character_id,'recipient_type'=>'character'];
                  $body = "<font size=\"12\" color=\"#bfffffff\"></font><font size=\"14\" color=\"#ffffffff\">"
                            ."<b>Привет,</b> </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:1383//".$character_id."\">".$pilot."</a></loc></font>"
                            ."<font size=\"14\" color=\"#ffffffff\">!<br></font><font size=\"12\" color=\"#bfffffff\"> <br></font><font size=\"14\" color=\"#bfffffff\">"
                            ."<b>Хочу предложить вступить в нашу корпорацию для совместной игры, так как ты находишься в НПЦ корпорации с налогом 11% (у нас 0,1%). "
                            ."У нас многоуровневая организация, вступление в академию - это первый этап. Чтобы посмотреть все этапы кликните на опиcание корпорации </font>"
                            ."<font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:2//98399497\">Red Cold Chili Banderlogs Academy</a></font><font size=\"14\" color=\"#bfffffff\">.</b>"
                            ."<br><br></font><font size=\"12\" color=\"#ffffffff\">Требования для вступления минимальны и не потребуют от вас ничего сверхъестественного."
                            ."<br>Быть адекватным, не сквернословить и по возможности участвовать в общих движухах. Пилоты тут учатся, а мы:<br></font>"
                            ."<font size=\"12\" color=\"#ffff0000\">* расскажем чем должен заниматься новичёк в первый месяц игры."
                            ."<br>* покажем как можно заработать (придется вкачать правильные скилы)<br>* ответим вам на все возникающие вопросы."
                            ."<br>* дадим возможсноть поучаствовать в ежедневных пвп вылетах<br>* поможем переселиться в нули, где больше возможностей для заработка и пвп;"
                            ."<br></font><font size=\"12\" color=\"#bfffffff\"> <br></font>"
                            ."<font size=\"12\" color=\"#ff007fff\">Наличие голосового чата дискорда на вылетах - обязательное условие. "
                            ."Общение в голосе - это единственный способ, максимальной и полноценной коммуникации в игре. "
                            ."<br>Средний возраст наших пилотов 30+ в основной корпорации, это папики в окружении детей, поэтому в дискорде не будет мата. "
                            ."<br><br></font><font size=\"12\" color=\"#ffffffff\"><b>Присоединяйтесь к нам и мы вам поможем освоиться.</b>"
                            ."<br><br></font><font size=\"12\" color=\"#ffb2b2b2\">Кликнув на название корпорации </font><font size=\"14\" color=\"#ffd98d00\">"
                            ."<a href=\"showinfo:2//98399497\">Red Cold Chili Banderlogs Academy</a></font><font size=\"12\" color=\"#ffb2b2b2\"> снизу будет кнопка - Подать заявку. "
                            ."<br>После подачи заявку проверяйте статус приглашения в разделе </font><font size=\"12\" color=\"#ff00ff00\"><b>\"Корпорация -&gt; Вербовка -&gt; Мои заявки\"</b>"
                            ."<br></font><font size=\"12\" color=\"#ffb2b2b2\">Голосовая связь корпорации - </font><font size=\"14\" color=\"#ff00ff00\">ссылка</font>"
                            ."<font size=\"14\" color=\"#ffb2b2b2\"> </font><font size=\"14\" color=\"#ffffe400\"><a href=\"https://discord.gg/xNhme66\">Дискорд</a></font>"
                            ."<br><font size=\"12\" color=\"#ffb2b2b2\">Ингейм канал - </font><font size=\"12\" color=\"#ff6868e1\"><a href=\"joinChannel:player_-67770277//None//None\">RSpaceX</a></font>"
                            ."<font size=\"12\" color=\"#ffb2b2b2\"> <br><br></font><font size=\"12\" color=\"#bfffffff\">"
                            ."<u>Все вопросы можно задать в дискорде в комнате - Рекрутерская, или в канале бандерлогсхэлп (доступен после регистрации в дискорде)</u></font>";
                  $post = array('approved_cost'=>0, 'recipients'=>$recipients,'body'=>$body,'subject'=>'Вступай в крупнейший русскоязычный альянс');
                  //dd(json_encode($post,true));
                  $options['body'] = json_encode($post,true);
                  $options['headers']['content-type'] = 'application/json';
                  //$response = ($check==0?$this->sendmail('514674064',$options):null);
                  if($check==0){
                      if(!$invited_pilot){
                          $invited_pilot = Invites::create(['character_id' => $character_id,
                                                          'name' => $pilot,
                                                          'invited' => false]);
                      }
                        $delay = \DB::table('jobs')->count()*10;
                      SendEveMailInvite::dispatch(new Invites(['character_id' => $character_id,
                                                                  'name' => $pilot,
                                                                  'invited' => false]))
                                            ->delay($delay);                      
                  } 
                  $sended[$key]['invited'] = 4;
                  $response = null;
                  if($response) {
                    if($response==520) {
                        $sended[$key]['invited'] = 3;
                    } else {
                        $sended[$key]['invited'] = 1;
                        //$invited_pilot = Invites::create(['character_id' => $character_id,
                        //                                  'name' => $pilot,
                        //                                  'invited' => true]);
                        //$invited_pilot->invited = true;
                        //$invited_pilot->save();
                    }
                  }
              } else {
                if($invited_pilot->invited==false) {
                    $delay = \DB::table('jobs')->count()*10;
                    SendEveMailInvite::dispatch(new Invites(['character_id' => $character_id,
                                                          'name' => $pilot,
                                                          'invited' => false]))
                                    ->delay($delay);                      
                    $sended[$key]['invited'] = 4;
                } else $sended[$key]['invited'] = 2;
              }

          } else {
              continue;
          }
//          dd(count($corporations));
        }      
//        dd($sended);
        return $sended;
    }    

    public function sendinvitemail2($pilots,$check) {
        $sended = array();
//        $provider = $this->setprovidermail();
        foreach($pilots as $key=>$pilot) {
          $recipients = array();
          $options = array();
          $sended[$key] = ['pilot'=>$pilot,'searched'=>0,'invited'=>0,'days'=>0];
          $character = $this->searchcharacter($pilot);
          if(isset($character['character'][0])) {
              $sended[$key]['searched'] = 1;
              $character_id = $character['character'][0];
              $invited_pilot = Invites::where('character_id',$character_id)->where('invited',true)->first();
              //if($invited_pilot->invited==false){
              if(!$invited_pilot){
                  $corporations = $this->corporations_history_character($character_id);
                  $start = Carbon::parse($corporations[0]['start_date']);
                  $now = new Carbon();
                  $sended[$key]['days'] = $start->diffInDays($now);
                  $corporations_npc = array_filter($corporations,
                                    function ($val)  { // N.b. $val, $key not $key, $val
                                        return $val['corporation_id'] < 2000000; } );
                  $corporations_pvp = array_filter($corporations,
                                    function ($val)  { // N.b. $val, $key not $key, $val
                                        return $val['corporation_id'] > 2000000; } );
                  if(count($corporations_pvp)>1) {
                    $sended[$key]['searched'] = 2;
                    continue;
                  }
                  if(count($corporations_npc)>2) {
                    $sended[$key]['searched'] = 2;
                    continue;
                  }
                  if($sended[$key]['days']>30) continue;
                  $recipients[] = ['recipient_id'=>$character_id,'recipient_type'=>'character'];
                  $body = "<font size=\"12\" color=\"#bfffffff\"></font><font size=\"14\" color=\"#ffffffff\">"
                            ."<b>Привет,</b> </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:1383//".$character_id."\">".$pilot."</a></loc></font>"
                            ."<font size=\"14\" color=\"#ffffffff\">!<br></font><font size=\"12\" color=\"#bfffffff\"> <br></font><font size=\"14\" color=\"#bfffffff\">"
                            ."<b>Хочу предложить вступить в нашу корпорацию для совместной игры, так как ты находишься в НПЦ корпорации с налогом 11% (у нас 0,1%). "
                            ."У нас многоуровневая организация, вступление в академию - это первый этап. Чтобы посмотреть все этапы кликните на опиcание корпорации </font>"
                            ."<font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:2//98399497\">Red Cold Chili Banderlogs Academy</a></font><font size=\"14\" color=\"#bfffffff\">.</b>"
                            ."<br><br></font><font size=\"12\" color=\"#ffffffff\">Требования для вступления минимальны и не потребуют от вас ничего сверхъестественного."
                            ."<br>Быть адекватным, не сквернословить и по возможности участвовать в общих движухах. Пилоты тут учатся, а мы:<br></font>"
                            ."<font size=\"12\" color=\"#ffff0000\">* расскажем чем должен заниматься новичёк в первый месяц игры."
                            ."<br>* покажем как можно заработать (придется вкачать правильные скилы)<br>* ответим вам на все возникающие вопросы."
                            ."<br>* дадим возможсноть поучаствовать в ежедневных пвп вылетах<br>* поможем переселиться в нули, где больше возможностей для заработка и пвп;"
                            ."<br></font><font size=\"12\" color=\"#bfffffff\"> <br></font>"
                            ."<font size=\"12\" color=\"#ff007fff\">Наличие голосового чата дискорда на вылетах - обязательное условие. "
                            ."Общение в голосе - это единственный способ, максимальной и полноценной коммуникации в игре. "
                            ."<br>Средний возраст наших пилотов 30+ в основной корпорации, это папики в окружении детей, поэтому в дискорде не будет мата. "
                            ."<br><br></font><font size=\"12\" color=\"#ffffffff\"><b>Присоединяйтесь к нам и мы вам поможем освоиться.</b>"
                            ."<br><br></font><font size=\"12\" color=\"#ffb2b2b2\">Кликнув на название корпорации </font><font size=\"14\" color=\"#ffd98d00\">"
                            ."<a href=\"showinfo:2//98399497\">Red Cold Chili Banderlogs Academy</a></font><font size=\"12\" color=\"#ffb2b2b2\"> снизу будет кнопка - Подать заявку. "
                            ."<br>После подачи заявку проверяйте статус приглашения в разделе </font><font size=\"12\" color=\"#ff00ff00\"><b>\"Корпорация -&gt; Вербовка -&gt; Мои заявки\"</b>"
                            ."<br></font><font size=\"12\" color=\"#ffb2b2b2\">Голосовая связь корпорации - </font><font size=\"14\" color=\"#ff00ff00\">ссылка</font>"
                            ."<font size=\"14\" color=\"#ffb2b2b2\"> </font><font size=\"14\" color=\"#ffffe400\"><a href=\"https://discord.gg/xNhme66\">Дискорд</a></font>"
//                            ."<br><font size=\"12\" color=\"#ffb2b2b2\">Ингейм канал - </font><font size=\"12\" color=\"#ff6868e1\"><a href=\"joinChannel:player_-67770277//None//None\">RSpaceX</a></font>"
                            ."<font size=\"12\" color=\"#ffb2b2b2\"> <br><br></font><font size=\"12\" color=\"#bfffffff\">"
                            ."<u>Все вопросы можно задать в дискорде в комнате - Рекрутерская, или в канале бандерлогсхэлп (доступен после регистрации в дискорде)</u></font>";
                  $post = array('approved_cost'=>0, 'recipients'=>$recipients,'body'=>$body,'subject'=>'Вступай в крупнейший русскоязычный альянс');
                  //dd(json_encode($post,true));
                  $options['body'] = json_encode($post,true);
                  $options['headers']['content-type'] = 'application/json';
//                  $response = ($check==0?$this->sendmail('514674064',$options):null);
                  $sended[$key]['invited'] = 4;
                  //$response = null;
                  if($response) {
                    if($response==520) {
                        $sended[$key]['invited'] = 3;
                    } else {
                        $sended[$key]['invited'] = 1;
                        $invited_pilot = Invites::create(['character_id' => $character_id,
                                                          'name' => $pilot,
                                                          'invited' => true]);
                        //$invited_pilot->invited = true;
                        //$invited_pilot->save();
                    }
                  }
              } else {
                $sended[$key]['invited'] = 2;
              }

          } else {
              continue;
          }
//          dd(count($corporations));
        }      
//        dd($sended);
        return $sended;
    }    
    public function getevenotifications($character_id,$refresh_token) {
      $provider = $this->providerP;
      $token = $provider->getAccessToken('refresh_token', [
            'refresh_token' => $refresh_token
      ]);
      $request = $provider->getAuthenticatedRequest(
            'GET',
            'https://esi.evetech.net/latest/characters/'.$character_id.'/notifications/?datasource=tranquility',
            $token
      );    
      return $provider->getParsedResponse($request);
    }    

}
