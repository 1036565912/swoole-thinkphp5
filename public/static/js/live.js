var ws_client = new WebSocket('ws://swoole.com');


ws_client.onopen = function(evt){
    console.log('Connect open.....');
};


ws_client.onmessage = function(evt){
    push(evt.data);
}

ws_client.onclose = function(evt){
    console.log('Connect closed!');
}

function push(data){
    var data = JSON.parse(data);
    //获取推送消息之后 进行数据的补充 然后进行填充
    console.log(data);
    var html = '<div class="frame">';
        html += '<h3 class="frame-header">';
        html += '<i class="icon iconfont icon-shijian"></i>第'+data.type+'节'+ data.time;
        html += '</h3>';
        html += '<div class="frame-item">';
        html += '<span class="frame-dot"></span>';
        html += '<div class="frame-item-author">';
        if(data.logo){
            html += '<img src="'+data.logo+'" width="20px" height="20px" />';
        }
        html += data.title;
        html += '</div>';
        html += '<p>'+data.content+'</p>';
        if(data.img){
            html += '<p>';
            html += '<img src="'+data.img+'" width="40%" />';
            html += '</p>';
        }
        html += '</div>';
        html += '</div>';
        $('#match-result').prepend(html);
}
