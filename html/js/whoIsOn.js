function whoIsOn() {
  $.ajax({
    type: 'GET',
    url: 'bring_logged.php',
    timeout: 2000,
    success: function(data) {
      $("#userlist").html(data);
      window.setTimeout(whoIsOn, 2000);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
              //      $("#notice_div").html('Timeout contacting server..');
              //      window.setTimeout(update, 60000);
    }
  });
}

function open_tables() {
  $.ajax({
    type: 'GET',
    url: 'open_tables.php',
    timeout: 2000,
    success: function(data) {
      $("#Tables").html(data);
      window.setTimeout(open_tables, 2000);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
              //      $("#notice_div").html('Timeout contacting server..');
              //      window.setTimeout(update, 60000);
    }
});
}
