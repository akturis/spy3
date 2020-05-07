<?php

namespace App\Http\Controllers\Ajax;

//use Illuminate\Http\Request;
use Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Auth;
use Log;

class DataController extends Controller
{
    //
    private $auth = 0;
    private $user ;
    public function __construct() 
    {
        if (Auth::check()) {
            $this->auth = 1;
            $this->user = Auth::user()->name;
        }
    }
    private function getdata1()
    {
        $table = \DB::table(\DB::raw('(SELECT DISTINCT CONCAT(YEAR(logonDateTime),"-",LPAD(MONTH(logonDateTime), 2, "0")) logon, characterId FROM `logons` WHERE logonDateTime>(CURDATE() - INTERVAL 12 MONTH)) dist'))
            ->select(\DB::raw('logon Period, COUNT(*) Online,
                               (SELECT COUNT(distinct killID) FROM `kills` k WHERE CONCAT(YEAR(killtime), "-", LPAD(MONTH(killtime), 2, "0"))=logon AND k.characterID!=victimID) "Kills",
                               (SELECT COUNT(distinct killID) FROM `kills` k WHERE CONCAT(YEAR(killtime), "-", LPAD(MONTH(killtime), 2, "0"))=logon AND k.characterID=victimID) "Looses"'))
            ->groupBy('logon')
            ->get();
/*        mysqli_query($mysqli,'SELECT MAX(DATE_FORMAT(STR_TO_DATE(logon,\'%Y%m\'),\'%b %Y\')) text, COUNT(*) "Online",
(SELECT COUNT(*) FROM `kills` k WHERE DATE_FORMAT(killTime,\'%Y%m\')=logon AND k.characterID!=victimID) "Kills",
(SELECT COUNT(*) FROM `kills` k WHERE DATE_FORMAT(killTime,\'%Y%m\')=logon AND k.characterID=victimID) "Looses"
 FROM (SELECT DISTINCT DATE_FORMAT(logonDateTime,\'%Y%m\') logon, characterId FROM `logons` WHERE logonDateTime>(CURDATE() - INTERVAL 12 MONTH)) dist GROUP BY logon');
*/
        $types = array ('string','number','number','number');

//        echo dd($table);
        foreach ($table as $r) {
           $rr = (array)$r;
           if(!isset($google_JSON)){    
             $google_JSON = "{\"cols\": [";
             $column = array_keys($rr);
             foreach($column as $key=>$value){
                 $google_JSON_cols[]="{\"id\": \"".$key."\", \"label\": \"".$value."\", \"type\": \"".$types[$key]."\"}";
             }    
             $google_JSON .= implode(",",$google_JSON_cols)."],\"rows\": [";       
           }
           $google_JSON_rows[] = "{\"c\":[{\"v\": \"".$rr['Period']."\"}, {\"v\": \"".$rr['Online']."\"}, {\"v\": \"".$rr['Kills']."\"}, {\"v\": \"".$rr['Looses']."\"}]}";
        }
        
        
        $data = $google_JSON.implode(",",$google_JSON_rows)."]}";
        
//        echo dd(json_encode($table));
        //$result[] = ['Period','Online','Kills','Looses'];
//        foreach ($table as $key => $value) {
//            $result[++$key] = [$value->Period, (int)$value->Online, (int)$value->Kills, (int)$value->Looses];
//        }
//        echo dd(json_encode($result, true));
//        echo dd($data);
        return $data;
    }

    private function getdata2($corpid,$days,$top)
    {
        $bounty = "400000";
        $query = \DB::table('characters as c')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) killsK FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND characterid!=victimid group by characterid ) as `k`'),'id','=','k.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) killsD FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND characterid=victimid group by characterid ) as `d`'),'id','=','d.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterid, count(*) days FROM (SELECT characterid, COUNT(DATE_FORMAT(killTime,\'%Y%m%d\')) FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW()  group by characterid, DATE_FORMAT(killTime,\'%Y%m%d\')) as p2 group by characterid) as `p1`'),'id','=','p1.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) miss, COUNT(IF(location_id in (3017706,3016019,3016020,3016022,3016017,3016018,3016023,3016021,3016019),1,null)) missX, COUNT(IF(location_id=3017700,1,null)) missZ FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=33 group by characterid) as `m`'),'id','=','m.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, ROUND(SUM(amount/1000000),2) bounty FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid) as `b`'),'id','=','b.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, ROUND(SUM(amount/1000),2) reward FROM `entries` WHERE refTypeId=33 AND date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid) as `r`'),'id','=','r.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) anom FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=85 AND amount>'.$bounty.' group by characterid) as `a`'),'id','=','a.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END ))) times FROM `logons` WHERE logonDateTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid ) as `f`'),'id','=','f.characterID')
                    ->leftJoin(\DB::raw('(SELECT l1.*, l2.count_l  FROM `logons` l1 JOIN (SELECT characterID, MAX(logoffDateTime) maxdateoff,  max(logondatetime) maxdateon, SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END ))) times, count(*) count_l FROM `logons` WHERE logonDateTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() GROUP BY characterid) l2 ON l2.characterid=l1.characterid AND l2.maxdateon=l1.logonDateTime AND l2.maxdateoff=l1.logoffDateTime  ) as `l`'),'id','=','l.characterID')
                    ->leftJoin('fleetup as fl','fl.characterid','=','c.id')
                    ->leftJoin('invites as inv','inv.character_id','=','c.id')
                    ->leftJoin('comments as t','t.id','=','c.id')
                    ->select(\DB::raw('DISTINCT CONCAT(c.name,"#",c.id) Pilot, title "Main", COALESCE(k.killsK,0) "K",
                                        COALESCE(d.killsD,0) "D", title "Title", startDateTime "Member since", COALESCE(f.times,0) "Flight time",
                                        COALESCE(l.logonDateTime,now()) "Last login", COALESCE(p1.days,0) "Days in PVP", COALESCE(m.miss,0) "Missions",
                                        COALESCE(m.missX,0) "X-7O",COALESCE(m.missZ,0) "5Z",COALESCE(a.anom,0) "Green", COALESCE(b.bounty,0) "Bounty", 
                                        COALESCE(l.location,"-") "Last location", COALESCE(l.shiptype,"-") "Last ship",
                                        c.SS "SS", COALESCE(l.count_l,0) "Logons", COALESCE(round(r.reward,1),0) "LP",COALESCE(fl.exist,0) "FleetUp", if(inv.character_id is null, 0, 1 ) "Invited",t.comment "Comment"'));
        $query = $query->whereBetween('l.logonDateTime',[\DB::raw('NOW() - INTERVAL 12 MONTH'),\DB::raw( 'NOW()')]);
        if (Auth::check()) {
            if ($top == 'none') {
                $query = $query->orderBy('startDateTime','DESC');
            } elseif ($top == 'topkills') {
                $query = $query->orderBy('K','DESC')->limit(10);
            } elseif ($top == 'topmissions') {
                $query = $query->orderBy('Missions','DESC')->limit(10);
            } elseif ($top == 'topgreen') {
                $query = $query->orderBy('Green','DESC')->limit(10);
            }
            if (!Auth::user()->hasRole(['director','admin','super'])) {
                $query = $query->where('c.id','=',Auth::user()->characterid);
                $query = $query->orwhere('c.id','=',\DB::raw('(select s.characterID from (SELECT characterID, COUNT(*) killsK FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND characterid!=victimid group by characterid order by killsK desc) s limit 1)'));
                $query = $query->orwhere('c.id','=',\DB::raw('(select s.characterID from (SELECT characterID, COUNT(*) miss, COUNT(IF(location_id in (3017706,3016019,3016020,3016022,3016017,3016018,3016023,3016021,3016019),1,null)) missX, COUNT(IF(location_id=3017700,1,null)) missZ FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=33 group by characterid order by miss desc) s limit 1)'));
                $query = $query->orwhere('c.id','=',\DB::raw('(select s.characterID from (SELECT characterID, COUNT(*) anom FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=85 AND amount>'.$bounty.' group by characterid order by anom desc) s limit 1)'));
            }
        } else {
            $query = $query->orderBy('startDateTime','desc')->limit(20);
        }
        
        if ($corpid != 'All') {
            $table = $query->where('corporationid','=',$corpid)->get();
        } else { 
            //Log::info($query->toSql());
            $table = $query->get(); 
        }
                    
        $badge="<span class='badge ";
        $types = array ('string','string','number','number',
                        'string','string','string','string',
                        'number','number','number','number',
                        'number','number','string','string',
                        'number','number','number','number','number','string');
        
        //while($r = mysql_fetch_assoc($table)) {
        foreach ($table as $rr) {
           $r = (array)$rr;
           if(!isset($google_JSON)){    
             $google_JSON = "{\"cols\": [";    
             $column = array_keys($r);
             foreach($column as $key=>$value){
                 $google_JSON_cols[]="{\"id\": \"".$key."\", \"label\": \"".$value."\", \"type\": \"".$types[$key]."\"}";
             }    
             $google_JSON .= implode(",",$google_JSON_cols)."],\"rows\": [";       
           }
//           $r['Pilot'] = str_replace("'", "",$r['Pilot']);
           $pilot=explode('#',$r['Pilot']);
           $pilotf = "<img src='http://image.eveonline.com/Character/{$pilot[1]}_32.jpg'><a  href='#{$pilot[1]}' data-toggle='modal' data-target='#sheet'> {$pilot[0]} </a>";
           $query_m = \DB::table('alts as a')
                       ->Join('characters as c','a.main_id','=','c.id')
                       ->leftJoin('fleetup as fl','fl.characterid','=','c.id')
                       ->select('c.name as main','c.id as main_id','fl.exist as fleetup')
                       ->where('a.id','=',$pilot[1])
                       ->get();
//           'SELECT c.name main, c.id main_id, f.exist fleetup FROM alts a 
//                        JOIN characters c ON c.id = a.main_id
//                        LEFT OUTER JOIN fleetup f ON f.characterid = c.id 
//                        WHERE a.id='.$pilot[1];
            if (!$query_m->isEmpty()) {
               $main_name = $query_m->first()->main;
               $main_id = $query_m->first()->main_id;
               $mainf = "<a  href='#{$main_id}'>{$main_name}</a>";
               $r['FleetUp'] = $query_m->first()->fleetup;
            } else {
               $main_name = "";
               $main_id = "";
               $mainf = "";
            }
        //   $query_p = 'SELECT count(*) "Days in PVP" froM (SELECT DATE_FORMAT(a.killTime,\'%Y%m%d\'), COUNT(DATE_FORMAT(a.killTime,\'%Y%m%d\')) FROM `kills` a WHERE DATE_FORMAT(a.killTime,\'%Y%m\')=DATE_FORMAT(CURDATE(),\'%Y%m\') AND (a.characterID='.$pilot[1].' or a.victimid='.$pilot[1].') group by DATE_FORMAT(a.killTime,\'%Y%m%d\')) b';
        //   $query_p = 'SELECT count(*) "Days in PVP" froM (SELECT DATE_FORMAT(a.killTime,\'%Y%m%d\'), COUNT(DATE_FORMAT(a.killTime,\'%Y%m%d\')) FROM `kills` a WHERE a.killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND (a.characterID='.$pilot[1].' or a.victimid='.$pilot[1].') group by DATE_FORMAT(a.killTime,\'%Y%m%d\')) b';
        //   $table_p = mysqli_query($mysqli, $query_p) or die('4'.$config['db']['errormsg'].mysqli_connect_error().$query_p);
           $pvp = $r['Days in PVP'];
        //   if ($p = mysqli_fetch_assoc($table_p) or die('5'.$config['db']['errormsg'].mysqli_connect_error())) $pvp = $p['Days in PVP'];
        
           $r['Bounty'] = (empty($r['Bounty']))?0:$r['Bounty'];
           $r['LP'] = (empty($r['LP']))?0:$r['LP']/13; // 9 при 5 процентах
           $r['LP'] = round($r['LP'],0);
           $r['SS'] = (empty($r['SS']))?0:$r['SS'];
           $r['Logons'] = (empty($r['Logons']))?0:$r['Logons'];
        
           $PVPf = ($pvp>1)?"badge-success'>&nbsp;{$pvp}":"badge-secondary'>&nbsp;{$pvp}"; $PVPf=$badge.$PVPf."&nbsp;</span>";
           $Mf = ($r['Missions'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['Missions']}":"badge-secondary'>&nbsp;{$r['Missions']}"; $Mf=$badge.$Mf."&nbsp;</span>";
           $Mxf = ($r['X-7O'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['X-7O']}":"badge-secondary'>&nbsp;{$r['X-7O']}"; $Mxf=$badge.$Mxf."&nbsp;</span>";
           $Mzf = ($r['5Z'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['5Z']}":"badge-secondary'>&nbsp;{$r['5Z']}"; $Mzf=$badge.$Mzf."&nbsp;</span>";
           $Af = ($r['Green'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['Green']}":"badge-secondary'>&nbsp;{$r['Green']}"; $Af=$badge.$Af."&nbsp;</span>";
           $Bf = ($r['Bounty']>100)?"badge-success'>&nbsp;{$r['Bounty']}":"badge-secondary'>&nbsp;{$r['Bounty']}"; $Bf=$badge.$Bf."&nbsp;</span>";
           $LPf = ($r['LP']>1000)?"badge-success'>&nbsp;{$r['LP']}":"badge-secondary'>&nbsp;{$r['LP']}"; $LPf=$badge.$LPf."&nbsp;</span>";
           $Kf = ($r['K']>0)?"badge-success'>&nbsp;{$r['K']}":"badge-secondary'>&nbsp;{$r['K']}"; $Kf=$badge.$Kf."&nbsp;</span>";
           $Df = ($r['D']>0)?"badge-danger'>&nbsp;{$r['D']}":"badge-secondary'>&nbsp;{$r['D']}"; $Df=$badge.$Df."&nbsp;</span>";
           $titlef = "-";
//           $titlef = mb_substr (preg_replace('/[^\w\p{Cyrillic} .,!?*-]/u','_',$r['Title']),0,36,'UTF-8');
           $Flf = ($r['FleetUp']==1)?"<span class='badge badge-success'>&nbsp</span>":"<span class='badge badge-danger'>&nbsp</span>"; 
           $Invf = ($r['Invited']==1)?"<span class='badge badge-success'>&nbsp</span>":"<span class='badge badge-danger'>&nbsp</span>"; 
           $last = $r['Last login'];
           $r['Flight time'] = ($r['Flight time'][0]==="-")?0:$r['Flight time'];
           $rating = $r['K'] * 10 - $r['D'] * 5 - $r['Missions'] * 3 - $r['Green'] * 2 ;
           $ratingM = $rating*30/$days;
           $rating = $rating + 1000;
           if ($ratingM <= -1000 ) {
               $ratingF = "badge-danger'>&nbsp;{$rating}";
           } elseif (($ratingM >-1000)and($ratingM < -300)) {
               $ratingF = "badge-warning'>&nbsp;{$rating}";
           } elseif (($ratingM >500)and($ratingM <= 1000)) {
               $ratingF = "badge-success'>&nbsp;{$rating}";
           } elseif ($ratingM > 1000) {
               $ratingF = "badge-primary'>&nbsp;{$rating}";
           } else {
                $ratingF = "'>&nbsp;{$rating}";
           }
           $ratingF=$badge.$ratingF."&nbsp;</span>";
           $lp = $r['LP'] * 9000; // при 10 было 4400
//           $Rf=$badge.$Rf."&nbsp;</span>";
           $Rf=$lp;
           $google_JSON_rows[] = "{\"c\":[{\"v\": \"".$pilot[0]."\", \"f\": \"$pilotf\"},{\"v\": \"".strtoupper($main_name)."\", \"f\": \"$mainf\"}, {\"v\": ".$r['K'].", \"f\": \"$Kf\"},
                                          {\"v\": ".$r['D'].", \"f\": \"$Df\"}, {\"v\": \"".$titlef."\"}, {\"v\": \"".$r['Member since']."\"}, {\"v\": \"".$r['Flight time']."\"}, 
                                          {\"v\": \"".$last."\"}, {\"v\": ".$pvp.", \"f\": \"$PVPf\"}, {\"v\": ".$r['Missions'].", \"f\": \"$Mf\"}, {\"v\": ".$r['X-7O'].", \"f\": \"$Mxf\"}, 
                                          {\"v\": ".$r['5Z'].", \"f\": \"$Mzf\"}, {\"v\": ".$r['Green'].", \"f\": \"$Af\"}, {\"v\": ".$r['Bounty'].", \"f\": \"$Bf\"}, 
                                          {\"v\": \"".$r['Last location']."\"},{\"v\": \"".$r['Last ship']."\"}, {\"v\": ".$r['SS'].", \"f\": \"".$r['SS']."\"}, 
                                          {\"v\": ".$r['Logons'].", \"f\": \"".$r['Logons']."\"}, {\"v\": ".$r['LP'].", \"f\": \"".$LPf."\"},{\"v\": ".$r['FleetUp'].", \"f\": \"".$Flf."\"},
                                          {\"v\": ".$r['Invited'].", \"f\": \"".$Invf."\"},{\"v\": \"".$r['Comment']."\"}]}";
        }    
        $data = $google_JSON.implode(",",$google_JSON_rows)."]}";
        return $data;
    }
    private function getdata3()
    {
        $query = \DB::table('entries')
                    ->leftJoin('characters as c','c.id','entries.characterID')
                    ->select(\DB::raw('YEARWEEK(date) Date,
                                    round(sum(amount)/1000000,0) Income, 
                                    round(sum(CASE WHEN refTypeId=33 or refTypeId=34 THEN amount ELSE 0 END)/1000000,0) Mission, 
                                    round(sum(CASE WHEN refTypeId=85 THEN amount ELSE 0 END)/1000000,0) Bounty'))
                    ->whereBetween('Date', [\DB::raw('NOW() - INTERVAL 3 MONTH'),\DB::raw( 'NOW()')])
//                    ->where('c.corporationID',$coprid)
                    ->groupBy(\DB::raw('YEARWEEK(date) ORDER BY YEARWEEK(date) DESC'));
//        dd($query->toSQL());
//        Log::info($query->toSql());
        $table = $query->get();
        $types = array ('string','number','number','number');
        foreach ($table as $rr) {
           $r = (array)$rr;
           if(!isset($google_JSON)){    
             $google_JSON = "{\"cols\": [";    
             $column = array_keys($r);
             foreach($column as $key=>$value){
                 $google_JSON_cols[]="{\"id\": \"".$key."\", \"label\": \"".$value."\", \"type\": \"".$types[$key]."\"}";
             }    
             $google_JSON .= implode(",",$google_JSON_cols)."],\"rows\": [";       
           }
           $google_JSON_rows[] = "{\"c\":[{\"v\": \"".$r['Date']."\"}, {\"v\": \"".$r['Income']."\"}, {\"v\": \"".$r['Mission']."\"}, {\"v\": \"".$r['Bounty']."\"}]}";
        }
        $data = $google_JSON.implode(",",$google_JSON_rows)."]}";
        return $data;
    }
    private function getdata4($corpid)
    {
        $dateS = Carbon::now()->startOfMonth()->subMonth(12);
        $dateE = Carbon::now()->startOfMonth(); 
        $query1 = \DB::table('logons')
                    ->leftJoin('characters as c1','c1.id','logons.characterID')
                    ->select(\DB::raw('HOUR(logons.logoffDateTime) Hour'), \DB::raw('COUNT(*) Logoff'))
                    ->whereBetween('logons.logonDateTime', [$dateS,$dateE])
//                    ->where('logons.logonDateTime','<','logons.logoffDateTime')
                    ->groupBy(\DB::raw('Hour'));
        $table1 = $query1->get();
        
        $query = \DB::table('logons as l')
                    ->Join(\DB::raw('(SELECT HOUR(logoffDateTime) Hour, COUNT(*) Logoff FROM logons l1 LEFT JOIN characters c1 ON c1.id = l1.characterID  WHERE l1.logoffDateTime BETWEEN NOW() - INTERVAL 3 MONTH AND NOW() AND l1.logonDateTime<l1.logoffDateTime GROUP BY HOUR(l1.logoffDateTime)) as o'),'o.Hour','=',\DB::raw('HOUR(l.logonDateTime)'))
                    ->leftJoin('characters as c','c.id','=','l.characterID')
                    ->select(\DB::raw('HOUR(l.logonDateTime) Hour, COUNT(*) Logon, o.Logoff Logoff'))
                    ->where([['l.logonDateTime','<','l.logoffDateTime']])
                    ->whereBetween('l.logonDateTime', [$dateS,$dateE])
                    ->groupBy(\DB::raw('HOUR(l.logonDateTime)'), 'Logoff');
        if ($corpid != 'All') {
            $table = $query->where('c.corporationid','=',$corpid)->get();
        } else {
            $table = $query->get();
        }
//        echo dd($table);

/*        mysqli_query($mysqli,'SELECT HOUR(logonDateTime) "Hour", COUNT(*) "Logon", o.Logoff "Logoff"
         FROM `logons` as l
         JOIN (SELECT HOUR(logoffDateTime) "Hour", COUNT(*) "Logoff" FROM logons LEFT JOIN characters c1 ON c1.id = characterid WHERE c1.corporationid = '.$corpid.' AND logoffDateTime BETWEEN NOW() - INTERVAL 12 MONTH AND NOW() AND logonDateTime<logoffDateTime GROUP BY HOUR(logoffDateTime)) as o ON o.hour=HOUR(l.logonDateTime)
         LEFT JOIN characters c ON c.id = characterid
         WHERE c.corporationid = '.$corpid.' AND logonDateTime BETWEEN NOW() - INTERVAL 12 MONTH AND NOW() AND logonDateTime<logoffDateTime GROUP BY HOUR(logonDateTime), Logoff') or die($config['db']['errormsg'].mysqli_error());
*/        
        $types = array ('string','number','number');
        foreach ($table as $rr) {
           $r = (array)$rr;
           if(!isset($google_JSON)){    
             $google_JSON = "{\"cols\": [";    
             $column = array_keys($r);
             foreach($column as $key=>$value){
                 $google_JSON_cols[]="{\"id\": \"".$key."\", \"label\": \"".$value."\", \"type\": \"".$types[$key]."\"}";
             }    
             $google_JSON .= implode(",",$google_JSON_cols)."],\"rows\": [";       
           }
           $google_JSON_rows[] = "{\"c\":[{\"v\": \"".$r['Hour']."\"}, {\"v\": \"".$r['Logon']."\"}, {\"v\": \"".$r['Logoff']."\"}]}";
        }
        
        
        $data = $google_JSON.implode(",",$google_JSON_rows)."]}";
        return $data;
    }

    private function getdata5($days=30)
    {
        $bounty = "400000";
        $query = \DB::table('characters as c')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) killsK FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND characterid!=victimid group by characterid ) as `k`'),'id','=','k.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) killsD FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND characterid=victimid group by characterid ) as `d`'),'id','=','d.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterid, count(*) days FROM (SELECT characterid, COUNT(DATE_FORMAT(killTime,\'%Y%m%d\')) FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW()  group by characterid, DATE_FORMAT(killTime,\'%Y%m%d\')) as p2 group by characterid) as `p1`'),'id','=','p1.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) miss FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=33 group by characterid) as `m`'),'id','=','m.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, ROUND(SUM(amount/1000000),2) bounty FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid) as `b`'),'id','=','b.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, ROUND(SUM(amount/1000000),2) reward FROM `entries` WHERE refTypeId=33 AND date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid) as `r`'),'id','=','r.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) anom FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=85 AND amount>'.$bounty.' group by characterid) as `a`'),'id','=','a.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END ))) times FROM `logons` WHERE logonDateTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid ) as `f`'),'id','=','f.characterID')
                    ->leftJoin(\DB::raw('(SELECT l1.*, l2.count_l  FROM `logons` l1 JOIN (SELECT characterID, MAX(logoffDateTime) maxdateoff,  max(logondatetime) maxdateon, SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END ))) times, count(*) count_l FROM `logons` WHERE logonDateTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() GROUP BY characterid) l2 ON l2.characterid=l1.characterid AND l2.maxdateon=l1.logonDateTime AND l2.maxdateoff=l1.logoffDateTime  ) as `l`'),'id','=','l.characterID')
                    ->leftJoin('comments as t','t.id','=','c.id')
                    ->select(\DB::raw('DISTINCT CONCAT(name,"#",c.id) Pilot, title "Main", COALESCE(k.killsK,0) "K",
                                        COALESCE(d.killsD,0) "L", title "Title", startDateTime "Member since", f.times "Flight time",
                                        l.logonDateTime "Last login", COALESCE(p1.days,0) "Days in PVP", COALESCE(m.miss,0) "Missions",
                                        COALESCE(a.anom,0) "Green", COALESCE(b.bounty,0) "Bounty", l.location "Last location", l.shiptype "Last ship",
                                        t.comment "Comment", c.SS "SS", l.count_l "Logons", 0 Rating, r.reward "Reward"'))
                    ->orderBy('K','DESC')->limit(10);
            $table = $query->get();

        $badge="<span class='badge ";
        $types = array ('string','string','number','number','string','string','string','string','number','number','number','number','string','string','string','number','number','number','number');
        
        //while($r = mysql_fetch_assoc($table)) {
        foreach ($table as $rr) {
           $r = (array)$rr;
           if(!isset($google_JSON)){    
             $google_JSON = "{\"cols\": [";    
             $column = array_keys($r);
             foreach($column as $key=>$value){
                 $google_JSON_cols[]="{\"id\": \"".$key."\", \"label\": \"".$value."\", \"type\": \"".$types[$key]."\"}";
             }    
             $google_JSON .= implode(",",$google_JSON_cols)."],\"rows\": [";       
           }
//           $r['Pilot'] = str_replace("'", "",$r['Pilot']);
           $pilot=explode('#',$r['Pilot']);
           $pilotf = "<a  href='#{$pilot[1]}'>{$pilot[0]}</a>";
               $main_name = $r['Title'];
               $main_id = "1";
               $mainf = "<a  href='#{$main_id}'>{$main_name}</a>";
           $pvp = $r['Days in PVP'];
        //   if ($p = mysqli_fetch_assoc($table_p) or die('5'.$config['db']['errormsg'].mysqli_connect_error())) $pvp = $p['Days in PVP'];
        
           $r['Bounty'] = (empty($r['Bounty']))?0:$r['Bounty']*7;
           $r['Reward'] = (empty($r['Reward']))?0:$r['Reward']*7;
           $r['SS'] = (empty($r['SS']))?0:$r['SS'];
           $r['Logons'] = (empty($r['Logons']))?0:$r['Logons'];
        
           $PVPf = ($pvp>1)?"badge-success'>&nbsp;{$pvp}":"badge-secondary'>&nbsp;{$pvp}"; $PVPf=$badge.$PVPf."&nbsp;</span>";
           $Mf = ($r['Missions'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['Missions']}":"badge-secondary'>&nbsp;{$r['Missions']}"; $Mf=$badge.$Mf."&nbsp;</span>";
           $Mxf = ($r['X-7O'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['X-7O']}":"badge-secondary'>&nbsp;{$r['X-7O']}"; $Mxf=$badge.$Mxf."&nbsp;</span>";
           $Mzf = ($r['5Z'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['5Z']}":"badge-secondary'>&nbsp;{$r['5Z']}"; $Mzf=$badge.$Mzf."&nbsp;</span>";
           $Af = ($r['Green'] / 30 > 5 )?"badge-warning'>&nbsp;{$r['Green']}":"badge-secondary'>&nbsp;{$r['Green']}"; $Af=$badge.$Af."&nbsp;</span>";
           $Bf = ($r['Bounty']>100)?"badge-success'>&nbsp;{$r['Bounty']}":"badge-secondary'>&nbsp;{$r['Bounty']}"; $Bf=$badge.$Bf."&nbsp;</span>";
           $LPf = ($r['LP']>100)?"badge-success'>&nbsp;{$r['LP']}":"badge-secondary'>&nbsp;{$r['LP']}"; $LPf=$badge.$LPf."&nbsp;</span>";
           $Kf = ($r['K']>0)?"badge-success'>&nbsp;{$r['K']}":"badge-secondary'>&nbsp;{$r['K']}"; $Kf=$badge.$Kf."&nbsp;</span>";
           $Df = ($r['D']>0)?"badge-danger'>&nbsp;{$r['D']}":"badge-secondary'>&nbsp;{$r['D']}"; $Df=$badge.$Df."&nbsp;</span>";
           $titlef = mb_substr (preg_replace('/[^\w\p{Cyrillic} .,!?*-]/u','_',$r['Title']),0,36,'UTF-8');
        //   $last = new Date($r['Last login']).toJson;
        //   $last = JSON.stringify($r['Last login']);
           $last = $r['Last login'];
           $r['Flight time'] = ($r['Flight time'][0]==="-")?0:$r['Flight time'];
           $rating = $r['K'] * 10 - $r['L'] * 5 - $r['Missions'] * 3 - $r['Green'] * 2 ;
           $ratingM = $rating*30/$days;
           $rating = $rating + 1000;
           if ($ratingM <= -1000 ) {
               $ratingF = "badge-danger'>&nbsp;{$rating}";
           } elseif (($ratingM >-1000)and($ratingM < -300)) {
               $ratingF = "badge-warning'>&nbsp;{$rating}";
           } elseif (($ratingM >500)and($ratingM <= 1000)) {
               $ratingF = "badge-success'>&nbsp;{$rating}";
           } elseif ($ratingM > 1000) {
               $ratingF = "badge-primary'>&nbsp;{$rating}";
           } else {
                $ratingF = "'>&nbsp;{$rating}";
           }
           $ratingF=$badge.$ratingF."&nbsp;</span>";
           $lp = $r['Reward'] * 4400;
//           $Rf=$badge.$Rf."&nbsp;</span>";
           $Rf=$lp;
           $google_JSON_rows[] = "{\"c\":[{\"v\": \"".$pilot[0]."\", \"f\": \"$pilotf\"},{\"v\": \"".strtoupper($main_name)."\", \"f\": \"$mainf\"}, {\"v\": ".$r['K'].", \"f\": \"$Kf\"}, {\"v\": ".$r['D'].", \"f\": \"$Df\"}, {\"v\": \"".$titlef."\"}, {\"v\": \"".$r['Member since']."\"}, {\"v\": \"".$r['Flight time']."\"}, {\"v\": \"".$last."\"}, {\"v\": ".$pvp.", \"f\": \"$PVPf\"}, {\"v\": ".$r['Missions'].", \"f\": \"$Mf\"}, {\"v\": ".$r['X-7O'].", \"f\": \"$Mxf\"}, {\"v\": ".$r['5Z'].", \"f\": \"$Mzf\"}, {\"v\": ".$r['Green'].", \"f\": \"$Af\"}, {\"v\": ".$r['Bounty'].", \"f\": \"$Bf\"}, {\"v\": \"".$r['Last location']."\"},{\"v\": \"".$r['Last ship']."\"}, {\"v\": ".$r['SS'].", \"f\": \"".$r['SS']."\"}, {\"v\": ".$r['Logons'].", \"f\": \"".$r['Logons']."\"}, {\"v\": ".$r['LP'].", \"f\": \"".$LPf."\"},{\"v\": ".$r['FleetUp'].", \"f\": \"".$Flf."\"},{\"v\": \"".$r['Comment']."\"}]}";
        }    
        $data = $google_JSON.implode(",",$google_JSON_rows)."]}";
        return $data;
    }

    private function getdata6($days=30)
    {
        $bounty = "400000";
        $query = \DB::table('characters as c')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) killsK FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND characterid!=victimid group by characterid ) as `k`'),'id','=','k.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) killsD FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND characterid=victimid group by characterid ) as `d`'),'id','=','d.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterid, count(*) days FROM (SELECT characterid, COUNT(DATE_FORMAT(killTime,\'%Y%m%d\')) FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW()  group by characterid, DATE_FORMAT(killTime,\'%Y%m%d\')) as p2 group by characterid) as `p1`'),'id','=','p1.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) miss FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=33 group by characterid) as `m`'),'id','=','m.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, ROUND(SUM(amount/1000000),2) bounty FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid) as `b`'),'id','=','b.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, ROUND(SUM(amount/1000000),2) reward FROM `entries` WHERE refTypeId=33 AND date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid) as `r`'),'id','=','r.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) anom FROM `entries` WHERE date BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND refTypeId=85 AND amount>'.$bounty.' group by characterid) as `a`'),'id','=','a.characterID')
                    ->leftJoin(\DB::raw('(SELECT characterID, SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END ))) times FROM `logons` WHERE logonDateTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() group by characterid ) as `f`'),'id','=','f.characterID')
                    ->leftJoin(\DB::raw('(SELECT l1.*, l2.count_l  FROM `logons` l1 JOIN (SELECT characterID, MAX(logoffDateTime) maxdateoff,  max(logondatetime) maxdateon, SEC_TO_TIME(SUM(TIME_TO_SEC(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END ))) times, count(*) count_l FROM `logons` WHERE logonDateTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() GROUP BY characterid) l2 ON l2.characterid=l1.characterid AND l2.maxdateon=l1.logonDateTime AND l2.maxdateoff=l1.logoffDateTime  ) as `l`'),'id','=','l.characterID')
                    ->leftJoin('comments as t','t.id','=','c.id')
                    ->select(\DB::raw('DISTINCT CONCAT(name,"#",c.id) Pilot, title "Main", COALESCE(k.killsK,0) "K",
                                        COALESCE(d.killsD,0) "L", title "Title", startDateTime "Member since", f.times "Flight time",
                                        l.logonDateTime "Last login", COALESCE(p1.days,0) "Days in PVP", COALESCE(m.miss,0) "Missions",
                                        COALESCE(a.anom,0) "Anomalies", COALESCE(b.bounty,0) "Bounty", l.location "Last location", l.shiptype "Last ship",
                                        c.SS "SS", l.count_l "Logons", r.reward "Reward",0 Rating,t.comment "Comment"'))
                    ->orderBy('Missions','DESC')->limit(10);
        $table = $query->get();

        $badge="<span class='badge ";
        $types = array ('string','string','number','number','string','string','string','string','number','number','number','number','string','string','string','number','number','number','number');
        
        //while($r = mysql_fetch_assoc($table)) {
        foreach ($table as $rr) {
           $r = (array)$rr;
           if(!isset($google_JSON)){    
             $google_JSON = "{\"cols\": [";    
             $column = array_keys($r);
             foreach($column as $key=>$value){
                 $google_JSON_cols[]="{\"id\": \"".$key."\", \"label\": \"".$value."\", \"type\": \"".$types[$key]."\"}";
             }    
             $google_JSON .= implode(",",$google_JSON_cols)."],\"rows\": [";       
           }
//           $r['Pilot'] = str_replace("'", "",$r['Pilot']);
           $pilot=explode('#',$r['Pilot']);
           $pilotf = "<a  href='#{$pilot[1]}'>{$pilot[0]}</a>";
//           $query_m = 'SELECT name main, id main_id FROM `characters` WHERE id='.$r['Title'];
//           $table_m = mysqli_query($mysqli, $query_m);
//           if ($m = mysqli_fetch_assoc($table_m)) {
//           }
//               $main_name = $m['main'];
//               $main_id = $m['main_id'];
//               $mainf = "<a  href='#{$main_id}'>{$main_name}</a>";
               $main_name = $r['Title'];
               $main_id = "1";
               $mainf = "<a  href='#{$main_id}'>{$main_name}</a>";
           $pvp = $r['Days in PVP'];
        //   if ($p = mysqli_fetch_assoc($table_p) or die('5'.$config['db']['errormsg'].mysqli_connect_error())) $pvp = $p['Days in PVP'];
        
           $r['Bounty'] = (empty($r['Bounty']))?0:$r['Bounty']*7;
           $r['Reward'] = (empty($r['Reward']))?0:$r['Reward']*7;
           $r['SS'] = (empty($r['SS']))?0:$r['SS'];
           $r['Logons'] = (empty($r['Logons']))?0:$r['Logons'];
        
           $PVPf = ($pvp>1)?"badge-success'>&nbsp;{$pvp}":"'>&nbsp;{$pvp}"; $PVPf=$badge.$PVPf."&nbsp;</span>";
           $Mf = ($r['Missions'] / $days > 5 )?"badge-danger'>&nbsp;{$r['Missions']}":"'>&nbsp;{$r['Missions']}"; $Mf=$badge.$Mf."&nbsp;</span>";
           $Af = ($r['Anomalies'] / $days > 5 )?"badge-danger'>&nbsp;{$r['Anomalies']}":"'>&nbsp;{$r['Anomalies']}"; $Af=$badge.$Af."&nbsp;</span>";
           $Bf = "'>&nbsp;{$r['Bounty']}"; $Bf=$badge.$Bf."&nbsp;</span>";
           $Rf = "'>&nbsp;{$r['Reward']}"; $Rf=$badge.$Rf."&nbsp;</span>";
           $Kf = ($r['K']>0)?"badge-success'>&nbsp;{$r['K']}":"'>&nbsp;{$r['K']}"; $Kf=$badge.$Kf."&nbsp;</span>";
           $Lf = ($r['L']>0)?"badge-danger'>&nbsp;{$r['L']}":"'>&nbsp;{$r['L']}"; $Lf=$badge.$Lf."&nbsp;</span>";
           $titlef = mb_substr (preg_replace('/[^\w\p{Cyrillic} .,!?*-]/u','_',$r['Title']),0,36,'UTF-8');
        //   $last = new Date($r['Last login']).toJson;
        //   $last = JSON.stringify($r['Last login']);
           $last = $r['Last login'];
           $r['Flight time'] = ($r['Flight time'][0]==="-")?0:$r['Flight time'];
           $rating = $r['K'] * 10 - $r['L'] * 5 - $r['Missions'] * 3 - $r['Anomalies'] * 2 ;
           $ratingM = $rating*30/$days;
           $rating = $rating + 1000;
           if ($ratingM <= -1000 ) {
               $ratingF = "badge-danger'>&nbsp;{$rating}";
           } elseif (($ratingM >-1000)and($ratingM < -300)) {
               $ratingF = "badge-warning'>&nbsp;{$rating}";
           } elseif (($ratingM >500)and($ratingM <= 1000)) {
               $ratingF = "badge-success'>&nbsp;{$rating}";
           } elseif ($ratingM > 1000) {
               $ratingF = "badge-primary'>&nbsp;{$rating}";
           } else {
                $ratingF = "'>&nbsp;{$rating}";
           }
           $ratingF=$badge.$ratingF."&nbsp;</span>";
           $lp = $r['Reward'] * 4400;
//           $Rf=$badge.$Rf."&nbsp;</span>";
           $Rf=$lp;
           $google_JSON_rows[] = "{\"c\":[{\"v\": \"".strtoupper($pilot[0])."\", \"f\": \"$pilotf\"},{\"v\": \"".strtoupper($main_name)."\", \"f\": \"$mainf\"}, {\"v\": ".$r['K'].", \"f\": \"$Kf\"}, {\"v\": ".$r['L'].", \"f\": \"$Lf\"}, {\"v\": \"".$titlef."\"}, {\"v\": \"".$r['Member since']."\"}, {\"v\": \"".$r['Flight time']."\"}, {\"v\": \"".$last."\"}, {\"v\": ".$pvp.", \"f\": \"$PVPf\"}, {\"v\": ".$r['Missions'].", \"f\": \"$Mf\"}, {\"v\": ".$r['Anomalies'].", \"f\": \"$Af\"}, {\"v\": ".$r['Bounty'].", \"f\": \"".$r['Bounty']."\"}, {\"v\": \"".$r['Last location']."\"},{\"v\": \"".$r['Last ship']."\"},{\"v\": \"".$r['Comment']."\"}, {\"v\": ".$r['SS'].", \"f\": \"".$r['SS']."\"}, {\"v\": ".$r['Logons'].", \"f\": \"".$r['Logons']."\"},{\"v\": ".$rating.", \"f\": \"".$ratingF."\"},{\"v\": ".$lp.", \"f\": \"".$Rf."\"}]}";
        }    
        $data = $google_JSON.implode(",",$google_JSON_rows)."]}";
        return $data;
    }
    private function getdata_pilots()
    {
        $pilots = "";
        $query = \DB::table('characters')
                    ->select(\DB::raw('id,name'));
        $table = $query->get();
        foreach ($table as $p) {
            $pilots = $pilots."<div id='".$p->id."' class='".$p->name."'></div>";
        }
        return $pilots;
    }

    private function getdata_pilot($id,$days)
    {
        $query = \DB::table('characters')
                    ->where('id',$id)
                    ->select(\DB::raw('id,name,DATE_FORMAT(startDateTime,\'%e %b %Y\') start,roles,
(SELECT COUNT(*) FROM `kills` k WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND k.characterID=id AND k.characterID!=victimID) kills,
(SELECT COUNT(*) FROM `kills` k WHERE killTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() AND k.characterID=id AND k.characterID=victimID) looses'));
        $table = $query->first();
        $body = "<div class='container-fluid'><div class='row'><div class='col-md-8'><h3>".$table->name."</h3><p>In corp since ".$table->start."</p>";
        $success=($table->kills>0) ? " label-success" : "";
        $important=($table->looses>0) ? " label-important" : "";
        $kills=$table->kills;
        $looses=$table->looses;
        $urlname=urlencode($table->name);
        $body = $body."</div><div class='col-md-4'><div class='picture picture_128'><a href='http://evewho.com/pilot/".$urlname."' target='_blank'><img src='http://image.eveonline.com/Character/".$table->id."_128.jpg'></a></div><p><span class='label$success' id='kills'>$kills kills</span>&nbsp;&nbsp;<span class='label$important' id='looses'>$looses looses</span></p><p><a href='https://zkillboard.com/character/{$table->id}' target='_blank'>Killboard</a></p></div></div></div>";
        
        $query_l = \DB::table('logons')
                    ->where('characterid',$id)
                    ->select(\DB::raw('MAX(logonDateTime) last,SEC_TO_TIME(COALESCE(SUM(TIME_TO_SEC(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END )),0)) time,COUNT(*) count'));
        $table_l = $query_l->first();
        $body = $body."<p>Last login on ".$table_l->last."</p><p>Total flight time: ".$table_l->time."</p><p>Records count: ".$table_l->count."</p>"; //img
        $shiph="<th>Ship</th>";
        $body = $body."<table class='table table-bordered table-sm'><thead><tr><th>Logon time</th><th>Session</th><th>Location</th>".$shiph."</tr></thead><tbody>";        
        $query_l2 = \DB::table('logons')
                    ->where('characterid',$id)
                    ->whereBetween('logonDateTime',[\DB::raw('NOW() - INTERVAL '.$days.' DAY'),\DB::raw( 'NOW()')])
                    ->select(\DB::raw('logonDateTime,(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END) time,location,shipType'))
                    ->orderBy('logonDateTime','DESC');
        $table_l2 = $query_l2->get();
//        $table_l2 = mysqli_query($mysqli,'SELECT logonDateTime,(CASE WHEN TIMEDIFF(logoffDateTime,logonDateTime)>0 THEN TIMEDIFF(logoffDateTime,logonDateTime) ELSE 0 END) time,location,shipType FROM `logons` WHERE characterId='.$id.' AND logonDateTime BETWEEN NOW() - INTERVAL '.$days.' DAY AND NOW() ORDER BY logonDateTime DESC');
          foreach($table_l2 as $row) {
            $shipd="<td>{$row->shipType}</td>";
            $body = $body."<tr><td>{$row->logonDateTime}</td><td>{$row->time}</td>
                  <td>{$row->location}</td>".$shipd."</tr>";
          }
        $body = $body."</tbody></table>";
        return $body;
    }

    private function getdata_average()
    {
        $pvp_avg = \DB::table('characters as c')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) killsK FROM `kills` WHERE killTime BETWEEN NOW() - INTERVAL 30 DAY AND NOW() AND characterid!=victimid group by characterid ) as `k`'),'id','=','k.characterID')
                    ->avg('killsK');
        $miss_avg = \DB::table('characters as c')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) miss, COUNT(IF(location_id in (3017706,3016019,3016020,3016022,3016017,3016018,3016023,3016021,3016019),1,null)) missX, COUNT(IF(location_id=3017700,1,null)) missZ FROM `entries` WHERE date BETWEEN NOW() - INTERVAL 30 DAY AND NOW() AND refTypeId=33 group by characterid) as `m`'),'id','=','m.characterID')
                    ->avg('miss');
        $green_avg = \DB::table('characters as c')
                    ->leftJoin(\DB::raw('(SELECT characterID, COUNT(*) anom FROM `entries` WHERE date BETWEEN NOW() - INTERVAL 30 DAY AND NOW() AND refTypeId=85 AND amount>400000 group by characterid) as `a`'),'id','=','a.characterID')
                    ->avg('anom');
        return ['PVP'=>$pvp_avg,'Missions'=>$miss_avg,'Anomalies'=>$green_avg];
    }
    public function getDashboard($corpid,$days,$top)
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Auth::check()) {
            $id = Auth::user();
        } else {
            $id = '';
        }
//            Cache::forget('dash'.$id.$corpid.$days.$top);
//            Cache::forget('dash'.$id.$corpid.$days.$top);
        if (Cache::has('dash'.$id.$corpid.$days.$top)) {
            $chart_result = Cache::get('dash'.$id.$corpid.$days.$top);
        } else {
            $chart_result = $this->getdata2($corpid,$days,$top);
            Cache::put('dash'.$id.$corpid.$days.$top, $chart_result, 300);
        }
//        echo Cache::has('dash'.$id.$corpid.$days.$top);
        return $chart_result;
    }
    public function getBalance()
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        $chart_result = $this->getdata3();
//        echo dd($chart_result);
        return $chart_result;
    }
    public function getChart()
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Cache::has('chart')) {
            $chart_result = Cache::get('chart');
        } else {
            $chart_result = $this->getdata1();
            Cache::put('chart', $chart_result, 9600);
        }
        return $chart_result;
    }
    public function getPrime($corpid)
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Cache::has('prime')) {
            $chart_result = Cache::get('prime');
        } else {
            $chart_result = $this->getdata4($corpid);
            Cache::put('prime', $chart_result, 9600);
        }
        return $chart_result;
    }

    public function getAverage()
    {
//        if (!Request::ajax()) {
//            return "Wrong request";
//        }
        if (Cache::has('gauge')) {
            $chart_result = Cache::get('gauge');
        } else {
            $chart_result = $this->getdata_average();
            Cache::put('gauge', $chart_result, 3600);
        }
        return $chart_result;
    }
    
    public function getTopKills()
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Cache::has('topkills')) {
            $chart_result = Cache::get('topkills');
        } else {
            $chart_result = $this->getdata5();
            Cache::put('topkills', $chart_result, 2400);
        }
        return $chart_result;
    }
    

    public function getTopMissions()
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Cache::has('topmissions')) {
            $chart_result = Cache::get('topmissions');
        } else {
            $chart_result = $this->getdata6();
            Cache::put('topmissions', $chart_result, 2400);
        }
        return $chart_result;
    }
    
    public function getTopLP()
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Cache::has('toplp')) {
            $chart_result = Cache::get('toplp');
        } else {
            $chart_result = $this->getdata7();
            Cache::put('toplp', $chart_result, 2400);
        }
        return $chart_result;
    }

    public function getPilots()
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Cache::has('pilots')) {
            $chart_result = Cache::get('pilots');
        } else {
            $chart_result = $this->getdata_pilots();
            Cache::put('pilots', $chart_result, 300);
        }
        return $chart_result;
    }

    public function getPilot(Request $request,$id,$days)
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        if (Cache::has('pilot')) {
            $chart_result = Cache::get('pilot'.$id);
        } else {
            $chart_result = $this->getdata_pilot($id,$days);
            Cache::put('pilot'.$id, $chart_result, 300);
        }
        return $chart_result;
    }


    public function setComments($id,$comment)
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        $data =  \DB::table('comments')->updateOrInsert(
            ['id' => $id, 'comment' => $comment]
        );
        return response()->json(array('success' => true, 'last_insert_id' => $data), 200);
    }

    public function setAlts($id,$main_id)
    {
        if (!Request::ajax()) {
            return "Wrong request";
        }
        $data = 0;
        if ($id <> $main_id) {
            $data =  \DB::table('alts')->updateOrInsert(
                ['id' => $id], ['main_id' => $main_id]
            );
        }
        return response()->json(array('success' => true, 'last_insert_id' => $data), 200);
    }
    
}
