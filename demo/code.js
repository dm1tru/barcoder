var socket = false;
var frm;
var table;

$(function () {
    frm = $('form#form');
    frm.find('#connect').bind('click', function () {
        if (socket) {
            socket.close();
        }

        socket = new WebSocket('ws://' + frm.find('#host').val());
        log('Connecting...', 'info');

        socket.onopen = () => {
            log("WebSocket connection opened", 'info');
            setServerState(true);
            // Send a message to the server
            socket.send("Hello from the browser!");
        };

        // Listen to the message event, which contains the data received from the server
        socket.onmessage = (event) => {
            log("Message from the server: " + event.data);
        };

        // Listen to the close event, which indicates the connection is closed
        socket.onclose = (event) => {
            log("WebSocket connection closed", 'info');
            setServerState(false);
        };

        // Listen to the error event, which indicates there is an error with the connection
        socket.onerror = (error) => {
            console.log(error);
            log("WebSocket error: " + error, 'info');
            setServerState(false);
        };
    });

    frm.find('#disconnect').bind('click', function () {
        if (socket) {
            socket.close();
        }

    });

    frm.find("#clear").bind('click', function () {
        $('table#table-log').empty();
    });

    setServerState(false);
})

function log(msg, type = '')
{
    var date = new Date();

    var row = $('<tr></tr>').addClass(type);
    var cell;
    cell = $('<td></td>').html(date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds());
    row.append(cell);
    cell = $('<td></td>').html(msg);
    row.append(cell);
    $('table#table-log').append(row);
}

function setServerState(state)
{
    var badge = $('#server-state');
    badge.html(state ? 'Подключен' : "Отключен");
    badge.removeClass('text-bg-primary').removeClass('text-bg-warning');
    badge.addClass(state ? 'text-bg-primary' : 'text-bg-warning');
}