var alertShow = (alertShow) ? alertShow : null;
var alertContainer = (alertContainer) ? alertContainer : null;
var alertSuccessShow = (alertSuccessShow) ? alertSuccessShow : null;
var alertSuccessContainer = (alertSuccessContainer) ? alertSuccessContainer : null;
// console.log(alertShow);
// console.log(alertSuccessShow);

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
    // var content = document.querySelector('.content');
    // content && content.addEventListener('click', function (event) {
    //     var editButton = event.target.closest('.edit.php');
    //     if (!editButton) return;
    //     // console.log("1");
    //     var article = editButton.closest('article');
    //     var preview = article.querySelector('.preview');
    //     var title = article.querySelector('.text-container .title');
    //     console.log(article);
    //     console.log(article.querySelector('.text-container'));
    //     var textarea = article.querySelector('.text-container .textarea');
    //     var editForm = `
    //         <form id="new" method="post" enctype="multipart/form-data">
    //             <div class="uk-margin">
    //                 <input
    //                         class="uk-input uk-form-large"
    //                         type="text"
    //                         placeholder="Заголовок"
    //                         required
    //                         name="title"
    //                         value="` + title.textContent + `">
    //             </div>
    //             <div class="uk-margin">
    //                 <textarea
    //                 id="textarea"
    //                 name="textarea"
    //                 value="` + textarea.innerHTML + `"></textarea>
    //             </div>
    //             <div class="uk-margin">
    //                 <div uk-form-custom="target: true">
    //                     <input
    //                             type="file"
    //                             name="file"
    //                             accept="image/jpeg,image/png,image/gif"
    //                             value="` + preview.value + `">
    //                     <input
    //                             class="uk-input uk-form-width-medium"
    //                             type="text"
    //                             placeholder="Выберете файл картинки"
    //                             disabled>
    //                 </div>
    //             </div>
    //             <div class="uk-margin">
    //                 <button class="uk-button uk-button-primary">Сохранить</button>
    //                 <input
    //                         type="reset"
    //                         class="uk-button uk-button-default"
    //                         value="Сбросить">
    //             </div>
    //         </form>`;
    //     article.innerHTML = editForm;
    // });
});