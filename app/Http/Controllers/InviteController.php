<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EveAuthController;
use App\Jobs\SendEveMailInvite;
use Carbon\Carbon;
use App\Models\Invites;

class InviteController extends Controller
{
    protected $eveauth;
    
    public function __construct(EveAuthController $eveauth)
    {
        $this->eveauth = $eveauth;
//	    $this->middleware('director');
	    $this->middleware('role:invite russian,invite english');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('invite');
    }

    public function list()
    {
        //
//        $invitesObjects = Invites::where('invited',1)
        $invitesObjects = \DB::table('invites')
                            ->join('characters', 'characters.id', '=', 'character_id')
                            ->join('corporations', 'characters.corporationID', '=', 'corporations.corpID')
                            ->select('invites.*', 'characters.name as character_name', 'corporations.name as corporation_name','characters.corporationID as corporation_id')
                            ->where('invited',1)
                            ->orderBy('updated_at','desc')
                            ->paginate(200);
        return view('invites.index',compact('invitesObjects'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        ini_set('max_execution_time', 300);
        try {
            $pilots = preg_split( '/\r\n|\r|\n/', $request->pilots );
            $start = new Carbon(); 
//            ProcessImageThumbnails::dispatch($image);
            $sended = $this->eveauth->sendinvitemail($pilots,$request->input('check'));
            $finish = new Carbon(); 
            $invited = array_filter($sended,
                       function ($val)  { // N.b. $val, $key not $key, $val
                       return $val['invited'] == 1||$val['invited'] == 4; } );
            $already = array_filter($sended,
                       function ($val)  { // N.b. $val, $key not $key, $val
                       return $val['invited'] == 2; } );
            $repeated = array();
            foreach($sended as $invite){
                foreach($invite as $key => $value){
                    if($invite['invited']==4||$invite['invited']==3){
                        if($key=='pilot') $repeated[]=$value;
                    }
                }
            }
            $repeated = implode("\r\n",$repeated);
            $total = $finish->diffInSeconds($start);
//            dd($_pilots);
//            dd($sended);
            return back()
//                ->with('success_message', 'Invite mail was successfully sent.')
                ->with('total',$total)
                ->with('pilots',$repeated)
                ->with('count',['total'=>count($pilots),'invited'=>count($invited),'already'=>count($already)])
                ->with('sended',$sended);
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request Invite.']);
        }
        ini_set('max_execution_time', 30);
    }

    public function send(Request $request)
    {
        //
        ini_set('max_execution_time', 300);
        try {
            $pilots = preg_split( '/\r\n|\r|\n/', $request->pilots );
            $template = $request->input('template')?$request->input('template'):1;
            $start = new Carbon(); 
//            SendEveMailInvite::dispatch($pilots);
//            $sended = $this->eveauth->sendinvitemail($pilots,$request->input('check'));
            foreach($pilots as $key=>$pilot) {
              $recipients = array();
              $options = array();
              $sended[$key] = ['pilot'=>$pilot,'searched'=>0,'invited'=>0,'days'=>0];
              $character = $this->eveauth->searchcharacter($pilot);
              if(isset($character['character'][0])) {
                  $sended[$key]['searched'] = 1;
                  $character_id = $character['character'][0];
                  $invited_pilot = Invites::where('character_id',$character_id)->first();
                  if(!$invited_pilot){
                      $corporations = $this->eveauth->corporations_history_character($character_id);
                      if(!isset($corporations[0])) {
                          $sended[$key]['searched'] = 2;
                          continue;
                      }
//                      dd($corporations);
                      if($corporations[0]['corporation_id'] >= 2000000) {
                          $sended[$key]['searched'] = 2;
                          continue;
                      }
                          $started = array_column($corporations, 'start_date');
                          array_multisort($started, SORT_ASC, $corporations);
                          //dd($corporations);
                          $start_date = Carbon::parse($corporations[0]['start_date']);
                          $now = new Carbon();
                          $sended[$key]['days'] = $start_date->diffInDays($now);
                      if($template <> "3") {
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
                          if(count($corporations_npc)>3) {
                            $sended[$key]['searched'] = 2;
                            continue;
                          }
                          if($sended[$key]['days']>120) continue;
                      }
                    
                        $delay = \DB::table('jobs')->count()*10;
                        $sended[$key]['invited'] = 4;
                        if($request->input('check')==0){
                          if(!$invited_pilot){
                              $invited_pilot = Invites::create(['character_id' => $character_id,
                                                                      'name' => $pilot,
                                                                      'invited' => false,
                                                                      'template' => $template]);
                          }
                         SendEveMailInvite::dispatch(new Invites(['character_id' => $character_id,
                                                                  'name' => $pilot,
                                                                  'invited' => false,
                                                                  'template' => $template]))
                                            ->delay($delay);
                        }
                  } else {
                    if($invited_pilot->invited == true) $sended[$key]['invited'] = 2;
                    else {
                        $delay = \DB::table('jobs')->count()*10;
                        $sended[$key]['invited'] = 4;
                        if($request->input('check')==0){
                            $template = $request->input('template')?$request->input('template'):1;
                            SendEveMailInvite::dispatch(new Invites(['character_id' => $character_id,
                                                                  'name' => $pilot,
                                                                  'invited' => false,
                                                                  'template' => $template]))
                                            ->delay($delay);
                        }
                    }
                  }
              } else {
                  continue;
              }
            }      
            $finish = new Carbon(); 
            $invited = array_filter($sended,
                       function ($val)  { // N.b. $val, $key not $key, $val
                       return $val['invited'] == 1||$val['invited'] == 4; } );
            $already = array_filter($sended,
                       function ($val)  { // N.b. $val, $key not $key, $val
                       return $val['invited'] == 2; } );
            $repeated = array();
            foreach($sended as $invite){
                foreach($invite as $key => $value){
                    if($invite['invited']==4||$invite['invited']==3){
                        if($key=='pilot') $repeated[]=$value;
                    }
                }
            }
            $repeated = implode("\r\n",$repeated);
            $total = $finish->diffInSeconds($start);
//            dd($_pilots);
//            dd($sended);
            return back()
//                ->with('success_message', 'Invite mail was successfully sent.')
                ->with('template',$template)
                ->with('total',$total)
                ->with('pilots',$repeated)
                ->with('count',['total'=>count($pilots),'invited'=>count($invited),'already'=>count($already)])
                ->with('sended',$sended);
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request Invite.']);
        }
        ini_set('max_execution_time', 30);
    }

    public function check(Request $request)
    {
        //
        ini_set('max_execution_time', 300);
        try {
            $pilots = preg_split( '/\r\n|\r|\n/', $request->pilots );
            dd($request->pilots);
            $start = new Carbon(); 
            $sended = $this->eveauth->sendinvitemail($pilots,$request->input('check'));
            $finish = new Carbon(); 
            $invited = array_filter($sended,
                       function ($val)  { // N.b. $val, $key not $key, $val
                       return $val['invited'] == 1; } );
            $total = $finish->diffInSeconds($start);
//            dd($sended);
            return back()
//                ->with('success_message', 'Invite mail was successfully sent.')
                ->with('total',$total)
                ->with('count',['total'=>count($pilots),'invited'=>count($invited)])
                ->with('sended',$sended);
        } catch (Exception $exception) {

            return back()->withInput()
                ->withErrors(['unexpected_error' => 'Unexpected error occurred while trying to process your request Invite.']);
        }
        ini_set('max_execution_time', 30);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
