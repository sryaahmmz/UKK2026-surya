<?php
/**
 * Helper functions for the application
 */

function format_date($date) {
    return date('d/m/Y H:i', strtotime($date));
}

function get_status_badge($status) {
    $badge = 'bg-secondary';
    if ($status == 'Proses') $badge = 'bg-warning text-dark';
    if ($status == 'Selesai') $badge = 'bg-success';
    return '<span class="badge ' . $badge . '">' . $status . '</span>';
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function check_login($role = null) {
    if (!isset($_SESSION['login'])) {
        header("Location: ../index.php");
        exit;
    }
    if ($role && $_SESSION['role'] !== $role) {
        header("Location: ../index.php");
        exit;
    }   
}
?>