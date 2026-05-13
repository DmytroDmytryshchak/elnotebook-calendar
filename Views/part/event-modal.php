<div id="event-modal" class="modal modal--hidden">

    <div class="modal-backdrop" id="modal-backdrop"></div>

    <div class="modal-box">

        <div class="modal-header">
            <h3 class="modal-title" id="modal-title">New Event</h3>
            <button type="button" class="modal-close" id="modal-close">✕</button>
        </div>

        <div class="modal-body">

            <div id="modal-errors" class="alert alert-error modal--hidden"></div>

            <input type="hidden" id="event-id" value="">

            <div class="form-group">
                <label for="event-title" class="form-label">Title *</label>
                <input type="text"
                       id="event-title"
                       class="form-input"
                       placeholder="Event title"
                       maxlength="200">
            </div>

            <div class="form-group">
                <label for="event-description" class="form-label">Description</label>
                <textarea id="event-description"
                          class="form-input form-textarea"
                          placeholder="Optional description"
                          rows="3"></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="event-starts" class="form-label">Start *</label>
                    <input type="datetime-local"
                           id="event-starts"
                           class="form-input">
                </div>

                <div class="form-group">
                    <label for="event-ends" class="form-label">End *</label>
                    <input type="datetime-local"
                           id="event-ends"
                           class="form-input">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="event-color" class="form-label">Color</label>
                    <input type="color"
                           id="event-color"
                           class="form-input form-color"
                           value="#5856d6">
                </div>

                <div class="form-group form-group--checkbox">
                    <label class="form-label">
                        <input type="checkbox" id="event-allday">
                        All day
                    </label>
                </div>
            </div>

        </div>

        <div class="modal-footer">

            <button type="button"
                    class="btn btn-danger modal--hidden"
                    id="btn-delete-event">Delete</button>

            <div class="modal-footer-right">
                <button type="button"
                        class="btn btn-outline"
                        id="btn-cancel-modal">Cancel</button>

                <button type="button"
                        class="btn btn-primary"
                        id="btn-save-event">Save</button>
            </div>

        </div>

    </div>

</div>
