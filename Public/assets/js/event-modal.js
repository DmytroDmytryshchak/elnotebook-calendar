var EventModal = (function () {

    var modal        = document.getElementById('event-modal');
    var backdrop     = document.getElementById('modal-backdrop');
    var modalTitle   = document.getElementById('modal-title');
    var errorsBlock  = document.getElementById('modal-errors');
    var btnClose     = document.getElementById('modal-close');
    var btnCancel    = document.getElementById('btn-cancel-modal');
    var btnSave      = document.getElementById('btn-save-event');
    var btnDelete    = document.getElementById('btn-delete-event');
    var btnAddEvent  = document.getElementById('btn-add-event');

    var fieldId          = document.getElementById('event-id');
    var fieldTitle       = document.getElementById('event-title');
    var fieldDescription = document.getElementById('event-description');
    var fieldStarts      = document.getElementById('event-starts');
    var fieldEnds        = document.getElementById('event-ends');
    var fieldColor       = document.getElementById('event-color');
    var fieldAllDay      = document.getElementById('event-allday');

    document.addEventListener('DOMContentLoaded', function () {
        btnClose.addEventListener('click',   close);
        btnCancel.addEventListener('click',  close);
        btnSave.addEventListener('click',    save);
        btnDelete.addEventListener('click',  destroy);
        backdrop.addEventListener('click',   close);

        if (btnAddEvent) {
            btnAddEvent.addEventListener('click', function () {
                var today = new Date();
                var dateStr = formatDateForInput(today);
                openCreate(dateStr);
            });
        }

        fieldAllDay.addEventListener('change', function () {
            toggleTimeFields(this.checked);
        });
    });

    function openCreate(date) {
        clearForm();
        setMode('create');

        fieldStarts.value = date + 'T09:00';
        fieldEnds.value   = date + 'T10:00';

        show();
    }

    function openEdit(eventId) {
        clearForm();
        setMode('edit');
        showLoading(true);
        show();

        // Завантажуємо дані події з сервера
        Ajax.get('/final_project/Public/events/' + eventId, function (err, data) {
            showLoading(false);

            if (err || !data.success) {
                showErrors('Failed to load event.');
                return;
            }

            fillForm(data.event);
        });
    }


    function show() {
        modal.classList.remove('modal--hidden');
        document.body.style.overflow = 'hidden'; // блокуємо скрол сторінки
    }

    function close() {
        modal.classList.add('modal--hidden');
        document.body.style.overflow = '';
        clearErrors();
    }

    function save() {
        clearErrors();

        var data = getFormData();
        var id   = fieldId.value;

        if (id) {
            Ajax.put('/final_project/Public/events/' + id, data, function (err, response) {
                handleSaveResponse(err, response);
            });
        } else {
            Ajax.post('/final_project/Public/events', data, function (err, response) {
                handleSaveResponse(err, response);
            });
        }
    }

    function handleSaveResponse(err, response) {
        if (err) {
            if (err.errors) {
                showErrors(err.errors);
            } else {
                showErrors('Something went wrong. Please try again.');
            }
            return;
        }

        close();
        window.reloadCalendar();
    }

    function destroy() {
        var id = fieldId.value;
        if (!id) { return; }

        if (!confirm('Delete this event?')) { return; }

        Ajax.delete('/final_project/Public/events/' + id, function (err, response) {
            if (err) {
                showErrors('Failed to delete event.');
                return;
            }

            close();
            window.reloadCalendar();
        });
    }

    function fillForm(event) {
        fieldId.value          = event.id;
        fieldTitle.value       = event.title;
        fieldDescription.value = event.description || '';
        fieldColor.value       = event.color;
        fieldAllDay.checked    = event.all_day == 1;

        if (event.all_day == 1) {

            fieldStarts.value = event.starts_at.substring(0, 10);
            fieldEnds.value   = event.ends_at.substring(0, 10);

        } else {

            fieldStarts.value = mysqlToInputFormat(event.starts_at);
            fieldEnds.value   = mysqlToInputFormat(event.ends_at);
        }

        toggleTimeFields(fieldAllDay.checked);
    }

    function getFormData() {
        var starts = fieldStarts.value;
        var ends   = fieldEnds.value;

        // якщо all day і дати однакові —
        // додаємо +1 день до ends_at
        if (fieldAllDay.checked && starts === ends) {

            var endDate = new Date(ends);
            endDate.setDate(endDate.getDate() + 1);

            ends = endDate.toISOString().split('T')[0];
        }

        return {
            title:       fieldTitle.value,
            description: fieldDescription.value,
            starts_at:   starts,
            ends_at:     ends,
            color:       fieldColor.value,
            all_day:     fieldAllDay.checked ? '1' : '0'
        };
    }

    function clearForm() {
        fieldId.value          = '';
        fieldTitle.value       = '';
        fieldDescription.value = '';
        fieldStarts.value      = '';
        fieldEnds.value        = '';
        fieldColor.value       = '#5856d6';
        fieldAllDay.checked    = false;
        toggleTimeFields(false);
    }

    function setMode(mode) {
        if (mode === 'create') {
            modalTitle.textContent = 'New Event';
            btnDelete.classList.add('modal--hidden');
        } else {
            modalTitle.textContent = 'Edit Event';
            btnDelete.classList.remove('modal--hidden');
        }
    }

    function toggleTimeFields(isAllDay) {
        var timeType = isAllDay ? 'date' : 'datetime-local';
        fieldStarts.type = timeType;
        fieldEnds.type   = timeType;
    }

    function showLoading(isLoading) {
        btnSave.disabled = isLoading;
        btnSave.textContent = isLoading ? 'Loading...' : 'Save';
    }

    function showErrors(errors) {
        errorsBlock.classList.remove('modal--hidden');

        if (typeof errors === 'string') {
            errorsBlock.textContent = errors;
            return;
        }

        var messages = [];
        for (var field in errors) {
            if (errors.hasOwnProperty(field)) {
                messages.push(errors[field]);
            }
        }
        errorsBlock.textContent = messages.join(' | ');
    }

    function clearErrors() {
        errorsBlock.classList.add('modal--hidden');
        errorsBlock.textContent = '';
    }

    function mysqlToInputFormat(datetime) {
        if (!datetime) { return ''; }
        return datetime.substring(0, 10) + 'T' + datetime.substring(11, 16);
    }

    function formatDateForInput(date) {
        var y = date.getFullYear();
        var m = String(date.getMonth() + 1).padStart(2, '0');
        var d = String(date.getDate()).padStart(2, '0');
        return y + '-' + m + '-' + d;
    }

    return {
        openCreate: openCreate,
        openEdit:   openEdit,
        close:      close
    };

})();
