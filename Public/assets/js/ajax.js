// ajax.js
// Простий хелпер для AJAX-запитів.
// Автоматично додає CSRF-токен до кожного небезпечного запиту.

var Ajax = (function () {

    // Читаємо CSRF-токен з мета-тегу в <head>
    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    // Основна функція запиту
    // method  — 'GET', 'POST', 'PUT', 'DELETE'
    // url     — '/events/5'
    // data    — об'єкт з даними або null
    // callback(err, responseData) — функція яка отримає результат
    function request(method, url, data, callback) {
        var xhr = new XMLHttpRequest();
        xhr.open(method, url, true);

        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        // Додаємо CSRF для небезпечних методів
        var unsafeMethods = ['POST', 'PUT', 'DELETE'];
        if (unsafeMethods.indexOf(method.toUpperCase()) !== -1) {
            xhr.setRequestHeader('X-CSRF-TOKEN', getCsrfToken());
        }

        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) { return; }

            var response = null;
            try {
                response = JSON.parse(xhr.responseText);
            } catch (e) {
                response = { success: false, message: 'Invalid JSON response' };
            }

            if (xhr.status >= 200 && xhr.status < 300) {
                callback(null, response);
            } else {
                callback(response, null);
            }
        };

        xhr.onerror = function () {
            callback({ message: 'Network error' }, null);
        };

        if (data !== null && data !== undefined) {
            xhr.send(JSON.stringify(data));
        } else {
            xhr.send();
        }
    }

    // Публічний API
    return {
        get: function (url, callback) {
            request('GET', url, null, callback);
        },
        post: function (url, data, callback) {
            request('POST', url, data, callback);
        },
        put: function (url, data, callback) {
            request('PUT', url, data, callback);
        },
        delete: function (url, callback) {
            request('DELETE', url, null, callback);
        }
    };

})();