(function () {

    var POLL_INTERVAL = 30000;

    var badge        = document.getElementById('notif-badge');
    var bellBtn      = document.getElementById('notif-bell-btn');
    var dropdown     = document.getElementById('notif-dropdown');
    var notifList    = document.getElementById('notif-list');
    var dismissAll   = document.getElementById('notif-dismiss-all');

    var shownIds = {};

    document.addEventListener('DOMContentLoaded', function () {
        poll();

        setInterval(poll, POLL_INTERVAL);

        if (bellBtn) {
            bellBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                toggleDropdown();
            });
        }

        if (dismissAll) {
            dismissAll.addEventListener('click', function () {
                markAllSeen();
            });
        }

        document.addEventListener('click', function () {
            hideDropdown();
        });
    });

    function poll() {
        Ajax.get('/notifications/pending', function (err, data) {
            if (err || !data.success) { return; }

            updateBadge(data.count);
            updateDropdown(data.notification);

            for (var i = 0; i < data.notification.length; i++) {
                var notif = data.notification[i];
                if (!shownIds[notif.id]) {
                    shownIds[notif.id] = true;
                    showToast(notif);
                }
            }
        });
    }

    function updateBadge(count) {
        if (!badge) { return; }

        if (count > 0) {
            badge.textContent = count > 99 ? '99+' : count;
            badge.classList.remove('notif-badge--hidden');
        } else {
            badge.textContent = '';
            badge.classList.add('notif-badge--hidden');
        }
    }

    function updateDropdown(notification) {
        if (!notifList) { return; }

        if (notification.length === 0) {
            notifList.innerHTML = '<p class="notif-empty">No new notification</p>';
            return;
        }

        var html = '';
        for (var i = 0; i < notification.length; i++) {
            var n = notification[i];
            var timeStr = formatTime(n.event_starts_at);

            html += '<div class="notif-item" data-id="' + n.id + '">';
            html += '  <div class="notif-item-title">📅 ' + escapeHtml(n.event_title) + '</div>';
            html += '  <div class="notif-item-time">Starts at ' + timeStr + '</div>';
            html += '  <button class="notif-item-dismiss" data-id="' + n.id + '">✕</button>';
            html += '</div>';
        }

        notifList.innerHTML = html;

        var dismissBtns = notifList.querySelectorAll('.notif-item-dismiss');
        for (var j = 0; j < dismissBtns.length; j++) {
            dismissBtns[j].addEventListener('click', function (e) {
                e.stopPropagation();
                var id = this.getAttribute('data-id');
                markOneSeen(id);
            });
        }
    }

    function showToast(notif) {
        var toast = document.createElement('div');
        toast.className = 'notif-toast';
        toast.innerHTML = '<strong>Upcoming event</strong><br>'
            + escapeHtml(notif.event_title) + '<br>'
            + '<small>Starts at ' + formatTime(notif.event_starts_at) + '</small>';

        document.body.appendChild(toast);

        setTimeout(function () {
            toast.classList.add('notif-toast--hide');
            setTimeout(function () {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 400);
        }, 5000);

        toast.addEventListener('click', function () {
            markOneSeen(notif.id);
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        });
    }

    function markOneSeen(id) {
        Ajax.post('/notifications/' + id + '/seen', {}, function (err, data) {
            if (!err && data && data.success) {
                var item = document.querySelector('.notif-item[data-id="' + id + '"]');
                if (item) { item.parentNode.removeChild(item); }
                poll();
            }
        });
    }

    function markAllSeen() {
        Ajax.post('/notifications/seen-all', {}, function (err, data) {
            if (!err && data && data.success) {
                shownIds = {};
                poll();
                hideDropdown();
            }
        });
    }

    function toggleDropdown() {
        if (!dropdown) { return; }
        if (dropdown.classList.contains('notif-dropdown--hidden')) {
            dropdown.classList.remove('notif-dropdown--hidden');
        } else {
            dropdown.classList.add('notif-dropdown--hidden');
        }
    }

    function hideDropdown() {
        if (dropdown) {
            dropdown.classList.add('notif-dropdown--hidden');
        }
    }

    function formatTime(datetime) {
        if (!datetime) { return ''; }
        var time = datetime.substring(11, 16);
        var date = datetime.substring(0, 10);
        return time + ' ' + date;
    }

    function escapeHtml(str) {
        var div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

})();
