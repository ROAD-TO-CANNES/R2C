// Used to keep the session alive by sending a request to the server every 5 minutes
setInterval(function () {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "../timer.php", true);
  xhr.send();
}, 602000); //10min + 2sec = 602000
