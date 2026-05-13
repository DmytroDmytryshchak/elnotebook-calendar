CREATE DATABASE IF NOT EXISTS notebook_calendar
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE notebook_calendar;

-- Users
CREATE TABLE IF NOT EXISTS users (
                                     id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                     name       VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    INDEX idx_email (email)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Events
--  color      — hex-колір події (#5856d6), відображається у сітці календаря
--  starts_at  — початок події
--  ends_at    — кінець події (може бути того самого дня або через декілька днів)
--  all_day    — якщо час ігнорується, подія займає весь день
CREATE TABLE IF NOT EXISTS events (
                                      id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                      user_id     INT UNSIGNED NOT NULL,
                                      title       VARCHAR(200) NOT NULL,
    description TEXT,
    color       VARCHAR(7)   NOT NULL DEFAULT '#5856d6',
    starts_at   DATETIME     NOT NULL,
    ends_at     DATETIME     NOT NULL,
    all_day     TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_events_user
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

    INDEX idx_user_starts (user_id, starts_at),
    INDEX idx_user_ends   (user_id, ends_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Notifications
--  trigger_at — момент коли треба показати сповіщення
--  seen       — 0 = не переглянуто, 1 = переглянуто
CREATE TABLE IF NOT EXISTS notifications (
                                             id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
                                             user_id    INT UNSIGNED NOT NULL,
                                             event_id   INT UNSIGNED NOT NULL,
                                             trigger_at DATETIME     NOT NULL,
                                             seen       TINYINT(1)   NOT NULL DEFAULT 0,
    created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_notifications_user
    FOREIGN KEY (user_id)  REFERENCES users(id)  ON DELETE CASCADE,
    CONSTRAINT fk_notifications_event
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE,

    -- Індекс для polling-запиту
    INDEX idx_pending (user_id, seen, trigger_at)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
