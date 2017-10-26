//init engine functions

var v1, v2;

var SetVar1 = function(pl){
    v1 = pl;
}
var SetVar2 = function(ps){
    v2 = ps;
}

var oSipStack, oSipSessionRegister, oSipSessionCall;

            //alert('{{Auth::user()->sip_number}}');
            //console.log('{{Auth::user()->sip_number}}' + (new Date().toISOString().slice(11, -1)));
            //alert('v1='+v1);
            
var readyCallback = function(e){
                createSipStack(); // see next section
            };
            var errorCallback = function(e){
                console.error('Failed to initialize the engine: ' + e.message);
            }


function sipHangUp() {
            if (oSipSessionCall) {
                txtCallStatus.innerHTML = '<i>Terminating the call...</i>';
                oSipSessionCall.hangup({ events_listener: { events: '*', listener: onSipEventSession } });
            }
        }



//init sip stack functions
    var sipStack;
            var eventsListener = function(e){
                if(e.type == 'started'){
                    login();
                }
                else if(e.type == 'i_new_message'){ // incoming new SIP MESSAGE (SMS-like)
                    acceptMessage(e);
                }
                else if(e.type == 'i_new_call'){ // incoming audio/video call
                    acceptCall(e);
                }
            }


  function createSipStack(){
                sipStack = new SIPml.Stack({
                        realm: 'sip.viasakha.ru', // mandatory: domain name
                        //impi: '109', // mandatory: authorization name (IMS Private Identity)
                        impi: v1.substring(0,v1.indexOf("@")), // mandatory: authorization name (IMS Private Identity)
//                        impu: 'sip:109@sip.viasakha.ru', // mandatory: valid SIP Uri (IMS Public Identity)
                        impu: 'sip:'+v1, // mandatory: valid SIP Uri (IMS Public Identity)
//                        password: '123456abc', // optional
                        password: v2, // optional
//                        display_name: '109', // optional
                        display_name: v1.substring(0,v1.indexOf("@")), // optional
                        websocket_proxy_url: 'wss://sip.viasakha.ru:8089/ws', // optional
                        events_listener: { events: '*', listener: eventsListener } // optional: '*' means all events
                    }
                );
                //alert( v1.substring(0,v1.indexOf("@")));
            }



//register session functions
     var registerSession;
            var eventsListener = function(e){
                console.info('session event = ' + e.type);
                if(e.type == 'connected' && e.session == registerSession){
                    makeCall();
                    sendMessage();
                    publishPresence();
                    subscribePresence('johndoe'); // watch johndoe's presence status change
                }
            }
            var login = function(){
                //alert(v1);
                registerSession = sipStack.newSession('register', {
                    events_listener: { events: '*', listener: eventsListener } // optional: '*' means all events
                });
                registerSession.register();
            }


//call functions
 var callSession;
            var eventsListener = function(e){
                console.info('session event = ' + e.type);
            }

            var makeCall = function(){
                callSession = sipStack.newSession('call-audio', {
                    audio_remote: document.getElementById('audio_remote'),
                    events_listener: { events: '*', listener: eventsListener } // optional: '*' means all events
                });
                callSession.call(id_tel_phone.value);
            }
        
   function sipHangUp() {
            if (callSession) {
                callSession.hangup({ events_listener: { events: '*', listener: eventsListener } });
            }
        }



