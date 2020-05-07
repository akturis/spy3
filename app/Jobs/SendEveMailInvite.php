<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\EveAuthController;
use App\Models\Invites;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SendEveMailInvite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $invite;
    protected $template;
 
    public $timeout = 70;
    public $tries = 5;
    public $retryAfter = 20;
/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invites $invite)
    {
        $this->invite = $invite;
//        $this->template = $template;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {    
        $eveauth = new EveAuthController();
        $recipients[] = ['recipient_id'=>$this->invite->character_id,'recipient_type'=>'character'];
        switch ($this->invite->template) {
            case '1':
                $body = "<font size=\"14\" color=\"#ffffffff\">"
                                        ."<b>Привет,</b> </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:1383//".$this->invite->character_id."\">".$this->invite->name."</a></loc></font>"
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
                $userid = '514674064';
                $refresh_token= 'b5AXAWrL2TVNdEBimZVa4kVSnoDLzxtIBloGlsnfhCk1';
                break;
            case '3':
                $body = "<font size=\"14\" color=\"#ffffffff\">"
                                        ."<b>Привет,</b> </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:1383//".$this->invite->character_id."\">".$this->invite->name."</a></loc></font>"
                                        ."<font size=\"14\" color=\"#ffffffff\">!<br></font><font size=\"12\" color=\"#bfffffff\"> <br></font><font size=\"14\" color=\"#bfffffff\">"
                                        ."Хочу предложить вступить в наше объединение, мы играем в EVE online уже 15 лет.<br>У нас многоуровневая организация поэтому можно выбрать любую из 3х корпораций в зависимости от опыта игры и квалификации<br>"
                                        ."<br> Письмо написано именно тебе так  как ты находишься в НПЦ корпорации, а следовательно высокая вероятность, что ты захочешь к нам присоединиться, да и н</font><font size=\"14\" color=\"#ff00ff00\">алог у нас меньше 11%</font><font size=\"14\" color=\"#bfffffff\">). <br>"
                                        ."<br>Мы набираем людей с разным уровнем игры, требования для вступления минимальны и не потребуют от тебя ничего сверхъестественного: быть адекватным, не сквернословить и по возможности участвовать в общих движухах. "
                                        ."<br>Наличие голосового чата дискорда на вылетах - обязательное условие. Общение в голосе - это единственный способ, максимальной и полноценной коммуникации в игре.<br>"
                                        ."<br>Для развития нам нужны новые игроки, которые помогут нам удержать и расширить территории которые мы уже имеем, а мы в свою очередь предоставим все возможности для интересной совместной игры и хорошего заработка (</font><font size=\"14\" color=\"#ff00ff00\"> майнерам на коветорах около 100 млн в час, агентранерам и плексранерам в районе 200 млн в час. при условии наличия омега статуса, альфа клоны зарабатывают раза в 4-5 меньше).</font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> У нас нет никаких скрытых сборов и комиссий , а те налоги которые мы собираем(5-7%) идут на содержание инфраструктуры и </font><font size=\"14\" color=\"#ff00ff00\">выплату компенсаций(до 75% стоимости корабля)</font><font size=\"14\" color=\"#bfffffff\"> при сливах на боевых вылетах. <br>"
                                        ."<br></font><font size=\"14\" color=\"#ff00ffff\"><b>Те кто только начал играть(или проживает в империи) - можно вступить в корпорацию -  </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2//98399497\">Red Cold Chili Banderlogs Academy</a></loc><br><br></font><font size=\"14\" color=\"#ff00ffff\">Для тех кто хочет попробовать пожить в нулях в регионе </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:3//10000023\">Pure Blind</a></font><font size=\"14\" color=\"#ff00ffff\"> в домашней системе  </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:5//30002005\">5ZXX-K</a></font>"
                                        ."<font size=\"14\" color=\"#ff00ffff\"> <loc> (есть клайм, нпц агенты </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:30//500016\">Servant Sisters of EVE</a></font><font size=\"14\" color=\"#ff00ffff\"> = </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:33469\">Astero Blueprint</a></font><font size=\"14\" color=\"#ff00ffff\">  и  </loc></font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:30//500018\">Mordu's Legion Command</a></font><font size=\"14\" color=\"#ff00ffff\"> = </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:33817\">Garmur Blueprint</a></font><font size=\"14\" color=\"#ff00ffff\">  <loc>) </loc> -  </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2//98520878\">Russian Space Community</a></loc>"
                                        ."<br><br></font><font size=\"14\" color=\"#ff00ffff\">И для опытных пвп пилотов    </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2//604035876\">Free Space Tech</a></loc></b>"
                                        ."<br><br></font><font size=\"14\" color=\"#bfffffff\">Вступление в </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2//98399497\">Red Cold Chili Banderlogs Academy</a></loc></font><font size=\"14\" color=\"#bfffffff\"> свободное. Нужно Кликнуть на название корпорации </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2//98399497\">Red Cold Chili Banderlogs Academy</a></loc></font><font size=\"14\" color=\"#bfffffff\"> снизу будет кнопка - Подать заявку."
                                        ."<br>После подачи заявку проверяйте статус приглашения в разделе \"Корпорация -&gt; Вербовка -&gt; Мои заявки\"<br><br>Вступить в </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2//98520878\">Russian Space Community</a></loc></font><font size=\"14\" color=\"#bfffffff\"> или </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2//604035876\">Free Space Tech</a></loc></font><font size=\"14\" color=\"#bfffffff\"> можно после собеседования в дискорде в ходе которого будет понятен уровень игры. <br>Нужно будет зайти в рекрутскую, рассказать в какую из корпораций вы хотите вступить и ответить на простые вопросы."
                                        ."<br><br>На все вопросы могу ответить в </font><font size=\"14\" color=\"#ffffe400\"><a href=\"https://discord.gg/xNhme66\">Дискорде</a></font><font size=\"14\" color=\"#bfffffff\"> или в дискорде в личку Lapsh banderlog#7317</font>";
                $post = array('approved_cost'=>0, 'recipients'=>$recipients,'body'=>$body,'subject'=>'Вступай в крупнейший русскоязычный альянс');
                $userid = '514674064';
                $refresh_token= 'b5AXAWrL2TVNdEBimZVa4kVSnoDLzxtIBloGlsnfhCk1';
                break;
            case '2':
                $body = "<font size=\"14\" color=\"#bfffffff\">"
                                        ."<b>Hello,</b> </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:1383//".$this->invite->character_id."\">".$this->invite->name."</a></loc></font>"
                                        ."<font size=\"14\" color=\"#ffffffff\">!<br></font><font size=\"12\" color=\"#bfffffff\"> <br></font><font size=\"14\" color=\"#bfffffff\">"
                                        ."<br>We are Banderlog <font size=\"14\" color=\"#ffd98d00\"><a href=\"recruitmentAd:98399496//130213\">Red Hot Chili Banderlogs Academy - RHCBA [Expeditionary Force]</a></font><font size=\"14\" color=\"#bfffffff\">,"
                                        ."the largest and oldest corporation of freelancers (free pilots) in EVE among the Russian community."
                                        ."<br>We welcome all professions, there is no system of orders and coercion, we are friendly for beginners."
                                        ."<br>We are a multi-project association: there are various projects, and the academy is the first step (there are no wars, and there are not many people living here, but in general we are many!), Therefore:"
                                        ."<br>You need to go into a discord, a recruiter and go through an interview. It is important that you do this from the very beginning! "
                                        ."(If I recruited you, then PM  </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:1377//2115010932\">OG Dead</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> or </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:1377//2115612565\">Vairotaka ''OG'' Rantavanen</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\">   or OG Dead#0383 -  </font><font size=\"14\" color=\"#ffffe400\">"
                                        ."<a href=\"https://discord.gg/xNhme66\">Discord</a></font><font size=\"14\" color=\"#bfffffff\"> room -  </font>"
                                        ."<font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:2//98593778\">Foreign Bodies</a></font><font size=\"14\" color=\"#bfffffff\"> "
                                        ."<br><br>= Entry Requirements -<br>1) The desire to play in a team:<br>2) Active participation (without fanaticism!) In [[PvP]] fleets or mining ops"
                                        ."<br>3) Compulsory relocation for permanent residence (we play a joint game):<br>4) The presence of voice chat -  </font>"
                                        ."<font size=\"14\" color=\"#ffffe400\"><a href=\"https://discord.gg/xNhme66\">Discord</a></font><font size=\"14\" color=\"#bfffffff\"> and headphones"
                                        ."<br><br>== 0 training ==<br>5) Duration of training - until you mature:"
                                        ."<br>6) There is a collection of useful guides, videos at your disposal for independent knowledge of the basics of the game:"
                                        ."<br>7) We will answer all your questions;<br>8) Regular training sessions and fleets under the guidance of experienced alliance pilots;"
                                        ."<br>9) Professional driving courses for warships in various directions;<br><br>== First step: ==<br>10) Without any obligation on your part you are joining the </font>"
                                        ."<font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:2//98399496\">Red Hot Chili Banderlogs Academy</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> (no war), We want:<br>Active players have developed skills, and if someone is not interested or does not put in the time, then we will wait until you become"
                                        ."<br>11) do  </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"openCareerAgents:\">Run Career Agents for ISK and free ships</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> <br>12) See  </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:1378//3019356\">Sister Alitura</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> - see about it on </font><font size=\"14\" color=\"#ffffe400\"><loc><a href=\"https://www.youtube.com/watch?v=Eiyc9OvFMyc\">youtube</a></loc>"
                                        ."<br></font><font size=\"14\" color=\"#bfffffff\">13) If interested, there will be time going to systems:<br></font><font size=\"14\" color=\"#ffd98d00\"><loc>"
                                        ."<a href=\"showinfo:1529//60012670\">Airaken V - Moon 1 - Sisters of EVE Academy</a></loc></font><font size=\"14\" color=\"#bfffffff\"> / </font>"
                                        ."<font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:2502//60012667\">Osmon II - Moon 1 - Sisters of EVE Bureau</a></loc>"
                                        ."<br></font><font size=\"14\" color=\"#bfffffff\">do lvl 1-2 </font><font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:30//500016\">Servant Sisters of EVE</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> agents<br><br>Fits:<br>need to use  </font><font size=\"14\" color=\"#ffd98d00\">"
                                        ."<a href=\"fitting:593:2048;1:31716;1:6437;1:8433;1:31752;1:41034;1:11563;1:26929;1:5973;1:2454;8::\">Tristan82/50км/5к/2325</a></font><font size=\"14\" color=\"#bfffffff\"> - &gt;  </font>"
                                        ."<font size=\"14\" color=\"#ffd98d00\"><a href=\"fitting:32872:2048;1:31716;1:31752;1:4393;2:8433;2:6003;1:7253;5:32025;1:2454;2:2183;5:222;1000::\">Algos</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\">  - &gt; </font><font size=\"14\" color=\"#ffd98d00\">"
                                        ."<a href=\"fitting:17843:4833;1:32071;1:35659;1:31047;1:23527;1:5839;1:6160;1:4435;1:4405;3:32027;1:4573;2:2464;5:15508;5:31878;5:29009;2:263;32:11289;14::\">Vexor NI</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> <br><br>== Second stage ==<br>15) The desire to develop in the place of our predominantly based system is </font><font size=\"14\" color=\"#ffd98d00\"><loc>"
                                        ."<a href=\"showinfo:5//30001978\">X-7OMU</a></loc></font><font size=\"14\" color=\"#bfffffff\">, in  </font><font size=\"14\" color=\"#ffd98d00\">"
                                        ."<a href=\"showinfo:1529//60014056\">X-7OMU II - Moon 7 - The Sanctuary School</a></font><font size=\"14\" color=\"#bfffffff\"> "
                                        ."<br>There are agents too, there is also something to mine and all this is 2-3 times more profitable than in the empire.<br>You then need to submit an application to </font>"
                                        ."<font size=\"14\" color=\"#ffd98d00\"><a href=\"showinfo:2//98593778\">Foreign Bodies</a></font><font size=\"14\" color=\"#bfffffff\"> (It is important to go because only in this case you will have access to everything"
                                        ."<br>and our allies will not shoot you) and pass another interview in a  </font><font size=\"14\" color=\"#ffffe400\"><a href=\"https://discord.gg/xNhme66\">Discord</a></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> .<br><br>== Third stage ==<br>After advanced training/experience//skills, you can perform fleet ops properly to do this, youll need to - "
                                        ."<br>16) Be able to correctly pilot / or at least be able to from the following ships:<br>17) </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:12098\">Interdictor</a></loc><br></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\">18) </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:632\">Blackbird</a></loc></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> with T2 jammers followed by </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:640\">scorpion</a></loc></font>"
                                        ."<font size=\"14\" color=\"#bfffffff\"> / </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:11957\">falcon</a></loc></font><font size=\"14\" color=\"#bfffffff\">:"
                                        ."<br>19) </font><font size=\"14\" color=\"#ffd98d00\"><loc><a href=\"showinfo:33098\">Battlecruiser</a></loc></font><font size=\"14\" color=\"#bfffffff\"> with T2 down, followed by going to t3 destroyer"
                                        ."<br><br>I'm a fucking carebear!!<br>   (''') о___о (''')<br>   ..\\ '( о_о )' /<br>   ....\\ \\_Ш_/ /<br>   ......l . . . . |<br>   ...../ ./\"U\"\\. \\<br>   ..(„„„)___(„„„)<br>** DoN't SHoOT!! **<br><br>   </font>";
                $userid = '2115612565';
                $refresh_token= 'No30yog2-hDtk36mOelfEKwS_HxjRGQZupd0IHQI1iM';
                $post = array('approved_cost'=>0, 'recipients'=>$recipients,'body'=>$body,'subject'=>'Join the Oldest Alliance');
                break;
        }
        $options['body'] = json_encode($post,true);
        $options['headers']['content-type'] = 'application/json';
        if($this->invite->invited==false){
            $response = $eveauth->sendmail($userid,$refresh_token,$options);
            if($response&&$response<>520) {
                $this->invite->invited = true;
                $this->invite->save();
            } else {
                $this->release(30);
            }
        }
    }
    public function failed($exception)
    {    
        echo $exception;
    }    

}
