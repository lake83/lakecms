// Редактирование и создание настроек
$('body').on('beforeSubmit', '#paramForm, #createSettingForm', function () {
    var form = $(this);
    if (form.find('.has-error').length) {
        return false;
    }
    $.ajax({
        url: form.attr('action'),
        type: 'post',
        data: form.serialize(),
        success: function(data){ 
            if (form.attr('id') == 'paramForm') {
                if (data.name == 'skin') {
                    location.reload();
                } else {
                    $('#modalContent').html(data.message);$('#' + data.name).text(data.value);
                }
            } else {
                $('#modalContent').html(data);
            } 
        }
    });
    return false;
});
function settings(label,field){
    $.ajax({
       type: 'POST',
       cache: false,
       url: '/admin/settings',
       data: {field: field},
       success: function(data) {
           $('#modalContent').html(data);
           $('#modal').modal('show').find('#modalTitle').text(label);
       }
    });
}
function createSetting(title){
    $.ajax({
       type: 'POST',
       cache: false,
       url: '/admin/create-setting',
       success: function(data) {
           $('#modalContent').html(data);
           $('#modal').modal('show').find('#modalTitle').text(title);
       }
    });
}

// для левого меню ссылка открывает страницу, угол разворачивает подменню
$('.sidebar-menu li').find('.fa-angle-left').hide().parent('a').after('<a href=\"\"><i class=\"fa fa-angle-left pull-right\"></i></a>');

// вывод диалога confirm в стиле bootstrap
yii.confirm = function (message, ok, cancel) {
    krajeeDialog.confirm(message, function (confirmed) {
        if (confirmed) {
            !ok || ok();
        } else {
            !cancel || cancel();
        }
    });
    return false;
}