<?php
require_once __DIR__.DIRECTORY_SEPARATOR.'lib.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Список дел TODO</title>
    <link rel="stylesheet" href="index.css">
    <meta charset="utf-8">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.5/jquery.min.js"></script>
</head>
<body>
<div class="modal-wrapper">
    <div class="modal">
        <div class="head">
            <a class="btn-close trigger" href='#'></a>
            <div class="title"></div>
        </div>
        <form>
            <textarea name="desc" rows="4" cols="65" placeholder="Описание дела"></textarea>
            <input type="button" value="Сохранить" class="trigger creater">
        </form>
    </div>
</div>
<div class="page-wrapper">
<input type="button" class="trigger adder" value="Добавить новое дело" name="add">
<table>
    <thead><tr>
        <th data-sort="asc" data-col="date_added">Дата</th>
        <th data-sort="asc" data-col="description">Дело</th>
        <th data-sort="asc" data-col="is_done">Статус</th>
        <th>Действия</th>
    </tr></thead>
    <tbody>
    <?php echo prepareTable($mainQuery) ?>
    </tbody>
</table>
<div>
    <dl>
        <dt>Сортировка - </dt>
        <dd>клик по загловку таблицы, имеющим значок сортировки</dd>
        <dt>Изменение статуса - </dt>
        <dd>клик по ячейке в колонке "Статус"</dd>
        <dt>Редактирование и удаление - </dt>
        <dd>клик по кнопке в колонке "Действия"</dd>
    </dl>
</div>
</div>
<script type="text/javascript">
    'use strict';
    let desc; // направления сортировки колонок
    let col;  // колонки для сортировки
    let typeQuery;
    let id;

    // устанавливаем переменные сортировки таблицы:
    $(document).ready(function() {
        setSort();
    })

    // обработчик клика на заголовке таблицы (сортировка):
    $('th').click(function(event){
        desc = event.currentTarget.dataset.sort;
        col = event.currentTarget.dataset.col;

        // если направление сортировки на кликнутой колонке не указано, выходим без запроса:
        if (desc === undefined) {
            return;
        }

        // собираем прочие направления сортировки по колонкам:
        $('[data-sort*="sc"]').each(function (i, val) {
                    if (event.currentTarget != val) {
                        desc += ',' + val.dataset.sort;
                        col += ',' + val.dataset.col;
            }
        });
        $.post("query.php",
                { typeQuery: "sort", sort: desc, column : col},
                function(data, result){
                    setData(data);
                    event.target.dataset.sort = (event.currentTarget.dataset.sort == "asc") ? "desc" : "asc"; // меняем направление сортировки
                }
        );
    });

    // обработчик клика по таблице:
    $('table').click(function(event){

        // обработчик щелка по колонке с исполненным: изменение статуса "исполнен":
        if (event.target.tagName == 'TD' && (event.target.classList[0] == 'done' || event.target.classList[0] == 'undone')) {
            let done = (event.target.classList[0] == "undone") ? "1" : "0";
            id = event.target.parentNode.id;
            $.post("query.php",
                {typeQuery: "update", id: id, done : done} ,
                function(data, status) {
                    setData(data);
                }
            );
        }

        if (event.target.tagName == 'IMG' && event.target.parentNode.classList[0] == 'del') {
            id = $(event.target).parentsUntil('tbody').last().attr('id');
            $.post("query.php",
                 {typeQuery: "delete", id : id},
                 function(data, result) {
                    setData(data);
                 }
            );
        }

        if (event.target.tagName == 'IMG' && event.target.parentNode.classList[0] == 'edit') {
            let trow = $(event.target).parentsUntil('tbody').last(); // выходим на текущую строку
            id = $(trow).attr('id');
            let description = $(trow).children(':nth-child(2)').text(); // значение 2 колонки
            $('textarea').val(description);
            $('.title').text("Редактирование дела");
            typeQuery = 'update';
            showModal();
        }
    });

    // обработчик элементов, изменяющих статус модального окна (3 штуки - закрыть, добавить, сохранить)
    $('.trigger').click(function(event){
        showModal();
    });

    //
    $('.adder').click(function(event) {
        $('.title').text("Добавление нового дела");
        $('textarea').val('');
        typeQuery = 'create';
        id = '';
    });

    $('.creater').click(function(event) {
        let desc = $('textarea').val();
        $.post("query.php",
            {typeQuery: typeQuery, description: desc, id: id },
            function(data, result){
                setData(data);
            }
        );
    });
    
    function showModal() {
        $('.modal-wrapper').toggleClass('open');
        $('.page-wrapper').toggleClass('blur');
    }
    
    function setSort() {
        desc='';
        col='';
        $('[data-sort*="sc"]').each(function (i, val) {
            if (desc !== '') {desc += ","; }
            if (col !== '') {col += ","; }
            desc += val.dataset.sort;
            col  += val.dataset.col;
        });
    }

    function setData(data) {
        if (data !== 'undefined' && data.length > 0) {
            $('tbody').html(data);
        }
    }

</script>
</body>
</html>

