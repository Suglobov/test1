var alertShow = (alertShow) ? alertShow : null;
var alertContainer = (alertContainer) ? alertContainer : null;
var alertSuccessShow = (alertSuccessShow) ? alertSuccessShow : null;
var alertSuccessContainer = (alertSuccessContainer) ? alertSuccessContainer : null;
console.log(alertShow);
console.log(alertSuccessShow);

var alert = document.querySelector('.alert');

if (alertShow) {

    alert.classList.remove('uk-hidden');
}
if (alertContainer) {
    console.log(alertContainer);
    alertContainer.forEach(function (element) {
        var div = document.createElement('div');
        div.textContent = element;
        alert.appendChild(div);
    });
}

var alertSuccess = document.querySelector('.alertSuccess');
if (alertSuccessShow) {
    alertSuccess.classList.remove('uk-hidden');
}
if (alertSuccessContainer) {
    alertSuccessContainer.forEach(function (element) {
        var div = document.createElement('div');
        div.textContent = element;
        alertSuccess.appendChild(div);
    });
}

window.addEventListener('load', function () {
    document.querySelector('.uk-container')
        .addEventListener('click', function (event) {
            
        });
});