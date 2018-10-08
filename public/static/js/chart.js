var ws_client = new WebSocket('ws://swoole.com:8080');


ws_client.onopen = function(evt){
    console.log('Connect open.....');
};


ws_client.onmessage = function(evt){
    //console.log(evt.data);
    push(evt.data);
}

ws_client.onclose = function(evt){
    console.log('Connect closed!');
}

function push(data){
    var data = JSON.parse(data);
    var html = '<div class="comment">';
    html += '<span>'+data.username+'</span>';
    html +=  '<span> '+data.content+'</span>';
    html +=  '</div>';
    $('#comments').append(html);
}
