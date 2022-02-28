(function($){
    var Module = {};
    Module.processInit = function(range, addWhere) {
        $.post(
            connector + '?mode=start',
            {
                range:range,
                addWhere:addWhere
            },
            function(data){
                if (data.success) {
                    processing = true;
                    $('#progress').show().progressbar({
                        value:0
                    });
                    Module.process(range, addWhere);
                } else {
                    $.messager.alert('Ошибка', 'Произошла ошибка','error');
                }
            },
            'json'
        ).fail(function() {
            $.messager.alert('Ошибка', 'Произошла ошибка','error',function () {
                $('#dialog').dialog('close');
            })
        })
    };
    Module.process = function(range, addWhere) {
        if (!processing) return;
        $.post(
            connector + '?mode=process',
            {
                range:range,
                addWhere:addWhere
            },
            function(data){
                if (data.success) {
                    if (data.processed < data.total) {
                        $('#progress').progressbar('setValue',Math.floor(data.processed/data.total*100));
                        Module.process(range, addWhere);
                    } else {
                        $('#dialog').dialog('close');
                        $.messager.alert('Готово', 'Обработано '+ data.processed +' документов','info');
                    }
                } else {
                    $.messager.alert('Ошибка', data.message,'error',function () {
                        $('#dialog').dialog('close');
                    })
                }
            },
            'json'
        ).fail(function() {
            $.messager.alert('Ошибка', 'Произошла ошибка','error',function () {
                $('#dialog').dialog('close');
            })
        })
    };
    Module.init = function() {
        $('#dialog').dialog({
            title: 'Обработка документов',
            width: 400,
            closed: true,
            modal: true,
            onOpen: function(){
                var range= $('#range').val();
                var addWhere = $('#addWhere').val();
                if (!processing) Module.processInit(range, addWhere);
            },
            onClose: function() {
                processing = false;
                $('#progress').hide();
                $('#progress').progressbar('setValue',0);
            }
        });
        $('#rangeForm').submit(function(e){
            e.preventDefault();
            $('#dialog').dialog('open');
        });
    };
    $('document').ready(function() {
            Module.init();
        }
    )
})(jQuery);
