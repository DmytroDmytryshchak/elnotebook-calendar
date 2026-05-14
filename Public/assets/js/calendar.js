// calendar.js
// Відповідає за поведінку сітки календаря:
// клік по дню → відкрити модал для нової події
// клік по події → відкрити модал для редагування

(function () {

    // Чекаємо поки DOM завантажиться
    document.addEventListener('DOMContentLoaded', function () {
        initDayClicks();
        initEventChipClicks();
    });

    // Клік по порожній клітинці дня — відкриваємо модал "нова подія"
    function initDayClicks() {
        var days = document.querySelectorAll('.day-cell');

        for (var i = 0; i < days.length; i++) {
            days[i].addEventListener('click', function (e) {
                // Якщо клікнули на event-chip — не відкривати новий модал
                if (e.target.closest('.event-chip')) {
                    return;
                }

                var date = this.getAttribute('data-date');
                if (!date) { return; }

                // Відкриваємо модал із заповненою датою
                openCreateModal(date);
            });
        }
    }

    // Клік по плашці події — відкриваємо модал "редагування"
    function initEventChipClicks() {
        var chips = document.querySelectorAll('.event-chip');

        for (var i = 0; i < chips.length; i++) {
            chips[i].addEventListener('click', function (e) {
                e.stopPropagation(); // щоб не спрацював клік по day-cell

                var eventId = this.getAttribute('data-event-id');
                if (!eventId) { return; }

                openEditModal(eventId);
            });
        }
    }

    // Ці функції реалізовані в event-modal.js
    // Тут просто викликаємо їх як зовнішній API
    function openCreateModal(date) {
        if (typeof EventModal !== 'undefined') {
            EventModal.openCreate(date);
        }
    }

    function openEditModal(eventId) {
        if (typeof EventModal !== 'undefined') {
            EventModal.openEdit(eventId);
        }
    }

    // Перезавантажує сторінку з тим самим місяцем/роком
    // Викликається після збереження або видалення події
    window.reloadCalendar = function () {
        window.location.href = '/final_project/Public/calendar?year=' + CALENDAR_YEAR
            + '&month=' + CALENDAR_MONTH;
    };

})();