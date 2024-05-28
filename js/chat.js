var conn = new WebSocket('ws://localhost:8081');

conn.onopen = function(e)
{
    console.log("Connection established!");
};

conn.onerror = function(e) {
    console.log("WebSocket error observed:", e);
};

function getTime()
{
    var date = new Date();

    return date.toLocaleDateString()
};

function sendMessage(e)
{
    var code = (e.keyCode ? e.keyCode : e.which);

    if(code !== 13) {
        return;
    }

    var message = document.getElementById('message').value;

    if (message.length == 0) {
        return;
    }

    conn.send(message);

    var content = document.getElementById('chat').innerHTML;

    document.getElementById('chat').innerHTML = content + '<div class="sent-message">Sent on [' + getTime() + '] ' + message + '</div>';
    document.getElementById('message').value = '';
};

function receiveMessage(e)
{
    var content = document.getElementById('chat').innerHTML;

    document.getElementById('chat').innerHTML = content + '<div class="received-message">Received on [' + getTime() + '] ' + e.data + '</div>';
};

conn.onmessage = function(e)
{
    receiveMessage(e);
};