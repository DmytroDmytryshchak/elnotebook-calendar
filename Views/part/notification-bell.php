<?php
$count = isset($unseenCount) ? (int) $unseenCount : 0;
?>

<div class="notif-bell" id="notif-bell">

    <button type="button" class="notif-bell-btn" id="notif-bell-btn"
            title="Notifications">
        🔔
        <?php if ($count > 0): ?>
            <span class="notif-badge" id="notif-badge">
                <?php echo $count > 99 ? '99+' : $count; ?>
            </span>
        <?php else: ?>
            <span class="notif-badge notif-badge--hidden" id="notif-badge"></span>
        <?php endif; ?>
    </button>

    <div class="notif-dropdown notif-dropdown--hidden" id="notif-dropdown">

        <div class="notif-dropdown-header">
            <span>Notifications</span>
            <button type="button" class="notif-dismiss-all" id="notif-dismiss-all">
                Dismiss all
            </button>
        </div>

        <div class="notif-list" id="notif-list">
            <p class="notif-empty">No new notifications</p>
        </div>

    </div>

</div>
