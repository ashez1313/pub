/**
 * Блокировка кнопки изменения ответственного
 */
export const blockAssignedButton = function () {
    // находим нужный div
    let assignedDiv = document.querySelector('[data-cid="ASSIGNED_BY_ID"]');

    if (assignedDiv) {
        // делаем клона и вставляем после оригинала
        let assignedDivNew = assignedDiv.cloneNode(true);
        BX.insertAfter(assignedDivNew, assignedDiv);
        // оригинал прячем
        assignedDiv.style.display = 'none';

        // добавляем класс для применения стилей (станет полупрозрачным)
        BX.addClass(assignedDivNew, 'ui-entity-editor-content-block-disabled');

        // находим кнопку "Сменить"
        let newAssignedChangeButton = BX.findChild(assignedDivNew, {
            tag: 'span',
            class: 'crm-widget-employee-change'
        }, true);

        // вешаем на нее свой обработчик
        BX.bind(newAssignedChangeButton, 'click', function () {
            // формируем сообщение в алерте
            let message = BX.create('DIV', {
                props: {className: 'custom-error-msg'},
                children: [
                    BX.create('DIV', {
                        props: {className: 'custom-error-msg-header'},
                        html: '<div class="ui-icon ui-icon-common-info"><i></i></div><h3>Недостаточно прав для изменения ответственного!</h3>'
                    }),
                    BX.create('P', {text: 'Обратитесь к администратору портала'})
                ]
            });
            // выводим алерт
            BX.UI.Dialogs.MessageBox.alert(message, (
                    messageBox, button, event) => {
                    messageBox.close();
                }
            );
        })
    }
}