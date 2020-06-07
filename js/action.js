
function ajax(url, data, onsuccess, onerror) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                var json = JSON.parse(xhr.responseText);
                onsuccess(json);
            } else {
                onerror(xhr);
            }
        }
    };
    xhr.open('POST', url, true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.send(data);
}

function getelement(id) {
    return document.getElementById(id);
}

function login_form() {
    ajax('index.php', 'login=' + getelement('login').value + '&password=' + getelement('password').value + '&saveme=' + (getelement('saveme').checked ? '1' : '0') + '&ajax=1',
        function (response) {
            switch (response.code) {
                case 0:
                    location.reload();
                    break;
                case 1:
                    getelement('messagebox').innerHTML = 'Invalid input';
                    break;
                case 2:
                    getelement('messagebox').innerHTML = 'Invalid login or password';
                    break;
            }
        },
        function (xhr) {
            getelement('messagebox').innerHTML = 'Connection error';
        });
}

function register_form() {
    if (getelement('password').value === getelement('confirm_password').value) {
        ajax('reg.php', 'name=' + getelement('name').value + '&login=' + getelement('login').value + '&password=' + getelement('password').value + '&confirm_password=' + getelement('confirm_password').value + '&email=' + getelement('email').value + '&ajax=1',
            function (response) {
                switch (response.code) {
                    case 0:
                        location.reload();
                        break;
                    case 1:
                        getelement('messagebox').innerHTML = 'Connection error';
                        break;
                    case 2:
                        getelement('messagebox').innerHTML = 'Email already in use';
                        break;
                    case 3:
                        getelement('messagebox').innerHTML = 'Login already in use';
                        break;
                }
            },
            function (xhr) {
                getelement('messagebox').innerHTML = 'Connection error';
            });
    } else {
        getelement('messagebox').innerHTML = 'Invalid password confirmation';
    }
}
