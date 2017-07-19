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
        <dd>клик по загловку таблицы</dd>
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

        // если направление сортировки не указано, выходим без запроса:
        if (desc === undefined) {
            return;
        }
        event.preventDefault();
        $.ajax({
            url: 'sort.php',
            type: 'POST',
            data: `sort=${desc}&column=${col}`,
            success: function(result){
                $('tbody').html(result);
                event.currentTarget.dataset.sort = (desc == "asc") ? "desc" : "asc";
            }
        });
    });

    // обработчик щелка по колонке с исполненным: изменение статуса "исполнен":
    $('.done, .undone').click(function(event){
        event.preventDefault();
        let done = (event.currentTarget.classList[0] == "undone") ? "1" : "0";
        let id = event.currentTarget.id;
        $.ajax({
            url: 'update.php',
            type: 'POST',
            data: `id=${id}&done=${done}`,
            success: function(result){
                $('tbody').html(result);
            }
        });
    });

    // обработчик кнопки remove/edit
    $('a').click(function(event){
        event.preventDefault();
        let id = event.currentTarget.id;
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: `id=${id}`,
            success: function(result){
                $('tbody').html(result);
            }
        });
    });

    // обработчик кнопки добавление нового дела - модальное окно
    $('[type = "button"]').click(function(event){
        showModal();
    });
    
    function showModal() {
        
    }

</script>
</body>
</html>

