var type;

$(document).ready(() => {
    getItems('posts', 'posts');
    getItems('categories', 'categories');

    $('input#post_id').next('button').click(function (e) {
        let inpval = $(this).prev('input').val();
    
        getItems('posts/' + inpval, 'post');
    });

    $('input#category_id').next('button').click(function (e) {
        let inpval = $(this).prev('input').val();
    
        getItems('categories/' + inpval, 'category');
    });

    $('input#category_id_posts').next('button').click(function (e) {
        let inpval = $(this).prev('input').val();
    
        getItems('category_posts/' + inpval, 'category_posts');
    });
});

function getItems(url, itemsType) {
    type = url;

    fetch('http://v.balabanov.study.dev0.ddemo.ru/public/api/' + type, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Basic ZGVtbzpkZW1v'
        }
    })
    .then((response) => {
        return response.json();
    })
    .then((data) => {
        appendToDOM(data, itemsType);
    });
}

function appendToDOM(data, itemsType) {
    $('#' + itemsType).find('.list').text('');

    if (data.length) {
        for (const item of data) {
            $('#' + itemsType).find('.list').append('<ul></ul>');
            
            let $ul = $('#' + itemsType).find($('ul').last());
            
            if ((itemsType.indexOf('categor') !== -1) && (itemsType != 'category_posts')) {
                $ul.append('<li>id: ' + item.id + '</li>');
                $ul.append('<li>название: ' + item.name + '</li>');
                $ul.append('<li>количество объявлений: ' + item.count_posts + '</li>');
            } else if (itemsType == 'category_posts') {
                $ul.append('<li>id категории: ' + item.category_id + '</li>');
                $ul.append('<li>название категории: ' + item.category_name + '</li>');
                $ul.append('<li>id товара: ' + item.post_id + '</li>');
                $ul.append('<li>название товара: ' + item.post_title + '</li>');
                $ul.append('<li>описание товара: ' + item.post_content + '</li>');
            } else {
                $ul.append('<li>id: ' + item.id + '</li>');
                $ul.append('<li>название: ' + item.title + '</li>');
                $ul.append('<li>описание: ' + item.content + '</li>');
                $ul.append('<li>цена: ' + item.price + '</li>');
            }
        }
    } else {
        $('#' + itemsType).find('.list').append('<ul></ul>');
            
        let $ul = $('#' + itemsType).find($('ul'));

        if ((itemsType.indexOf('categor') !== -1) && (itemsType != 'category_posts')) {
            $ul.append('<li>id: ' + data.id + '</li>');
            $ul.append('<li>название: ' + data.name + '</li>');
            $ul.append('<li>количество объявлений: ' + data.count_posts + '</li>');
        } else if (itemsType == 'category_posts') {
            $ul.append('<li>id категории: ' + item.category_id + '</li>');
            $ul.append('<li>название категории: ' + item.category_name + '</li>');
            $ul.append('<li>id товара: ' + item.post_id + '</li>');
            $ul.append('<li>название товара: ' + item.post_title + '</li>');
            $ul.append('<li>описание товара: ' + item.post_content + '</li>');
        } else {
            $ul.append('<li>id: ' + data.id + '</li>');
            $ul.append('<li>название: ' + data.title + '</li>');
            $ul.append('<li>описание: ' + data.content + '</li>');
            $ul.append('<li>цена: ' + data.price + '</li>');
        }
    }
}