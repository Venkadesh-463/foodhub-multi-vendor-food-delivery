<?php
/**
 * helpers/UploadHelper.php
 * Handles safe file uploads (images & documents) for FoodHub.
 * All methods are static.
 */
class UploadHelper {

    /** Allowed MIME types for image uploads */
    private static array $allowedImageTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

    /** Allowed MIME types for documents */
    private static array $allowedDocTypes = [
        'application/pdf', 'image/jpeg', 'image/png',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    /** Max file size: 5 MB */
    private static int $maxSize = 5 * 1024 * 1024;

    /**
     * Upload an image to a specified subdirectory.
     *
     * @param array  $file      Entry from $_FILES
     * @param string $subDir    Subdirectory inside uploads/ (e.g. 'food', 'restaurant')
     * @param string $prefix    Optional filename prefix
     * @return array            ['success' => bool, 'filename' => string|null, 'message' => string]
     */
    public static function uploadImage(array $file, string $subDir, string $prefix = ''): array {
        return self::upload($file, $subDir, $prefix, self::$allowedImageTypes);
    }

    /**
     * Upload a document (PDF, DOCX, image).
     */
    public static function uploadDocument(array $file, string $subDir, string $prefix = ''): array {
        return self::upload($file, $subDir, $prefix, self::$allowedDocTypes);
    }

    /**
     * Core upload logic.
     */
    private static function upload(array $file, string $subDir, string $prefix, array $allowedTypes): array {
        if (!isset($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'filename' => null, 'message' => self::uploadError($file['error'] ?? -1)];
        }

        // Size check
        if ($file['size'] > self::$maxSize) {
            return ['success' => false, 'filename' => null, 'message' => 'File size exceeds 5 MB limit.'];
        }

        // MIME type check (use finfo, not $_FILES['type'] which can be spoofed)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        if (!in_array($mime, $allowedTypes, true)) {
            return ['success' => false, 'filename' => null, 'message' => 'Invalid file type: ' . htmlspecialchars($mime)];
        }

        // Build safe filename
        $ext      = self::mimeToExt($mime);
        $filename = ($prefix ? $prefix . '_' : '') . uniqid('', true) . '.' . $ext;

        // Ensure directory exists
        $uploadDir = rtrim(ROOT_PATH ?? dirname(__DIR__), '/') . '/uploads/' . trim($subDir, '/') . '/';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true)) {
            return ['success' => false, 'filename' => null, 'message' => 'Could not create upload directory.'];
        }

        $destination = $uploadDir . $filename;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'filename' => null, 'message' => 'Failed to move uploaded file.'];
        }

        return ['success' => true, 'filename' => $filename, 'message' => 'File uploaded successfully.'];
    }

    /**
     * Delete an uploaded file by filename and subdirectory.
     */
    public static function delete(string $filename, string $subDir): bool {
        if (empty($filename)) return false;
        $path = rtrim(ROOT_PATH ?? dirname(__DIR__), '/') . '/uploads/' . trim($subDir, '/') . '/' . basename($filename);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    /* ── Private Helpers ──────────────────────────────── */

    private static function mimeToExt(string $mime): string {
        $map = [
            'image/jpeg'       => 'jpg',
            'image/png'        => 'png',
            'image/webp'       => 'webp',
            'image/gif'        => 'gif',
            'application/pdf'  => 'pdf',
            'application/msword' => 'doc',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => 'docx',
        ];
        return $map[$mime] ?? 'bin';
    }

    private static function uploadError(int $code): string {
        $messages = [
            UPLOAD_ERR_INI_SIZE   => 'File exceeds server upload limit.',
            UPLOAD_ERR_FORM_SIZE  => 'File exceeds form upload limit.',
            UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
            UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION  => 'A PHP extension stopped the upload.',
        ];
        return $messages[$code] ?? 'Unknown upload error.';
    }
}
