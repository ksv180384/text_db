<?php require_once __DIR__ . '/components/header.php'?>
<?php require_once __DIR__ . '/components/title.php'?>
<section class="container">
    <div class="row">
        <div class="col-12">
            <button id="btnAddTableModal"
                    class="btn btn-sm btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#addTable"
            >
                Добавить таблицу
            </button>
            <ul class="list-group mt-3">
                <?php if(!empty($data['tablesList'])): ?>
                    <?php foreach ($data['tablesList'] as $table): ?>
                        <li class="list-group-item">
                            <a href="/table/show/<?=$table; ?>"><?=$table; ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

        </div>
    </div>
</section>

    <div id="addTableModal" class="modal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="formFieldsList" class="mt-2" method="post" action="index/store">
                    <div class="modal-header">
                        <h5 class="modal-title">Форма добавления таблицы</h5>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <input id="inputTableName"
                                   type="text"
                                   class="form-control"
                                   name="table_name"
                                   placeholder="Название таблицы"
                                   required
                            >
                        </div>
                        <button id="btnAddField"
                                class="btn btn-sm btn-success mb-3"
                        >
                            Добавить новое поле
                        </button>

                        <ul class="list-group">
                            <li class="list-group-item">
                                <input type="text" class="form-control" name="fields[]" placeholder="Название поля" required>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                        <button id="btnCloseAddTableModal" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                    </div>
                </form>
            </div>
        </div>

<?php require_once __DIR__ . '/components/footer.php'?>