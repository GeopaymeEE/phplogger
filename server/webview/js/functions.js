$(function(){

    $.ajax({
        url:'/jbernatowicz/phplogger/server/webview/dataaccess.php',
        data:{type:'all'},
        success:function(data){
            data=JSON.parse(data);
            console.log(data);
            var template = $('#log_row').html();
            var html = Mustache.to_html(template,data);
            $('#results').html(html);
        }
    })

    $.ajax({
        url:'/jbernatowicz/phplogger/server/webview/dataaccess.php',
        data:{type:'error'},
        success:function(data){
            data=JSON.parse(data);
            console.log(data);
            var template = $('#log_row_errors').html();
            var html = Mustache.to_html(template,data);
            $('#results_error').html(html);
        }
    })
})