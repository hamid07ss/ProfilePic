/**
 * Created by h.sarani on 1/18/2017.
 */

var move = false;

$(window).load(function () {
    loadFonts();

    var text_doc = '<div class="text-field"></div>';

    console.log(typeof tinymce);
    if(typeof(tinyMCE) != "undefined"){
        tinymce.init({
            selector: '#TextEditor',
            height: 300,
            width: 500,
            theme: 'modern',
            plugins: ['image'],
            /*plugins: [
                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                'searchreplace wordcount visualblocks visualchars code fullscreen',
                'insertdatetime media nonbreaking save table contextmenu directionality',
                'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
            ],*/
            // styleselect |‌bullist numlist outdent indent | link |fontsizeselect | fontselect |
            toolbar1: 'undo redo | insert |  bold italic | alignleft aligncenter alignright alignjustify |  image',
            toolbar2: '  print preview media | forecolor backcolor emoticons | codesample',
            menubar:false,
            statusbar: false,
            fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
            image_advtab: true,
            templates: [
                { title: 'Test template 1', content: 'Test 1' },
                { title: 'Test template 2', content: 'Test 2' }
            ],
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tinymce.com/css/codepen.min.css'
            ],
            setup:function(ed) {
                ed.on('keyup', function(e) {
                    $('.text-field').html(ed.getContent());
                });
                ed.on('change', function(e) {
                    $('.text-field').html(ed.getContent());
                });
            }
        });
    }

    loadImgs();


    $('.save-this-and-new').click(function () {
        if($('.text-field').find('img').length > 0){
            move = false;
            $('.text-field').attr('class', 'text-field-image');

            tinymce.activeEditor.setContent('');
            return;
        }
        move = false;
        $('.text-field').attr('class', 'text-field-saved');

        tinymce.activeEditor.setContent('');
    });

    $(window).click(function (e) {
        if($(e.target).hasClass('text-field')){
            $(e.target).addClass('move');
        }else{
            $(e.target).removeClass('move');
        }
    });

    $(document).keydown(function(e) {
        // if(!$('.text-field').hasClass('move')){
        //     return;
        // }

        var code = e.keyCode || e.which;
        var inp = '';
        /*
        left = 37
        up = 38
        right = 39
        down = 40
        */
        if(code == 37){
            e.preventDefault();
            $('.picture-left').val(parseInt($('.picture-left').val()) - 1);
            inp = $('.picture-left');
        }
        if(code == 39){
            e.preventDefault();
            $('.picture-left').val(parseInt($('.picture-left').val()) + 1);
            inp = $('.picture-left');
        }


        if(code == 38){
            e.preventDefault();
            $('.picture-top').val(parseInt($('.picture-top').val()) - 1);
            inp = $('.picture-top');
        }
        if(code == 40){
            e.preventDefault();
            $('.picture-top').val(parseInt($('.picture-top').val()) + 1);
            inp = $('.picture-top');
        }

        if(inp != '')
            changeParameters($(inp), 'px');
    });

    $('#getPhoto').click(function () {
        var formData = new FormData();

        formData.append('image', $('input[type=file]')[0].files[0]);

        console.log(formData);

        $.ajax({
            url: '/save-pic.php',
            data: formData,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (res) {
                $('.picture').attr('src', res);
            }
        })
    });

    $(".picture").on('mousedown', function (e) {

        if ($('.text-field').length == 0) {
            $('.pic-cont').append(text_doc);
        }

        var bord = $('.text-field');
        $(bord).css({
            top: e.pageY,
            left: e.pageX
        });
        $('.options input.picture-top').val(e.pageY - ($(bord).height() / 2));
        $('.options input.picture-left').val(e.pageX - ($(bord).width() / 2));

        if(!move){
            var pic = $('.pic-cont');

            $(pic).bind('mousemove', setSize);
            $(pic).on('mouseup', function () {
                $(pic).unbind('mousemove', setSize);
            });
        }

    });

    $('.enable-move').click(function () {
        var pic1 = $('.pic-cont');
        if(move){
            move = false;

            $(pic1).bind('mousemove', setSize);
            $(pic1).on('mouseup', function () {
                $(pic1).unbind('mousemove', setSize);
            });
        }else{
            move = true;

            $(pic1).unbind('mousemove', setSize);

            var pic = $('.pic-cont');

            $(pic).on('mousedown', function () {
                $(pic).bind('mousemove', moveField);
            });
            $(pic).on('mouseup', function () {
                $(pic).unbind('mousemove', moveField);
            });
        }
    });

    $('.options input').on('focus', function (event) {

        $(window).bind('mousewheel', changeVal);

        changeParameters($(event.target));
    });

    $('.options input').on('focusout', function (event) {
        $(window).unbind('mousewheel', changeVal);
    });

    $('.picture-text-align').on('change', function (e) {
        $('.text-field').css('text-align', $(this).val());
    });

    var inter = setInterval(function () {
        if($('#tinymce').length == 0){
            return;
        }
        clearInterval(inter);
        $('#tinymce').on('change', function() {
            $('.text-field').html($('#tinymce').html());
        });
    }, 1000);

    /*var mce = setInterval(function () {
        if($('div#mceu_55').length == 0){
            return;
        }
        clearInterval(mce);
        var fonts = $('div#mceu_55').clone().remove();
        $('.other-opt').append(fonts);

        $('div#mceu_55 .mce-menu-item').click(function (e) {
            var font = $(e.target).closest('.mce-menu-item').find('.mce-text').css('font-family');
            $('.text-field').css('font-family', font);
        });
    }, 1000)*/
});

function selectImage(elm) {
    $('.picture').attr('src', $(elm).attr('src'));

    $('.images_pop').removeClass('act');
}

function loadImgs() {
    $.ajax({
        url: '/getImages.php',
        type: 'POST',
        success: function (data) {
            data = JSON.parse(data);
            console.log(data);
            var html = '';

            $.each(data, function (key, arr) {
                html += '<div class="images-cont"><div class="head"><h2>'+ key +'</h2></div><ul class="images-ul">';
                $.each(arr, function (i, src) {
                    html += '<li class="img-cont"><div>'+ baseName(src) +'</div><img class="old-pic" onclick="selectImage(this);" src="' + src + '" /></li>';
                });
                html += '</ul></div>';
            });

            if(html != '')
                $('.show-images').append(html);
        }
    });
}

function changeParameters(inp, px) {
    if ($(inp).attr('name') == 'rotate') {
        $('.text-field').css('transform', 'rotate(' + $(inp).val() + 'deg)');
    } else if ($(inp).attr('name') == 'font-size') {
        $('.text-field').css('font-size',  $(inp).val() + px);
        $('.text-field *').css('font-size',  $(inp).val() + px);
    } else {
        $('.text-field').css($(inp).attr('name'), $(inp).val() + px);
    }
}

function changeVal(e) {
    e.preventDefault();

    var inp = $('.options input:focus');
    var inpVal = $(inp).val();
    if (!inpVal) {
        $(inp).val(1);
        inpVal = 1;
    }
    var px = '';

    if ($(inp).attr('name') == 'top' || $(inp).attr('name') == 'left' || $(inp).attr('name') == 'font-size') {
        if (!$(inp).val()) {
            $(inp).val($('.text-field').css($(inp).attr('name')));
            inpVal = $('.text-field').css($(inp).attr('name'));
        }else{
            px = 'px';
        }
    }

    if (e.originalEvent.wheelDelta < 0) {
        //    scroll down
        $(inp).val((parseInt(inpVal) - 1));
    } else {
        //    scroll up
        $(inp).val((parseInt(inpVal) + 1));
    }


    changeParameters($(inp), px);
}

function moveField(event) {
    event.preventDefault();

    var bord = $('.text-field');
    $(bord).css({
        top: event.pageY - ($(bord).height() / 2),
        left: event.pageX - ($(bord).width() / 2)
    });
    $('.options input.picture-top').val(event.pageY - ($(bord).height() / 2));
    $('.options input.picture-left').val(event.pageX - ($(bord).width() / 2));
}

function setSize(event) {
    event.preventDefault();
    var bord = $('.text-field');


    $(bord).css({
        width: event.pageX - parseInt($(bord).css('left')),
        height: event.pageY - parseInt($(bord).css('top'))
    });

    $('.options input.picture-width').val(parseInt($(bord).css('width')));
    $('.options input.picture-height').val(parseInt($(bord).css('height')));
}

function saveImage() {
    var html = $('.pic-cont').clone();

    var imgCode = $(html).html();
    var fileName = $('.fileName').val();
    var imgSrc = '';
    var namesCount = $('.pic-cont .text-field-saved').length;

    var element = $('.pic-cont');
    html2canvas(element, {
        fontFamily: $(element).find("text-field").css("font-family"),
        onrendered: function(canvas) {
            imgSrc = canvas.toDataURL();

            $.ajax({
                url: '/functions/saveImages.php',
                type: 'POST',
                data: {
                    act: 'Save',
                    imgSrc : imgSrc,
                    imgCode : imgCode,
                    namesCount : namesCount,
                    fileName : fileName
                },
                success: function (data) {
                    console.log(data);
                    $(element).after('<img class="channel_image" src="'+ data +'" />');
                }
            });
        }
    });
}
var ImageSource = "";

function sendToTel(chatId, id) {
    if(!$('.user-pic').attr('src')) {
        $.ajax({
            url: '/functions/saveImages.php',
            type: 'POST',
            data: {
                act: 'getFile',
                imgSrc: ImageSource,
                id: id,
                fileName: chatId
            },
            success: function (data) {
                $('canvas').before('<img class="user-pic" src="' + data + '" />').remove();
                $('.btn-send-tel').toggleClass('btn-success')
            }
        });
    }

    if($('.user-pic').attr('src')){
        var data = $('.user-pic').attr('src');
        $.ajax({
            url: '/functions/saveImages.php',
            type: 'POST',
            data: {
                act: 'sendToUser',
                imgAddr : data,
                id : id,
                chatId : chatId
            },
            success: function (data) {
                $('.btn-send-tel').before(data);
            }
        });
    }
}

function setNames(names) {
    var names_cont = $('.pic-cont').find('.text-field-saved');
    $.each(names, function (i, name) {
        var text = $(names_cont[i]).text();
        var html = $(names_cont[i]).html();

        $(names_cont[i]).html(html.replace(text, name));
    });

    var element = $('.pic-cont');
    html2canvas(element, {
        onrendered: function(canvas) {
            $(element).after(canvas);
            ImageSource = canvas.toDataURL();
        }
    });
}

function sendToChannel() {
    var img = $('.channel_image').attr('src');
    if(!img){
        img = $('.picture').attr('src');
    }
    img += '?time=' + new Date().getTime();
    var descs = $('.descs').val();

    $.ajax({
        url: '/functions/channel.php',
        type: 'POST',
        data: {
            img: img,
            descs : descs
        },
        success: function (data) {
            console.log(data);
        }
    })
}

function baseName(str)
{
    var base = new String(str).substring(str.lastIndexOf('/') + 1);
    if(base.lastIndexOf(".") != -1)
        base = base.substring(0, base.lastIndexOf("."));
    return base;
}

function loadFonts(){
    $.ajax({
        url: '/editor/styles/style.php',
        type: 'get',
        data: {
            fontsHtml: 'get font'
        },
        success: function (fontsHtml) {
            $(".font-list").html(fontsHtml);

            $(".font-list").find('li').click(function (e) {
                $('.text-field, .text-field *').css('font-family', $(e.target).css('font-family'))
            });
        }
    })
}