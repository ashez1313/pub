import {blockAssignedButton} from "./blockAssignedButton";

/**
 * Расширение блокирует кнопку изменения ответственного в карточке сделки
 */

BX.ready(() => {
    let currentWindow = top.window;

    if (top.BX.SidePanel && top.BX.SidePanel.Instance && top.BX.SidePanel.Instance.getTopSlider()) {
        currentWindow = top.BX.SidePanel.Instance.getTopSlider().getWindow();
        // подписываемся на событие, когда контент слайдера загружен
        BX.addCustomEvent(currentWindow, "SidePanel.Slider:onLoad", function (event) {

            let sliderObject = event.getSlider();
            // если загружен слайдер детального вида сделки
            if (sliderObject.iframeSrc !== false && sliderObject.iframeSrc.includes('/crm/deal/details/')) {
                // то блочим кнопку ответственного
                blockAssignedButton();
            }
        });
    }
});
