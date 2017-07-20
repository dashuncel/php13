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
    <form>
        <label>Описание дела:<input type="text"></label>
        <input type="submit" value="Сохранить">
    </form>
    </div>
</div>
<input type="button" value="Добавить новое дело" name="add">
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
<script type="text/javascript">
    'use strict';

    // обработчик клика на заголовке таблицы (сортировка):
    $('th').click(function(event){
        let desc = event.currentTarget.dataset.sort;
        let col = event.currentTarget.dataset.col;

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
        $.post("sort.php",
                { sort: desc, column : col},
                function(data, result){
                     $('tbody').html(data);
                     event.target.dataset.sort = (event.currentTarget.dataset.sort == "asc") ? "desc" : "asc"; // меняем направление сортировки
                }
        );
    });

    // обработчик клика по таблице (используем всплытие Тк таблицу перерисовываем и теряем обработчики):
    $('table').click(function(event){
        // обработчик щелка по колонке с исполненным: изменение статуса "исполнен":
        if (event.target.tagName == 'TD' && (event.target.classList[0] == 'done' || event.target.classList[0] == 'undone')) {
            let done = (event.target.classList[0] == "undone") ? "1" : "0";
            let id = event.target.id;
            $.post("update.php",
                {id: id, done : done} ,
                function(data, status) {
                    $('tbody').html(data);
                }
            );
        }

        if (event.target.tagName == 'IMG' && event.target.parentNode.classList[0] == 'del') {
            let id = event.target.parentNode.id;
            $.post("delete.php",
                   {id : id},
                   function(data, result){
                       $('tbody').html(data);
                   }
            );
        }

        if (event.target.tagName == 'A' && event.target.classList[0] == 'edit') {
            let id = event.target.id;
            let description = showModal();
            $.post("update.php",
                {id : id, description: description},
                function(data, result){
                    $('tbody').html(data);
                }
            );
        }
    });

    // обработчик кнопки добавление нового дела - модальное окно
    $('[type = "button"]').click(function(event){
        let desc = showModal('');
    });
    
    function showModal(desc) {
    }

</script>
</body>
</html>

