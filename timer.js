setInterval(function () {
  var xhr = new XMLHttpRequest();
  xhr.open("GET", "../timer.php", true);
  xhr.send();
}, 302000); //5min + 2sec = 302000
