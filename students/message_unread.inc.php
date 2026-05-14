<?php
/**
 * Badge thông báo Messages: bảng message_read_pointer (tự tạo nếu chưa có).
 * Cần $conn và (để đếm) $_SESSION['uname'].
 */
if (!isset($studentMessageUnreadCount)) {
    $studentMessageUnreadCount = 0;
}

if (!function_exists('student_ensure_message_read_pointer_table')) {
    function student_ensure_message_read_pointer_table($conn)
    {
        static $done = false;
        if ($done || !($conn instanceof mysqli)) {
            return;
        }
        $done = true;
        $sql = "CREATE TABLE IF NOT EXISTS `message_read_pointer` (
  `uname` varchar(100) NOT NULL,
  `last_read_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        try {
            mysqli_query($conn, $sql);
        } catch (Throwable $e) {
            // Không có quyền CREATE hoặc lỗi khác — bỏ qua, phần đếm sẽ trả 0
        }
    }
}

if (!function_exists('student_message_unread_compute')) {
    function student_message_unread_compute($conn)
    {
        $GLOBALS['studentMessageUnreadCount'] = 0;
        if (!isset($_SESSION['uname'], $conn) || !($conn instanceof mysqli)) {
            return;
        }
        student_ensure_message_read_pointer_table($conn);
        $u = mysqli_real_escape_string($conn, $_SESSION['uname']);
        try {
            $qPtr = mysqli_query($conn, "SELECT last_read_id FROM message_read_pointer WHERE uname='$u' LIMIT 1");
            if ($qPtr === false) {
                return;
            }
            $last = 0;
            if ($row = mysqli_fetch_assoc($qPtr)) {
                $last = (int) $row['last_read_id'];
            }
            $qCnt = mysqli_query($conn, "SELECT COUNT(*) AS c FROM message WHERE id > $last");
            if ($qCnt !== false && $row = mysqli_fetch_assoc($qCnt)) {
                $GLOBALS['studentMessageUnreadCount'] = (int) $row['c'];
            }
        } catch (Throwable $e) {
            $GLOBALS['studentMessageUnreadCount'] = 0;
        }
    }
}

if (!function_exists('student_message_badge_html')) {
    function student_message_badge_html()
    {
        $n = isset($GLOBALS['studentMessageUnreadCount']) ? (int) $GLOBALS['studentMessageUnreadCount'] : 0;
        if ($n < 1) {
            return '';
        }
        $show = $n > 99 ? '99+' : (string) $n;
        $title = 'Có thông báo từ giáo viên chưa đọc';
        return '<span class="msg-nav-badge" title="' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($show, ENT_QUOTES, 'UTF-8') . '</span>';
    }
}

// Trang messages.php đặt $GLOBALS['student_message_unread_defer']=true trước require_once để chạy compute sau khi đánh dấu đã đọc
if (empty($GLOBALS['__student_message_unread_auto_done'])) {
    $GLOBALS['__student_message_unread_auto_done'] = true;
    if (empty($GLOBALS['student_message_unread_defer'])) {
        student_message_unread_compute(isset($conn) ? $conn : null);
    }
}
