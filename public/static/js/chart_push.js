$(function(){
    var url = "http://swoole.com/index/chart/index";
    $('#chart-box').keydown(function(evt){
        if(evt.keyCode == 13){
            //13 代表就是回车键
            $.ajax({
                url : url,
                type : 'post',
                data : {content:$(this).val(),game_id:1},
                dataType: 'json',
                async : true,
                success : function(data){
                    if(data.status == 200){
                        $('#chart-box').val("");
                    }else{
                        layer.alert(data.msg,function(index){
                            window.location.href = "http://swoole.com/login.html";
                            layer.close(index);
                        });
                    }
                }
            });
        }
    });
});


