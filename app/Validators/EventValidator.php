<?php
// Перевіряє вхідні дані перед створенням або оновленням події, відповідає за валідацію
class EventValidator
{
    public function validate($data)
    {
        $errors = array();

        // Title
        $title = isset($data['title']) ? trim($data['title']) : '';
        if (strlen($title) < 1) {
            $errors['title'] = 'Title is required.';
        } elseif (strlen($title) > 200) {
            $errors['title'] = 'Title must not exceed 200 characters.';
        }

        // starts_at
        $startsAt = isset($data['starts_at']) ? $data['starts_at'] : '';
        if (empty($startsAt)) {
            $errors['starts_at'] = 'Start date is required.';
        } elseif (!$this->isValidDatetime($startsAt)) {
            $errors['starts_at'] = 'Start date has invalid format.';
        }

        // ends_at
        $endsAt = isset($data['ends_at']) ? $data['ends_at'] : '';
        if (empty($endsAt)) {
            $errors['ends_at'] = 'End date is required.';
        } elseif (!$this->isValidDatetime($endsAt)) {
            $errors['ends_at'] = 'End date has invalid format.';
        }

        // ends_at must be after starts_at
        if (empty($errors['starts_at']) && empty($errors['ends_at'])) {
            if (strtotime($endsAt) <= strtotime($startsAt)) {
                $errors['ends_at'] = 'End date must be after start date.';
            }
        }

        // color — optional, but if provided must be valid hex
        $color = isset($data['color']) ? $data['color'] : '';
        if (!empty($color) && !preg_match('/^#[0-9A-Fa-f]{6}$/', $color)) {
            $errors['color'] = 'Color must be a valid hex code (e.g. #5856d6).';
        }

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

    private function isValidDatetime($value)
    {
        $formats = array('Y-m-d\TH:i', 'Y-m-d H:i:s', 'Y-m-d');

        foreach ($formats as $format) {
            $dt = DateTime::createFromFormat($format, $value);
            if ($dt !== false) {
                return true;
            }
        }

        return false;
    }
}