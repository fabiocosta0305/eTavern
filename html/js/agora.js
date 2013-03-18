function sendAgora(){
    $.ajax({
        type: 'POST',
        data: {command: 'dataSent', info: $("#agoraEntry").val()},
        url: 'agora.php',
        timeout: 2000,

        success: function(data) {
            $("#agoraEntry").val("");

            return false;
        },
         
        complete: function(XMLHttpResponse, textStatus)
        { 
            return true;
        },
                                      
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            return true;
        }});

    return false;
}

function agoraGet(){
    $.ajax({
        type: 'POST',
        data: {command: 'agoraGet', lastTimestamp: $("input[name=lastAgoraTimestamp]").val()},
        url: 'agora.php',
        timeout: 2000,
        success: function(data) {
            var obj=jQuery.parseJSON(data);
            $("#agoraRoom").append(obj.agoraData);
            $("input[name=lastAgoraTimestamp]").val(obj.lastTimestamp);
            $("#agoraRoom").animate({ scrollTop: $('#agoraRoom').height() }, 300);
            return false;
        },
        complete: function(XMLHttpResponse, textStatus)
        { 
            return true;
        },                            
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            return true;
        }});
}

function whoIsOnAgora() {
  $.ajax({
    type: 'GET',
    url: 'bring_agora.php',
    timeout: 2000,
    success: function(data) {
        if (data=='kicked out')
        {
            window.location.href='/';
        }
        else
        {
            $("#agoraPeople").html(data);
            window.setTimeout(whoIsOnAgora, 2000);
        }
    // },
    //   error: function (XMLHttpRequest, textStatus, errorThrown) {
    //           //      $("#notice_div").html('Timeout contacting server..');
    //           //      window.setTimeout(update, 60000);
    }
  });
}

function takeAgoraTimestamp(){
    $.ajax({
        type: 'POST',
        data: {command: 'firstTimestamp'},
        url: 'agora.php',
        timeout: 2000,
        success: function(data) {
            var obj=jQuery.parseJSON(data);
            $("input[name=lastAgoraTimestamp]").val(obj.lastTimestamp);
            return false;
        },
        complete: function(XMLHttpResponse, textStatus)
        { 
            return true;
        },                            
        error: function (XMLHttpRequest, textStatus, errorThrown) {
            return true;
        }});
}
