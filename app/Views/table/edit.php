<?php require_once __DIR__ . '/../components/header.php'?>
<?php require_once __DIR__ . '/../components/title.php'?>
<div class="container mt-5">
    <?php if(!empty($data['error'])): ?>
        <div class="text-danger">
            <?=$data['error']; ?>
        </div>
    <? endif; ?>

    <div class="mb-3">
        <form method="post" action="/table/store/<?=$data['table']; ?>">
            <div class="d-flex justify-content-around bd-highlight mb-2">
                <?php if(!empty($data['columns'])): ?>
                    <?php foreach($data['columns'] as $column): ?>
                        <input class="form-control" name="<?=$column; ?>" placeholder="<?=$column; ?>">
                    <? endforeach; ?>
                <? endif; ?>
            </div>
            <button class="btn btn-sm btn-success">Добавить запись</button>
        </form>
    </div>

    <table class="table">
        <thead>
            <tr>
                <?php if(!empty($data['columns'])): ?>
                    <?php foreach($data['columns'] as $column): ?>
                        <th><?=$column; ?></th>
                    <? endforeach; ?>
                <? endif; ?>
            </tr>
        </thead>
        <tbody>

        <?php if(!empty($data['rowsList'])): ?>
            <?php foreach($data['rowsList'] as $row): ?>
                <tr>
                    <?php if(!empty($data['columns'])): ?>
                        <?php foreach($data['columns'] as $column): ?>
                            <td><?=$row[$column]; ?></td>
                        <? endforeach; ?>
                    <? endif; ?>
                </tr>
            <? endforeach; ?>
        <? endif; ?>
        </tbody>
    </table>

</div>
<?php require_once __DIR__ . '/../components/footer.php'?>