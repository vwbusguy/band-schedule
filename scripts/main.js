function addEvent(date){
	$.ajax({
		type: "POST",
		url: '/services/addEvent.php',
		aync:false,
		data: { date: date},
		dataType: 'JSON'
		}).done(function(data){
			return data;
		});
}

function chgEventStatus(eventid,stat){
        $.post('/services/chgEventStatus.php', {eventid: eventid, status: stat},
                function(data){
                        if (data.status == 'ok'){
                                document.location.reload(true);
                        }
                        else{
                                alert('Could not change event status: ' + data.message);
                        }
                },
                "json");
}


function chgUserEventStatus(eventid,stat){
        $.post('/services/chgUserEventStatus.php', {eventid: eventid, stat: stat},
                function(data){
                        if (data.status == 'ok'){
                                document.location.reload(true);
                        }
                        else{
                                alert('Could not change status: ' + data.message);
                        }
                },
                "json");
}


function cnfEvent(username,eventid,btnobject){
	$.ajax({
		type: "POST",
		url: '/services/resEvent.php',
		async: false,
		data: { username: username,
			eventid: eventid,
			type: "confirm"},
		dataType: 'JSON'
		}).done(function(data){
			setEventConfirm(data,btnobject);
		});
}

function delEvent(eventid){
        $.post('/services/delEvent.php', {eventid: eventid},
                function(data){
                        if (data.status == 'ok'){
                                window.location.href = '/events.php';
                        }
                        else{
                                alert('Could not change event status: ' + data.message);
                        }
                },
                "json");
}


function email(recipient,subject,message){
	$.ajax({
        	type: "POST",
        	url: '/services/mailUser.php',
		async: false,
        	data: { recipient: recipient,
                	subject: subject,
                	message: message
                	},
        	dataType: "JSON",
       	 	success: function(data){
                	if (data.status != 'ok'){
                                alert('Could not send email: ' + data.message);
                        }
                }});
}

function getEventEmails(eventid,callback){
	$.ajax({
		url: '/services/getEventEmails.php',
		async: false,
		type: 'GET',
		data: {eventid : eventid},
		dataType: 'json',
		success:function(data){
			if (data.status == 'ok'){
				if (data.data !== null){
					callback(data.data);
				}
			}else{
				alert("Could not get event emails due to: " + data.message);
			}
		}
	});
}

function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
        vars[key] = value;
    });
    return vars;
}

function getUserLevel(callback){
	$.ajax({
        	url: '../services/getUserLevel.php',
        	async: false,
        	type: 'GET',
        	dataType: 'json',
        	success:function(data){callback(data)}
	});
}

function sendPracticeEmail(emails){
        day = $('#datPractice').val();
        time = $('#timPractice').val();
	event_date = $('h1').text();
	for (i=0; i < emails.length;i++){
		email(emails[i],'Practice update for ' + event_date, 'The practice time has been updated for the event on '
		+ event_date + '.  Practice will be held on ' + day + ' at ' + time + '.  This email is automatically '
		+ 'generated.  Do not reply to this email.'); 
	}
}

function setEventConfirm(result,btnobject){
	eventid = $(btnobject).val();
        if (result.status == "success"){
                $('#divstat' + eventid).text('You are scheduled for this event.');
		$(btnobject).replaceWith("<a href='/event.php?id=" + eventid + "'><button class='btn btn-success btnDetails' id='btnDetail" + eventid + "' value='" + eventid + "' type='button'>Details</button></a>");
        }else{
                $('#divstat' + eventid).addClass('error').text("There was an error confirming your reservation.");
        }

}

function setLeader(eventid,leaderid){
	$.post('/services/chgLeader.php', {eventid: eventid, leaderid: leaderid},
		function(data){
                        if (data.status == 'ok'){
                                document.location.reload(true);
                        }
                        else{
                                alert('Could not change leader: ' + data.message);
                        }
		},
		"json");
}

function setPractice(eventid,practice){
        $.post('/services/chgPractice.php', {eventid: eventid, date: practice},
                function(data){
                        if (data.status == 'ok'){
				getEventEmails(eventid,sendPracticeEmail);
                                document.location.reload(true);
                        }
                        else{
                                alert('Could not change date: ' + data.message);
                        }
                },
                "json");
}



function setSession(username){
        $.post('/services/setSession.php', {username: username},
                function(data){
                        if (data.status == 'ok'){
                                window.location.href = "/events.php";
                        }
                        else{
                                alert('Could not set session!');
                        }
                },
                "json");

}

function setUsername(){
	$.get('/services/getCurUser.php',function(data){
		window.curUser = data.username;
	},"json");
}

function setCookie(username){
        $.cookie('username',username, {expires: 7, path: '/'});
}

function valAlphaNum(string){
	crit = /^[A-Za-z0-9]+$/;
	if (!crit.test(string)){
		return false;
	}else{
		return true;
	}
}


$(document).ready(function(){

$('.btnEventConfirm').click(function(e){
	e.preventDefault();
	eventid = $(this).val();
	setUsername();
	result = cnfEvent(window.curUser,eventid,$(this));
});

$('#selChgEvLead').change(function(e){
	e.preventDefault();
	eventid = getUrlVars()['id'];
	leaderid = $('#selChgEvLead').val();
	result = setLeader(eventid,leaderid);
});

$('#selChgEvStat').change(function(e){
        e.preventDefault();
        eventid = getUrlVars()['id'];
        stat = $('#selChgEvStat').val();
        result = chgEventStatus(eventid,stat);
});


$('select#selChgEvStatUsr').change(function(e){
	e.preventDefault();
	eventid = getUrlVars()['id'];
	stat = $('#selChgEvStatUsr').val();
	result = chgUserEventStatus(eventid,stat);
});

$('div#chgEvPractice input').blur(function(e){
        e.preventDefault();
        eventid = getUrlVars()['id'];
        day = $('#datPractice').val();
	time = $('#timPractice').val();
	if (day.length > 9){
		practice = "'" + day + ' ' + time + ":00'";
        	result = setPractice(eventid,practice);
	}
});

$('.btnDelEv').click(function(e){
	e.preventDefault();
	eventid = getUrlVars()['id'];
	if (confirm("Deleting an event cannot be undone.  If not sure, consider cancelling it instead.  Continue?")){
		result = delEvent(eventid);
	}
});

$('.datepicker').datepicker({
	format:'yyyy-mm-dd',
	weekStart:0,
	viewMode:0,
	altField:"#date"
	}).on('changeDate', function(ev){
		var newEventDate = ev.date.valueOf();
	});

$('.timepicker').timepicker({
	minuteStep: 15,
	defaultTime: '11:45',
	showMeridian: false,
	showInputs: false,
	disableFocus: true
	});

$('#login').submit(function(e){
        e.preventDefault();
        username = $('#username').val();
        password = $.md5($('#password').val());
	if (valAlphaNum(username) === true){
	username = username.toLowerCase();
        $.ajax({
                url: '/services/valUser.php',
                type: 'GET',
                dataType : 'json',
                data: { username: username,
                        udata: password,
                        method: 'pass'},
                success: function(response){
                        var data = response;
                        if (data.status == 'ok'){
                                if (data.login == 'verified'){
                                        setCookie(username);
                                        setSession(username);
                                }else{
                                        alert('Login failed.');
                                }
                        }else{
                                alert(data.message);    
                        }

                },
                error : function(xhr, status, error){
                        document.write(error);
                }
        });
	}
        return false;
});


});

