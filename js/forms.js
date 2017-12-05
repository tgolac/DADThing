$(function () {
    $('#loginForm').submit(function (e) {
        let form = $(this);
        e.preventDefault();
        apiCall("auth", "login", objectify(this), function (data) {
            window.location.reload()
        }, function (data) {
            handleFormUnsuccessful(form, data)
        })
    });
    $('#registerForm').submit(function (e) {
        let form = $(this);
        e.preventDefault();
        apiCall("auth", "register", objectify(this), function (data) {
            window.location.reload()
        }, function (data) {
            handleFormUnsuccessful(form, data)
        })
    });
    console.log("ready!");
});

function handleFormUnsuccessful(form, data) {
    switch (data['status']) {
        case ERROR_ALERT:
            alert(data['alert']);
            break;
        case ERROR_FIELDS: {
            $.each(data['errors'], function (key, value) {
                form.find('#' + key).attr('placeholder', value);
            });
            break;
        }
    }
}

function objectify(formArray) {//serialize data function
    let values = {};
    $.each($(formArray).serializeArray(), function (i, field) {
        values[field.name] = field.value;
    });
    return values;
}