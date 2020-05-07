/*google.load('visualization', '1.0', {'packages':['table', "corechart"]});
google.load('visualization', '1.0', {'packages':['controls']});
google.load('visualization', '1.0', {'packages':['calendar']});
*/
google.charts.load('current', {'packages':['gauge','table', 'corechart','controls','calendar']});
google.charts.setOnLoadCallback(drawIt);

var corpid = 'All';
var pages = 50;
var days = 'Default';
var selectedUserId='';
var parse_pilots = [""];
var lChart;
var suid;
var top_="none";
var fleetup = 1;
var gauge;

/*function getAccess() {
    $.ajax({
     url: "main/getAccess.php",
     async: true,
     success: function(data) {
         return $.trim(data);
//       var result = !$.trim(data);
//       suid = $.trim(data);
//       if (result) return 0; else return 1;
     }
    });
}
*/

$.urlParam = function(name){
	        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	        if (results===null) {
	            return 0;
	        } else {
        	  return results[1] || 0;
	        }
};        

$.urlP = function(name,url){
	        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(url);
	        if (results===null) {
	            return 0;
	        } else {
        	  return results[1] || 0;
	        }
};        


function drawDashboard() {
        // Create a dashboard.
        var dashboard = new google.visualization.Dashboard(
            document.getElementById('dashboard_div'));

        // Create a range slider, passing some options
        var donutRangeSlider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div',
          'options': {
            'filterColumnLabel': 'K'
          }
        });
        var curr = new Date();
        var low = new Date(curr.getTime()-7*24*3600*1000);
        var donutRangeSliderDate = new google.visualization.ControlWrapper({
          'controlType': 'DateRangeFilter',
          'containerId': 'filter_div1',
//          'state': {'lowValue': 3, 'highValue': 8},
          'options': {
            'filterColumnLabel': 'Start date',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            lowvalue: low, highvalue: curr
          }
        });
        var donutLastDate = new google.visualization.ControlWrapper({
          'controlType': 'DateRangeFilter',
          'containerId': 'filter_div2',
//          'state': {'lowValue': 3, 'highValue': 8},
          'options': {
            'filterColumnLabel': 'Last login',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });
        var donutDayinPVP = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div3',
//          'state': {'lowValue': 3, 'highValue': 8},
          'options': {
            'filterColumnLabel': 'Days in PVP',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });
        var donutM = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div4',
          'options': {
            'filterColumnLabel': 'Missions',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });
        var donutA = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div5',
          'options': {
            'filterColumnLabel': 'Green',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });
        var donutB = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div6',
          'options': {
            'filterColumnLabel': 'Bounty',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });

        var donutRating = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div7',
          'options': {
            'filterColumnLabel': 'Rating',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });
        
        var donutSS = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div8',
          'options': {
            'filterColumnLabel': 'SS',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });

        var donutL = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'filter_div9',
          'options': {
            'filterColumnLabel': 'Logons',
            'ui': { 
                allowTyping: false,
				allowMultiple: true,
				electedValuesLayout: 'belowStacked'
            }
          },
          state: {
            value: ''
          }
        });

        var donutString = new google.visualization.ControlWrapper({
          'controlType': 'StringFilter',
          'containerId': 'filter_div10',
          'options': {
            'filterColumnIndex': 0,
            'matchType':'any',
             'ui': { 
              'label': 'Pilot :'
            }
          },
          state: {
            value: ''
          }
        });

        var donutStringShip = new google.visualization.ControlWrapper({
          'controlType': 'StringFilter',
          'containerId': 'filter_div11',
          'options': {
            'filterColumnIndex': 12,
            'matchType':'any',
             'ui': { 
              'label': 'Ship :'
            }
          },
          state: {
            value: ''
          }
        });
        var donutLocation = new google.visualization.ControlWrapper({
          'controlType': 'StringFilter',
          'containerId': 'filter_div12',
          'options': {
            'filterColumnLabel': 'Last location',
            'interpolateNulls': 'true',
            'matchType':'any',
             'ui': { 
              'label': 'Last location :'
            }
          },
          state: {
            value: ''
          }
        });

        $.urlParam = function(name){
	        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	        if (results===null) {
	            return 0;
	        } else {
        	  return results[1] || 0;
	        }
        };        
//        $days = ( $.urlParam("days") === 0 ) ? 30 : $.urlParam("days") ;
        pages = ( pages == 'All' ) ? 5000 : pages;
        days = ( days == 'Default' ) ? 30 : days;
        // Create our data table.
//        var jsonData = $.ajax({
        $.ajax({
          url: "main/dashboard/corpid/"+corpid+"/days/"+days+"/top/"+top_,
          dataType:"json",
          async: true,
          beforeSend: function() { $('#wait').show(); },
          complete: function(data) {  },
          error: function(data) {
             $('#wait').hide();
             console.log(data);
          }
//        }).responseText;
        }).done( function(jsonData) {
          $('#wait').hide();
          var data = new google.visualization.DataTable(jsonData);
          var formatter_medium = new google.visualization.DateFormat({formatType: 'short'});
          // Reformat our data.
          formatter_medium.format(data, 5);
          var formatter_2 = new google.visualization.PatternFormat("<span>{0}</span>");
          formatter_2.format(data, [14]);
          var view= new google.visualization.DataView(data);
          view.setColumns([0,1,2,3,{column:5, calc:StringToDate, type:'date', label:'Start date',format: 'YY'},6,
//          {column:7, calc:StringToDate2, type:'date', label:'Last login'},8,9,10,11,12,13,14,15,16,{column:17, calc:setRating, type:'number', label:'Rating'}]);
//          {column:7, calc:StringToDate2, type:'date', label:'Last login'},8,9,10,11,12,13,14,15,16,17,18]);
          {column:7, calc:StringToDate2, type:'date', label:'Last login'},8,9,10,11,12,13,14,15,16,17,18,{column:21, calc:setRating, type:'number', label:'Rating'},19,20]);
          var formatterC = new google.visualization.ColorFormat();
          formatterC.addRange(1001, 100000, 'red', 'white');
          formatterC.format(data, 16); // Apply formatter to second column
        var cssClassNames = {
        'headerRow': 'large-font',
        'tableRow': 'small-font',
        'oddTableRow': 'small-font',
        'selectedTableRow': 'dummy',
        'hoverTableRow': 'tableHover',
        'headerCell': 'dummy',
        'tableCell': 'demo',
        'rowNumberCell': 'dummy'};

        var options = {'showRowNumber': true, 'allowHtml': true, 
            'cssClassNames': cssClassNames,  sortAscending:false,
            page: 'enable',
            pageSize: pages,
            format: 'short'
        };
        var table = new google.visualization.ChartWrapper({
            chartType: 'Table',
            dataTable:view,
            containerId: 'membersTable',
            options:options
//            view: { columns: [0,1,2,3,{column:4, calc:StringToDate, type:'date', label:'Start date'},5,6] }
        });
        function StringToDate(dt,row) {
            var formatter3 = new google.visualization.DateFormat({pattern: "EEE, MMM d, ''yy"});
            var dateArr = dt.getValue(row,5).split('-');
            var year = parseInt(dateArr[0]); 
            var month = parseInt(dateArr[1])-1; 
            var day = parseInt(dateArr[2]);
            var date = new Date(year,month,day);
            return date;
        }
        function StringToDate2(dt,row) {
            var dateArr = dt.getValue(row,7).split('-');
            var year = parseInt(dateArr[0]); 
            var month = parseInt(dateArr[1])-1; 
            var day = parseInt(dateArr[2]); 
            return new Date(year,month,day);
        }
        function setRating(dt,row) {
            var rating = parseInt(dt.getValue(row,2)) * 10 - parseInt(dt.getValue(row,3)) * 5 - parseInt(dt.getValue(row,9)) * 3 - parseInt(dt.getValue(row,10)) * 2 + 1000 ;
            return rating;
        }
        var addListener = google.visualization.events.addListener;
        var addListener2 = google.visualization.events.addListener;
        addListener(table, 'ready', function() { 
            google.visualization.events.removeListener(addListener);
            $('#membersTable').on('click', 'a', function(e){ 
                e.preventDefault(); 
                showSheet($(this).attr('href').replace('#', ''),days);
                return false;
            }); 
            $('#count').html("Count: <span class='badge badge-secondary'>"+table.getDataTable().getNumberOfRows()+'</span>');
        });
        dashboard.bind([donutRangeSlider,donutRangeSliderDate,donutLastDate,donutDayinPVP,donutM,donutA,donutB,donutRating,donutSS,donutL,donutString,donutStringShip,donutLocation], table);
       dashboard.draw(view);
        });
      }

function drawChart() {
  // Create and populate the data table.
  $.ajax({
    url: "main/chart",
    dataType:"json",
     beforeSend: function() { $('#wait2').show(); },
     complete: function() {  },
     error: function(data) {
       $('#wait').hide();
       console.log(data);
     },
    async: true
  }).done( function(jsonData) {
      var data = new google.visualization.DataTable(jsonData);
    
      // Create and draw the visualization.
      new google.visualization.LineChart(document.getElementById('onlineChart')).
          draw(data, {
                      theme: "maximized",
                      legend: "none",
                      curveType: "function",
                      colors:['black','green','red'],
                      focusTarget: "category",
                      lineWidth: 3,
                      pointSize: 4,
    		  height: 150}
              );
      $('#wait2').hide();
  });
}


function drawChartBalance() {
//    $.ajax({
//     url: "main/getAccess.php",
//     async: false,
//     success: function(_data) {
      if(lChart!=undefined) lChart.clearChart();
//      if ($.trim(_data) === "1") {
          $.ajax({
            url: "main/balance/corpid/"+corpid,
            dataType:"json",
             beforeSend: function() { $('#wait3').show(); },
             complete: function() { $('#wait3').hide(); },
            async: true
          }).done( function(jsonData) {
        
              var data = new google.visualization.DataTable(jsonData);
            
              // Create and draw the visualization.
              lChart = new google.visualization.ColumnChart(document.getElementById('Balance'));
                  lChart.draw(data, {
                              theme: "maximized",
                              legend: "none",
    //                          curveType: "function",
                              colors:['green','blue','red'],
                              focusTarget: "category",
                              lineWidth: 3,
                              pointSize: 4,
                              width: 1000,
            		  height: 250}
                      );
               
             });
//     } 
//    });  // Create and populate the data table.
}

function drawChartPrime() {
  // Create and populate the data table.
  $.ajax({
    url: "main/prime/corpid/"+corpid,
    dataType:"json",
     beforeSend: function() { $('#wait4').show(); },
     complete: function() { $('#wait4').hide(); },
    async: true
  }).done( function(jsonData) {

      var data = new google.visualization.DataTable(jsonData);
    
      // Create and draw the visualization.
      new google.visualization.LineChart(document.getElementById('Prime')).
          draw(data, {
                      theme: "maximized",
                      legend: "none",
                      curveType: "function",
                      colors:['blue','red','green'],
                      focusTarget: "category",
                      lineWidth: 3,
                      pointSize: 4,
                      width: 1000,
    		  height: 250}
              );
  });
}

function drawAverage() {
  // Create and populate the data table.
  $.ajax({
    url: "main/average",
     beforeSend: function() { $('#wait4').show(); },
     complete: function() { $('#wait4').hide(); },
    async: true
  }).done( function(jsonData) {

//      var gaugeOptions = {min: 0, max: 100, yellowFrom: 50, yellowTo: 80,
//          greenFrom: 80, greenTo: 100, redFrom: 0, redTo: 50, minorTicks: 5, animation: { duration:1000 } };
      var gaugeOptions = {min: 0, minorTicks: 5, animation: { duration:1000, easing:'out' } };

//      gaugeData = new google.visualization.DataTable();
//      gaugeData.addColumn('number', 'PVP');
//      gaugeData.addColumn('number', 'PVE');
//      gaugeData.addRows(2);
//      gaugeData.setCell(0, 0, 0);
//      gaugeData.setCell(0, 1, 0);
        var gaugeData = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Kills', 0],
          ['Missions', 0],
          ['Anomalies', 0]
        ]);
    
      // Create and draw the visualization.
      var gauge = new google.visualization.Gauge(document.getElementById('Gauge'));
      gauge.draw(gaugeData, gaugeOptions);
        var gaugeData = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['Kills', parseInt(jsonData.PVP,0)],
          ['Missions', parseInt(jsonData.Missions,0)],
          ['Anomalies', parseInt(jsonData.Anomalies,0)]
        ]);
      gauge.draw(gaugeData, gaugeOptions);
  });
}

function drawIt() {
  var route = $(location).attr('pathname');
  if ( route == '/stats') {
     drawChart();
     drawChartBalance();
     drawChartPrime();
     drawAverage();
  } else {
    drawDashboard();
  }
}

function showSheet(id,days=30,text='') {
  $.ajax({
    url: "main/getSheet.php",
    data: {id: id, text: text, days: days},
    success: function(data) {
      $('.modal-body').html(data);
      $('.modal').modal();
    }
  });
}

function drawBottom() {
    $.ajax({
     url: "main/getBottom.php",
     beforeSend: function() { $('#wait').show(); },
     complete: function() { $('#wait').hide(); },
     success: function(data) {
       $('#bottom').html(data);
       $('a[rel=bottomtip]').tooltip({placement:'right'});
       $('#bottom a').click(function(e){ e.preventDefault(); showSheet($(this).attr('href').replace('#', '')); });
     }
    });
}

function drawOptions() {
}

function auth (p) {
  if (typeof p == "undefined") {
    p = "";
  }
  $.ajax({
  url: "main/auth.php?"+p,
  success: function(data) {
    if (data!=0) {
      $('#status').text(data);
      $('#login').toggle();
      drawOptions();
      drawDashboard();
      drawChartBalance();
    }
  }
  });
}

function login (p) {
  $.ajax({
  type: "POST",
  url: "main/login.php?u="+usr.value+"&p="+pwd.value,
  success: function(data,e) {
     console.log(data);
     suid = $.trim(data);

      getButton();
      drawOptions();
      drawDashboard();
      drawChartBalance();
  }
  });
}
function logout () {
  $.ajax({
  url: "main/logout.php",
  success: function(data) {
      suid="";

      getButton();
      drawOptions();
      drawDashboard();
      drawChartBalance();
  }
  });
}
function check_sso() {
    $.ajax({
     url: "main/sso_super.php",
     async:false,
     success: function(data) {
       $('.login-sso').html(data);
        $("#ssoSelectButton").click(function(e){
    //            var hiddenSection = $('#ssoSelect').parents('section.hidden');
                var scopes = [];
    //            $('input[name="scopes"]:checked').each(function(){scopes.push($(this).val())});
                var array={action:"ssoLogin", scopes:scopes};
                $.ajax({
                    type: 'POST',
                    url: 'main/sso.php?t=' + new Date().getTime(),
                    data: array,
                    async: true,
                    success: function (data, textStatus, XHR) {
                        data=$.parseJSON(data);
                        window.location.href=data.url;
                    }
                });
        });
        if ($.urlParam("code")!=0) {
            $.ajax({
                url: "main/sso_login.php?code="+$.urlParam("code"),
                success: function(data) {
                  window.location = '/';
                }
            });
        }
     }
    });    
}
function getButton() {
    check_sso();
    $.ajax({
     url: "main/super.php",
     success: function(data) {
       $('.login-form').html(data);
       $('#submit').click(function(p){
         p.preventDefault();
         login(p);
       });
       $('#logout').click(function(){
         $("#ssoSelectButton").show();
         logout();
       });
     }
    });    
/*    $.ajax({
     url: "main/getAccess.php",
     async: false,
     success: function(_data) {
      if ($.trim(_data) === "1") {
        $('#comment_div').show();
        $('#Balance').show();
      } else {
        $('#comment_div').hide();
        $('#Balance').hide();
      }
     } 
    });  
*/
}
function getCount(){
    $.ajax({
     url: "main/getCount.php?corpid="+corpid,
     beforeSend: function() { $('#wait1').show(); },
     complete: function() { $('#wait1').hide(); },
     success: function(data) {
       $('.counter').html(data);
     }
    });
}

!function ($) {
  $(function(){
    var $window = $(window)
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });    
//    $.ajax({
//     url: "main/getCorp.php",
//     success: function(data) {
//       $('#corp2').html(data);
//        $('.dropdown-toggle').dropdown();
//          $("li a").click(function(){
//    //           $('#nav li').removeClass();
//    //           $(this).parent().addClass('active');   
//               $('#corp').text($(this).text());   
//          });
////        $('#fsp-t').click(function(){
//        $('ul.dropdown-menu li a').click(function(){
//           corpid = '604035876'; 
//           corpid = $(this).attr("id"); 
//           getButton();
//           drawOptions();
//           drawDashboard();
//           drawChartBalance();
//           drawChartPrime();
//           getCount();
//        });
//     }
//    });
    getCount();
    $.ajax({
     url: "main/getpilots",
     success: function(data) {
       $('#charlist').html(data);
//        days = ( $.urlParam("days") === 0 ) ? 30 : $.urlParam("days") ;
       var pilots = [];
       $("#charlist").find("div").each(function(){ pilots.push($(this).attr('class')); });
       $('.search-query').typeahead({source:pilots});
       $('.search-query').keypress(function(event) {
         if ( event.which == 13 ) {
           event.preventDefault();
           pid=$("[class='"+$(this).val()+"']").attr("id");
           if (pid) showSheet(pid,days);
           return;
         }
       });
       $('.search-query').change(function(){
         if (event.type=='change') {
           pid=$("[class='"+$('li.active').attr('data-value')+"']").attr("id");
           if (pid) showSheet(pid,days);
         }
       });
       $('.search').typeahead({source:pilots});
       $('.search').change(function(){
         if (event.type=='change') {
           pid=$("[class='"+$('li.active').attr('data-value')+"']").attr("id");
//           if (pid) showSheet(pid,$days);
         }
       });
/*       $.ajax({
        url: "main/getAccess.php",
        async: false,
        success: function(_data) {
            if ($.trim(_data) === "1") {
               $('#comment').click(function(){
                   pid=$("[class='"+$('li.active').attr('data-value')+"']").attr("id");
                   $.ajax({
                     type: "POST",
                     url: "main/setComment.php?id="+pid+"&comment="+$('.comment').val(),
                     beforesend: function() {  },
                     success: function(data) {
                         drawDashboard();
                     }
                   });
               });
            }
        } 
       });
*/

     }
    });
               $('#comment').click(function(){
                   pid=$("[class='"+$('li.active').data('value')+"']").attr("id");
                   $.ajax({
                     type: "POST",
                     url: "main/setComment/"+pid+"/"+$('.comment').val(),
                     beforesend: function() {  },
                     success: function(data) {
                         drawDashboard();
                     },
                      error: function(data) {
                         $('#wait').hide();
                         console.log(data);
                      }
                   });
               });
               $('#alts').click(function(){
                   pid=$("[class='"+$('#alt_id')[0].value+"']").attr("id");
                   main_pid=$("[class='"+$('#main_id')[0].value+"']").attr("id");
                   $.ajax({
                     type: "POST",
                     url: "main/setAlts/"+pid+"/"+main_pid,
                     beforesend: function() {  },
                     error: function(data) {
                         $('#wait').hide();
                         console.log(data);
                     },
                     success: function(data) {
                         drawDashboard();
                     }
                   });
               });
               $('#getparse').click(function(){
                   parse_pilots = $('.pilots').val();
                  drawDashboard();
               });
               $('#fleetup').click(function(e){
                 if($(this).is(":checked")) {
                    fleetup = 0;
                 } else {
                    fleetup = 1; 
                 }
//                 e.preventDefault();
                 drawDashboard();
               });
               $('#clear').click(function(){
                   $('.pilots').val('');
                   parse_pilots = $('.pilots').val();
//                   parse_pilots = $('.pilots').val().split("\n");
                   drawDashboard();
               });
    
//    getButton();
        $("#ssoSelectButton").click(function(e){
    //            var hiddenSection = $('#ssoSelect').parents('section.hidden');
                var scopes = [];
    //            $('input[name="scopes"]:checked').each(function(){scopes.push($(this).val())});
                var array={action:"ssoLogin", scopes:scopes};
                $.ajax({
                    type: 'POST',
                    url: 'main/sso.php?t=' + new Date().getTime(),
                    data: array,
                    async: true,
                    success: function (data, textStatus, XHR) {
                        data=$.parseJSON(data);
                        window.location.href=data.url;
                    }
                });
        });
        if ($.urlParam("code")!=0) {
            $.ajax({
                url: "main/sso_login.php?code="+$.urlParam("code"),
                success: function(data) {
                  window.location = '/';
                }
            });
        }

    $('#corp').change(function(){ 
        corpid = $(this).val();
           drawDashboard();
           drawChartBalance();
           drawChartPrime();
    });    
    $('#pages').change(function(){ 
        pages = $(this).val();
           drawDashboard();
           drawChartBalance();
           drawChartPrime();
    });    
    $('#days').change(function(){ 
        days = $(this).val();
           drawDashboard();
    });    
    $('#top').change(function(){ 
        top_ = $(this).val();
        drawDashboard();
    });    
    
    $('#corp .dropdown-toggle').dropdown();
      $("li a").click(function(){
//           $('#nav li').removeClass();
//           $(this).parent().addClass('active');   
           $('#corp').text($(this).text());   
      });
  })
}(window.jQuery)
