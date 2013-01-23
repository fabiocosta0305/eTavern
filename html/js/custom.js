function whoIsOn() {
  $.ajax({
    type: 'GET',
    url: 'bring_logged.php',
    timeout: 2000,
    success: function(data) {
      $("#userlist").html(data);
      window.setTimeout(update, 10000);
    },
    error: function (XMLHttpRequest, textStatus, errorThrown) {
              //      $("#notice_div").html('Timeout contacting server..');
              //      window.setTimeout(update, 60000);
    }
});
}
