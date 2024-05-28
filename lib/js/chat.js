$(document).ready(function() {

  var messageBox = $('#messages-box')
  var sock       = new WebSocket('ws://192.168.88.100:8089')
  // var sock       = new WebSocket('wss://echo.websocket.org')
  window.ws      = sock

  sock.onopen    = function(event) { 
    // messageBox.prepend('<div class="alert alert-success">Connection opened</div>') 
  }
  sock.onclose   = function(event) { messageBox.prepend('<div class="alert alert-danger">Connection closed</div>') }
  sock.onerror   = function(event) { messageBox.prepend('<div class="alert alert-danger">Unknown error</div>'); console.log(event) }
  sock.onmessage = function(event) { 
    var data = JSON.parse(event.data)
    messageBox.prepend('<div class="alert '+data.class+'">' + data.msg + '</div>'); console.log(event) 
  }

  window.sendMessage = function() {
    var message = $('#message-input').val()
    sock.send(message)
    $('#message-input').val('')
  }
})