<?php
require_once '../includes/config.example.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!is_admin_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

// Set content type to JSON
header('Content-Type: application/json');

// Handle AJAX requests
if ($_POST) {
    $action = $_POST['action'] ?? '';
    
    switch ($action) {
        case 'update_status':
            $id = (int)($_POST['id'] ?? 0);
            $type = sanitize_input($_POST['type'] ?? '');
            $status = sanitize_input($_POST['status'] ?? '');
            
            if ($id > 0 && $type === 'dispensation' && in_array($status, ['pending', 'approved', 'rejected'])) {
                try {
                    $stmt = $pdo->prepare("UPDATE dispensation SET status = ? WHERE id = ?");
                    $stmt->execute([$status, $id]);
                    
                    echo json_encode([
                        'success' => true,
                        'message' => 'Status berhasil diperbarui'
                    ]);
                } catch (PDOException $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Gagal memperbarui status'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Parameter tidak valid'
                ]);
            }
            break;
            
        case 'delete_record':
            $id = (int)($_POST['id'] ?? 0);
            $type = sanitize_input($_POST['type'] ?? '');
            
            if ($id > 0) {
                try {
                    if ($type === 'guest') {
                        $stmt = $pdo->prepare("DELETE FROM guest_book WHERE id = ?");
                        $stmt->execute([$id]);
                        $message = 'Data buku tamu berhasil dihapus';
                    } elseif ($type === 'dispensation') {
                        $stmt = $pdo->prepare("DELETE FROM dispensation WHERE id = ?");
                        $stmt->execute([$id]);
                        $message = 'Data dispensasi berhasil dihapus';
                    } else {
                        throw new Exception('Tipe data tidak valid');
                    }
                    
                    echo json_encode([
                        'success' => true,
                        'message' => $message
                    ]);
                } catch (Exception $e) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Gagal menghapus data'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'ID tidak valid'
                ]);
            }
            break;
            
        case 'get_stats':
            try {
                // Get current statistics
                $stats = [];
                
                // Guest book statistics
                $stmt = $pdo->query("SELECT COUNT(*) FROM guest_book");
                $stats['total_guests'] = $stmt->fetchColumn();
                
                $stmt = $pdo->query("SELECT COUNT(*) FROM guest_book WHERE DATE(created_at) = CURDATE()");
                $stats['guests_today'] = $stmt->fetchColumn();
                
                // Dispensation statistics
                $stmt = $pdo->query("SELECT COUNT(*) FROM dispensation");
                $stats['total_dispensations'] = $stmt->fetchColumn();
                
                $stmt = $pdo->query("SELECT COUNT(*) FROM dispensation WHERE DATE(created_at) = CURDATE()");
                $stats['dispensations_today'] = $stmt->fetchColumn();
                
                $stmt = $pdo->query("SELECT COUNT(*) FROM dispensation WHERE status = 'pending'");
                $stats['pending_dispensations'] = $stmt->fetchColumn();
                
                $stmt = $pdo->query("SELECT COUNT(*) FROM dispensation WHERE status = 'approved'");
                $stats['approved_dispensations'] = $stmt->fetchColumn();
                
                echo json_encode([
                    'success' => true,
                    'data' => $stats
                ]);
            } catch (PDOException $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengambil statistik'
                ]);
            }
            break;
            
        case 'export_data':
            $type = sanitize_input($_POST['type'] ?? '');
            $format = sanitize_input($_POST['format'] ?? 'csv');
            
            try {
                if ($type === 'guest') {
                    $stmt = $pdo->query("SELECT name, email, phone, institution, message, created_at FROM guest_book ORDER BY created_at DESC");
                    $data = $stmt->fetchAll();
                    $filename = 'buku_tamu_' . date('Y-m-d');
                } elseif ($type === 'dispensation') {
                    $stmt = $pdo->query("SELECT name, nim_nip, department, reason, start_date, end_date, status, created_at FROM dispensation ORDER BY created_at DESC");
                    $data = $stmt->fetchAll();
                    $filename = 'dispensasi_' . date('Y-m-d');
                } else {
                    throw new Exception('Tipe data tidak valid');
                }
                
                if ($format === 'csv') {
                    // Generate CSV content
                    $csv_content = '';
                    if (!empty($data)) {
                        // Header
                        $csv_content .= implode(',', array_keys($data[0])) . "\n";
                        
                        // Data rows
                        foreach ($data as $row) {
                            $csv_content .= implode(',', array_map(function($field) {
                                return '"' . str_replace('"', '""', $field) . '"';
                            }, $row)) . "\n";
                        }
                    }
                    
                    echo json_encode([
                        'success' => true,
                        'data' => base64_encode($csv_content),
                        'filename' => $filename . '.csv',
                        'mime_type' => 'text/csv'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Format tidak didukung'
                    ]);
                }
            } catch (Exception $e) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Gagal mengekspor data'
                ]);
            }
            break;
            
        default:
            echo json_encode([
                'success' => false,
                'message' => 'Aksi tidak dikenali'
            ]);
            break;
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak didukung'
    ]);
}
?>
