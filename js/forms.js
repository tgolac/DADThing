$(function () {
    $('#loginForm').submit(function (e) {
        e.preventDefault();
        let data = objectify(this);
        apiCall("auth", "login", objectify(this), function (data) {

        })
    });
    console.log("ready!");
});

function objectify(formArray) {//serialize data function
    let values = {};
    $.each($(formArray).serializeArray(), function (i, field) {
        values[field.name] = field.value;
    });
    return values;
}