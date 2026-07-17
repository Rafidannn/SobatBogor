<?php
/**
 * middleware/AdminMiddleware.php
 * Ensures logged-in user has 'admin' role. Returns 403 if not.
 * TODO: Implement in Tugas 2
 */
class AdminMiddleware {
    public static function handle(): void {
        // Check role === 'admin', abort with 403 if unauthorized
    }
}
