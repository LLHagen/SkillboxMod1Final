'use strict';

const toggleHidden = (...fields) => {

    fields.forEach((field) => {
        if (field.hidden === true) {
            field.hidden = false;
        } else {
            field.hidden = true;
        }
    });
};

const labelHidden = (form) => {

    form.addEventListener('focusout', (evt) => {

        const field = evt.target;
        const label = field.nextElementSibling;

        if (field.tagName === 'INPUT' && field.value && label) {
            label.hidden = true;
        } else if (label) {
            label.hidden = false;
        }
    });
};

const toggleDelivery = (elem) => {

    const delivery = elem.querySelector('.js-radio');
    const deliveryYes = elem.querySelector('.shop-page__delivery--yes');
    const deliveryNo = elem.querySelector('.shop-page__delivery--no');
    const fields = deliveryYes.querySelectorAll('.custom-form__input');

    delivery.addEventListener('change', (evt) => {

        if (evt.target.id === 'dev-no') {
            fields.forEach(inp => {
                if (inp.required === true) {
                    inp.required = false;
                }
            });

            toggleHidden(deliveryYes, deliveryNo);

            deliveryNo.classList.add('fade');
            setTimeout(() => {
                deliveryNo.classList.remove('fade');
            }, 1000);
        } else {
            fields.forEach(inp => {
                if (inp.required === false) {
                    inp.required = true;
                }
            });

            toggleHidden(deliveryYes, deliveryNo);

            deliveryYes.classList.add('fade');
            setTimeout(() => {
                deliveryYes.classList.remove('fade');
            }, 1000);
        }
    });
};

const filterWrapper = document.querySelector('.filter__list');
if (filterWrapper) {

    filterWrapper.addEventListener('click', evt => {

        const filterList = filterWrapper.querySelectorAll('.filter__list-item');

        filterList.forEach(filter => {
            if (filter.classList.contains('active')) {
                filter.classList.remove('active');
            }
        });

        const filter = evt.target;

        filter.classList.add('active');
    });
}

// sort
function getUrlInArray() {
    var urlParam = window.location.search;
    var arrayVar = [];
    var valueAndKey = [];
    var resultArray = [];

    arrayVar = (urlParam.substr(1)).split('&');

    if(arrayVar[0]=="") return resultArray;

    for (var i = 0; i < arrayVar.length; i ++) {
            valueAndKey = arrayVar[i].split('=');
            resultArray[valueAndKey[0]] = valueAndKey[1];
    }
    return resultArray;
}

function sortingElement(el, getParam) {
    var myURL = getUrlInArray();
    myURL[getParam] = el.value;
    var urlNoParamString = window.location.toString();
    var urlVar = window.location.search;
    var url_array = urlVar.split("?");
    var resultURL = '?';

    for (var key in myURL) {
        if (myURL.hasOwnProperty(key)) {
            if (resultURL == '?') {
                resultURL += key + '=' + myURL[key];
            } else {
                resultURL += '&' + key + '=' + myURL[key];
            }
        }
    }
    document.location.href = resultURL;
}

const sortElements =  document.querySelector('.shop__sorting');
if (sortElements) {
    var opt = sortElements.querySelectorAll('.custom-form__select');
    opt.forEach(element => {
        element.addEventListener('change', function (evt) {
                var getParam = evt.target.name;
                if (getParam == 'category') {
                        getParam = 'sorting';
                }else if (getParam == 'prices') {
                        getParam = 'groupingOrder';
                }
                var optionValue = evt.target;
                sortingElement(optionValue, getParam);
                // console.log(evt.target.name);

        });
    });

    var getParamsArray = getUrlInArray();
    if ( typeof(getParamsArray['sorting']) != "undefined" && (getParamsArray['sorting'] !== null)) {
        var sortingSelected =  document.querySelector('option[value=' + getParamsArray['sorting'] + ']');
        sortingSelected.selected = true;
    }
    if ( typeof(getParamsArray['groupingOrder']) != "undefined" && (getParamsArray['groupingOrder'] !== null)) {
        var sortingSelected =  document.querySelector('option[value=' + getParamsArray['groupingOrder'] + ']');
        sortingSelected.selected = true;
    }
}

const shopFilter = document.querySelector('.shop__filter');
if (shopFilter) {
    const buttonFilter = shopFilter.querySelector('.button');

    if (buttonFilter) {

        buttonFilter.addEventListener('click', (evt) => {
            evt.preventDefault();
            const inputs = shopFilter.querySelectorAll('input');

                var data = {};
                inputs.forEach(inp => {

                    if ((inp.type == 'radio' && inp.checked == true) || inp.type == 'hidden') {
                        data[inp.name] = inp.value;
                    }
                    if (inp.type == 'checkbox') {
                        data[inp.name] = inp.checked;
                    }
                });

                var getArray = getUrlInArray();
                for (var key in data) {
                    if (data.hasOwnProperty(key)) {
                        if (data[key] == false &&  getArray[key] != 'undefined') {
                            delete getArray[key];
                        } else {
                            getArray[key] = data[key];
                        }
                    }
                }
                var resultURL = '?';
                for (var key in getArray) {
                    if (getArray.hasOwnProperty(key)) {
                        if (resultURL == '?') {
                            resultURL += key + '=' + getArray[key];
                        } else {
                            resultURL += '&' + key + '=' + getArray[key];
                        }
                    }
                }

                document.location.href = resultURL;
        });
    }
}

// покупка товара(клик по товару)
const shopList = document.querySelector('.shop__list');
if (shopList) {

    shopList.addEventListener('click', (evt) => {

        const prod = evt.path || (evt.composedPath && evt.composedPath());;

        if (prod.some(pathItem => pathItem.classList && pathItem.classList.contains('shop__item'))) {

            const shopOrder = document.querySelector('.shop-page__order');

            toggleHidden(document.querySelector('.intro'), document.querySelector('.shop'), shopOrder);

            window.scroll(0, 0);

            const form = shopOrder.querySelector('.custom-form');
            labelHidden(form);

            toggleDelivery(shopOrder);

            const buttonOrder = shopOrder.querySelector('.button');
            const popupEnd = document.querySelector('.shop-page__popup-end');

            buttonOrder.addEventListener('click', (evt) => {

                form.noValidate = true;

                const inputs = Array.from(shopOrder.querySelectorAll('[required]'));

                inputs.forEach(inp => {

                    if (!!inp.value) {
                        if (inp.classList.contains('custom-form__input--error')) {
                            inp.classList.remove('custom-form__input--error');
                        }
                    } else {
                        inp.classList.add('custom-form__input--error');
                    }
                });

                if (inputs.every(inp => !!inp.value)) {

                    evt.preventDefault();

                    // собрать данные с формы заказа и отправить их на сервер
                    var data = {};
                    var surname = $('input[name="surname"]').val();
                    if (!checkEmpty(surname)) {
                        data.surname = surname;
                    }
                    var name = $('input[name="name"]').val();
                    if (!checkEmpty(name)) {
                        data.name = name;
                    }
                    var thirdName = $('input[name="thirdName"]').val();
                    if (!checkEmpty(thirdName)) {
                        data.thirdName = thirdName;
                    }
                    var phone = $('input[name="phone"]').val();
                    if (!checkEmpty(phone)) {
                        data.phone = phone;
                    }
                    var email = $('input[name="email"]').val();
                    if (!checkEmpty(email)) {
                        data.email = email;
                    }
                    var pay = $('input[name="pay"]:checked').val();
                    if (!checkEmpty(pay)) {
                        data.pay = pay;
                    }
                    var comment = $('textarea[name="comment"]').val();
                    if (!checkEmpty(comment)) {
                        data.comment = comment;
                    }
                    var delivery = $('input[name="delivery"]:checked').val();
                    if (delivery == "dev-yes"){
                        data.delivery = true;
                        var city = $('input[name="city"]').val();
                        var street = $('input[name="street"]').val();
                        var home = $('input[name="home"]').val();
                        var aprt = $('input[name="aprt"]').val();
                    } else {
                        data.delivery = false;
                        var city = 'Москва';
                        var street = 'Тверская';
                        var home = '4';
                        var aprt = 'нет';
                    }
                    if (!checkEmpty(city)) {
                        data.city = city;
                    }
                    if (!checkEmpty(street)) {
                        data.street = street;
                    }
                    if (!checkEmpty(home)) {
                        data.home = home;
                    }
                    if (!checkEmpty(aprt)) {
                        data.aprt = aprt;
                    }
                    var productId = $('input[name="productId"]').val();
                    if (!checkEmpty(productId)) {
                        data.productId = productId;
                    }

                    $.ajax({
                        url: '/include/ajaxOrder.php',
                        type: "POST",
                        data: data,
                        success: function(data){
                            data = JSON.parse(data);

                            if (!!data.error) {
                                console.log(data);
                                var strError = '';
                                for (const [key, value] of Object.entries(data.error)) {
                                    strError += value + '\n';
                                }
                                alert(strError);

                                return false;

                            } else {
                                evt.preventDefault();

                                toggleHidden(shopOrder, popupEnd);

                                popupEnd.classList.add('fade');
                                setTimeout(() => popupEnd.classList.remove('fade'), 1000);

                                const buttonEnd = popupEnd.querySelector('.button');

                                buttonEnd.addEventListener('click', () => {

                                    popupEnd.classList.add('fade-reverse');

                                    setTimeout(() => {

                                        popupEnd.classList.remove('fade-reverse');

                                        toggleHidden(popupEnd, document.querySelector('.intro'), document.querySelector('.shop'));

                                        location.href = '/';
                                    }, 1000);

                                });

                                window.scroll(0, 0);

                            }
                        },
                        error: function (jqXHR, exception) {
                            var msg = '';
                            if (jqXHR.status === 0) {
                                msg = 'Not connect.\n Verify Network.';
                            } else if (jqXHR.status == 404) {
                                msg = 'Requested page not found. [404]';
                            } else if (jqXHR.status == 500) {
                                msg = 'Internal Server Error [500].';
                            } else if (exception === 'parsererror') {
                                msg = 'Requested JSON parse failed.';
                            } else if (exception === 'timeout') {
                                msg = 'Time out error.';
                            } else if (exception === 'abort') {
                                msg = 'Ajax request aborted.';
                            } else {
                                msg = 'Uncaught Error.\n' + jqXHR.responseText;
                            }
                            console.log(msg);
                        }
                    });

                } else {
                    window.scroll(0, 0);
                    evt.preventDefault();
                }


            });
        }
    });
}

// смена статуса заказа
const pageOrderList = document.querySelector('.page-order__list');
if (pageOrderList) {

    pageOrderList.addEventListener('click', evt => {

        if (evt.target.classList && evt.target.classList.contains('order-item__toggle')) {
            var path = evt.path || (evt.composedPath && evt.composedPath());
            Array.from(path).forEach(element => {
                if (element.classList && element.classList.contains('page-order__item')) {
                    element.classList.toggle('order-item--active');
                }
            });
            evt.target.classList.toggle('order-item__toggle--active');
        }

        if (evt.target.classList && evt.target.classList.contains('order-item__btn')) {
            var data = {};
            data.orderId = evt.target.parentElement.parentElement.parentElement.querySelectorAll('.order-item__wrapper')[0].firstElementChild.children[1].firstChild.data;

            const status = evt.target.previousElementSibling;

            if (status.classList && status.classList.contains('order-item__info--no')) {
                data.status = true;
            } else {
                data.status = false;
            }

            $.ajax({
                url: '/include/ajaxOrderStatus.php',
                type: "POST",
                data: data,
                success: function(data){
                    if (data == 'true') {
                        if (status.classList && status.classList.contains('order-item__info--no')) {
                            status.textContent = 'Выполнено';
                        } else {
                            status.textContent = 'Не выполнено';
                        }
                        status.classList.toggle('order-item__info--no');
                        status.classList.toggle('order-item__info--yes');
                    } else {
                        console.log('error');
                    }
                },
                error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log(msg);
                }
            });
        }
    });
}

const checkList = (list, btn) => {

    if (list.children.length === 1) {
        btn.hidden = false;
    } else {
        btn.hidden = true;
    }

};

const addList = document.querySelector('.add-list');
if (addList) {

    const form = document.querySelector('.custom-form');
    labelHidden(form);

    const addButton = addList.querySelector('.add-list__item--add');
    const addInput = addList.querySelector('#product-photo');

    const testAddImg = addList.querySelector('.add-list__item--active');

    if (testAddImg) {
        testAddImg.addEventListener('click', evt => {
            addList.removeChild(evt.target);
            addInput.value = '';
            checkList(addList, addButton);
        });
    }

    checkList(addList, addButton);

    addInput.addEventListener('change', evt => {

        const template = document.createElement('LI');
        const img = document.createElement('IMG');

        template.className = 'add-list__item add-list__item--active';
        template.addEventListener('click', evt => {
            addList.removeChild(evt.target);
            addInput.value = '';
            checkList(addList, addButton);

        });

        const file = evt.target.files[0];

        const reader = new FileReader();

        reader.onload = (evt) => {
            img.src = evt.target.result;
            console.log(img)
            template.appendChild(img);
            addList.appendChild(template);
            checkList(addList, addButton);
        };

        reader.readAsDataURL(file);

    });
}

// удалить продукт
const productsList = document.querySelector('.page-products__list');
if (productsList) {
    productsList.addEventListener('click', evt => {
        const target = evt.target;
        if (target.classList && target.classList.contains('product-item__delete')) {

            // удалить с помощью аякса
            var data = {};
            data.id = target.parentElement.querySelector('#productId').value;

            $.ajax({
                url: '/include/ajaxDeleteProduct.php',
                type: "POST",
                data: data,
                success: function(data){
                    if (data == 'true') {
                        productsList.removeChild(target.parentElement);
                    } else {
                        alert('Ошибка удаления товара');
                    }
                },
            error: function (jqXHR, exception) {
                var msg = '';
                if (jqXHR.status === 0) {
                    msg = 'Not connect.\n Verify Network.';
                } else if (jqXHR.status == 404) {
                    msg = 'Requested page not found. [404]';
                } else if (jqXHR.status == 500) {
                    msg = 'Internal Server Error [500].';
                } else if (exception === 'parsererror') {
                    msg = 'Requested JSON parse failed.';
                } else if (exception === 'timeout') {
                    msg = 'Time out error.';
                } else if (exception === 'abort') {
                    msg = 'Ajax request aborted.';
                } else {
                    msg = 'Uncaught Error.\n' + jqXHR.responseText;
                }
                console.log(msg);
            }
        });
        }
    });
}


// проверить импут
function checkEmpty(str){
    if (str != null && typeof str !== "undefined") {
        str = str.trim();
    }
    if (!str) {
        return true;
    } else {
        return false;
    }

}

// скрыть подсказку в импутах товара
const inputHalpers = document.querySelectorAll('.custom-form__input-wrapper');
if (inputHalpers) {
    inputHalpers.forEach(element => {
        var p = element.querySelector('p');
        var inputValue = element.querySelector('input').value;
        if (inputValue) {
            p.hidden = true;
        }
    });
}

// оформление покупки(клип по товару) заполняет айдишник в скрытом импуте
function buyProduct(el)
{
    var id = el.dataset.id;
    $('input[name="productId"]').val(id);
}

// jquery range maxmin
if (document.querySelector('.shop-page')) {

    $('.range__line').slider({
        min: 290,
        max: 32000,
        values: [290, 32000],
        range: true,
        stop: function(event, ui) {
            // установить значения в слайдере
            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
            // Присвоить скрытым полям в форме фильтрации значения фильтра цен для дальнейшей отправки формы
            $("#minPrice").val($('.range__line').slider('values', 0));
            $("#maxPrice").val($('.range__line').slider('values', 1));
        },
        slide: function(event, ui) {
            // установить  значения на странице
            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
        }
    });

    $.ajax({
        url: '/include/ajaxRangeLine.php',
        type: "POST",
        dataType: "json",
        success: function(data){
            var max = Number(data);
            var min = Number(data.min);
            var max = Number(data.max);
            var values = [Number(data.values[0]), Number(data.values[1])];
            // установить значения в слайдере
            $('.range__line').slider( "option", "min", min );
            $('.range__line').slider( "option", "max", max );
            $('.range__line').slider( "option", "values", values );
            // установить  значения на странице
            $('.min-price').text($('.range__line').slider('values', 0) + ' руб.');
            $('.max-price').text($('.range__line').slider('values', 1) + ' руб.');
            // установить значения в скрытую часть формы
            $("#minPrice").val($('.range__line').slider('values', 0));
            $("#maxPrice").val($('.range__line').slider('values', 1));
        }
    });
}

