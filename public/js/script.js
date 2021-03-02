const btnAddTableModal = document.getElementById('btnAddTableModal');
const btnCloseAddTableModal = document.getElementById('btnCloseAddTableModal');
const formFieldsList = document.getElementById('formFieldsList');
const btnAddField = document.getElementById('btnAddField');
const inputTableName = document.getElementById('inputTableName');
const addTableModal = new bootstrap.Modal(document.getElementById('addTableModal'), {
    keyboard: false
});

btnAddTableModal.addEventListener('click', function (event) {
    addTableModal.show();
});
btnAddTableModal.addEventListener('shown.bs.modal', function () {
    inputTableName.focus();
});

btnCloseAddTableModal.addEventListener('click', function (event) {
    addTableModal.hide();
});

btnAddField.addEventListener('click', function (event) {
    const newField = formFieldsList.querySelector('ul>li:first-child').cloneNode(true);
    newField.querySelector('input').value = '';
    formFieldsList.querySelector('ul').appendChild(newField);
});

