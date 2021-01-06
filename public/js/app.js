$(document).ready(function () {
    if ($.cookie(APP_NAME + '-ra')) {
        loadRoute($.cookie(APP_NAME + '-ra'));
    }
    updateButtons();

    onload = function () {
        apiClient.validateToken(function (response) {
            if (!response.success) {
                $.cookie(APP_NAME + '-ra', "");
                var b64 = window.btoa('admin');
                window.location = 'login?tgt=' + b64;
            } else {

                apiClient.get('user/show/' + $.cookie(APP_NAME + '-UserId'), function (response) {
                    if (response.success === true) {
                        var tmp = response.data.name.split(' ');
                        $('.user-name').html(tmp[0] + ' ' + tmp[tmp.length - 1]);
                        $('.user-name-complete').html(response.data.name);
                        $('.user-email').html(response.data.email);
                        if ($.cookie(APP_NAME + '-UserId') != '') {
                            if (response.data.avatar != '') {
                                $('.user-image').attr('src', URL + 'storage/avatars-' + response.data.avatar);
                            } else {
                                $('.user-image').attr('src', URL + 'storage/avatars-default.jpg');
                            }
                        }
                    }
                });

                app.loadHtml('home/menu', function (response) {
                    $('#menu-bar').html(response);
                    // === Sidebar navigation === //

                    $('.submenu > a').click(function (e)
                    {
                        e.preventDefault();
                        var submenu = $(this).siblings('ul');
                        var li = $(this).parents('li');
                        var submenus = $('#menu-bar li.submenu ul');
                        var submenus_parents = $('#menu-bar li.submenu');
                        if (li.hasClass('open'))
                        {
                            if (($(window).width() > 768) || ($(window).width() < 479)) {
                                submenu.slideUp();
                            } else {
                                submenu.fadeOut(250);
                            }
                            li.removeClass('open');
                        } else
                        {
                            if (($(window).width() > 768) || ($(window).width() < 479)) {
                                submenus.slideUp();
                                submenu.slideDown();
                            } else {
                                submenus.fadeOut(250);
                                submenu.fadeIn(250);
                            }
                            submenus_parents.removeClass('open');
                            li.addClass('open');
                        }
                    });

                    var ul = $('#menu-bar > ul');

                    $('#menu-bar > a').click(function (e)
                    {
                        e.preventDefault();
                        var sidebar = $('#menu-bar');
                        if (sidebar.hasClass('open'))
                        {
                            sidebar.removeClass('open');
                            ul.slideUp(250);
                        } else
                        {
                            sidebar.addClass('open');
                            ul.slideDown(250);
                        }
                    });

                    $('#loading-page').hide();
                });
            }
        });

    };

    $('.mask-cpf').mask('999.999.999-99');
    $('.mask-cnpj').mask('99.999.999/9999-99');
    $('.mask-cep').mask('99.999-999');
    $('.mask-date').mask('99/99/9999');
    $('.mask-telephone').mask('(99) 9999-9999');
    $('.mask-cellphone').mask('(99) 99999-9999');

    if (!isMobile) {
        $("#sidebar-toggler").click();
    }

    $('#sidebar-option-select').val('sidebar-fixed-minified').change();
});

function onlynumber(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    //var regex = /^[0-9.,]+$/;
    var regex = /^[0-9.]+$/;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault)
            theEvent.preventDefault();
    }
}