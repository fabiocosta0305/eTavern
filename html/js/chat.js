function sendChat(){
    $.ajax({
        type: 'POST',
        data: {command: 'dataSent', info: $("#chatEntry").val()},
        url: 'chat.php',
        timeout: 2000,

        success: function(data) {
            $("#chatEntry").val("");
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

function offChatGet(){
    $.ajax({
        type: 'POST',
        data: {command: 'offChatGet', lastTimestamp: $("input[name=lastOffTimestamp]").val()},
        url: 'chat.php',
        timeout: 2000,
        success: function(data) {
            var obj=jQuery.parseJSON(data);
            $("#offChatData").append(obj.chatData);
            $("input[name=lastOffTimestamp]").val(obj.lastTimestamp);
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

function takeFirstTimestamp(){
    $.ajax({
        type: 'POST',
        data: {command: 'firstTimestamp'},
        url: 'chat.php',
        timeout: 2000,
        success: function(data) {
            var obj=jQuery.parseJSON(data);
            $("input[name=lastOnTimestamp]").val(obj.lastTimestamp);
            $("input[name=lastOffTimestamp]").val(obj.lastTimestamp);
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

function onChatGet(){
    $.ajax({
        type: 'POST',
        data: {command: 'onChatGet', lastTimestamp: $("input[name=lastOnTimestamp]").val()},
        url: 'chat.php',
        timeout: 2000,
        success: function(data) {
            var obj=jQuery.parseJSON(data);
            $("#onChatData").append(obj.chatData);
            $("input[name=lastOnTimestamp]").val(obj.lastTimestamp);
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
