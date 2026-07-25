<?php
/**
 * helpers/ValidationHelper.php
 * Centralised input validation rules for FoodHub.
 * All methods are static — no instantiation needed.
 */
class ValidationHelper {

    private static array $errors = [];

    /** Clear error store (call before a new validation run). */
    public static function reset(): void { self::$errors = []; }

    /** Return all collected errors. */
    public static function errors(): array { return self::$errors; }

    /** True if no errors since last reset(). */
    public static function passes(): bool { return empty(self::$errors); }

    /** Add an error message for a field. */
    private static function addError(string $field, string $msg): void {
        self::$errors[$field][] = $msg;
    }

    /* ── Individual Rule Methods ──────────────────────── */

    public static function required(string $field, mixed $value, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if ($value === null || trim((string)$value) === '') {
            self::addError($field, "$label is required.");
            return false;
        }
        return true;
    }

    public static function email(string $field, string $value): bool {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            self::addError($field, 'Please enter a valid email address.');
            return false;
        }
        return true;
    }

    public static function minLength(string $field, string $value, int $min, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if (strlen($value) < $min) {
            self::addError($field, "$label must be at least $min characters.");
            return false;
        }
        return true;
    }

    public static function maxLength(string $field, string $value, int $max, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if (strlen($value) > $max) {
            self::addError($field, "$label must not exceed $max characters.");
            return false;
        }
        return true;
    }

    public static function numeric(string $field, mixed $value, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if (!is_numeric($value)) {
            self::addError($field, "$label must be a number.");
            return false;
        }
        return true;
    }

    public static function min(string $field, mixed $value, float $min, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if ((float)$value < $min) {
            self::addError($field, "$label must be at least $min.");
            return false;
        }
        return true;
    }

    public static function max(string $field, mixed $value, float $max, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if ((float)$value > $max) {
            self::addError($field, "$label must not exceed $max.");
            return false;
        }
        return true;
    }

    public static function match(string $field, string $value, string $other, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if ($value !== $other) {
            self::addError($field, "$label does not match.");
            return false;
        }
        return true;
    }

    public static function inList(string $field, mixed $value, array $list, string $label = ''): bool {
        $label = $label ?: ucfirst($field);
        if (!in_array($value, $list, true)) {
            self::addError($field, "$label must be one of: " . implode(', ', $list) . '.');
            return false;
        }
        return true;
    }

    public static function phone(string $field, string $value): bool {
        if (!preg_match('/^\+?[\d\s\-().]{7,20}$/', $value)) {
            self::addError($field, 'Please enter a valid phone number.');
            return false;
        }
        return true;
    }

    public static function url(string $field, string $value): bool {
        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            self::addError($field, 'Please enter a valid URL.');
            return false;
        }
        return true;
    }

    /**
     * Validate a batch of fields at once.
     *
     * @param array $data    ['field' => value, ...]
     * @param array $rules   ['field' => 'required|email|min:8', ...]
     * @return bool
     */
    public static function validate(array $data, array $rules): bool {
        self::reset();
        foreach ($rules as $field => $ruleString) {
            $value = $data[$field] ?? '';
            foreach (explode('|', $ruleString) as $rule) {
                $parts  = explode(':', $rule, 2);
                $name   = trim($parts[0]);
                $param  = $parts[1] ?? null;
                switch ($name) {
                    case 'required':   self::required($field, $value); break;
                    case 'email':      self::email($field, (string)$value); break;
                    case 'min':        self::min($field, $value, (float)$param); break;
                    case 'max':        self::max($field, $value, (float)$param); break;
                    case 'minLength':  self::minLength($field, (string)$value, (int)$param); break;
                    case 'maxLength':  self::maxLength($field, (string)$value, (int)$param); break;
                    case 'numeric':    self::numeric($field, $value); break;
                    case 'phone':      self::phone($field, (string)$value); break;
                    case 'url':        self::url($field, (string)$value); break;
                }
            }
        }
        return self::passes();
    }

    /** Return first error for a field (for inline form feedback). */
    public static function firstError(string $field): string {
        return self::$errors[$field][0] ?? '';
    }
}
