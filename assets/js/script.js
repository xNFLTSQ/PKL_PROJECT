// Simple JavaScript for Government Guest Book & Dispensation System

document.addEventListener('DOMContentLoaded', function() {
    
    // Bootstrap tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // Form validation
    document.querySelectorAll('.needs-validation').forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Auto-hide alerts
    document.querySelectorAll('.alert:not(.alert-permanent)').forEach(alert => {
        setTimeout(() => new bootstrap.Alert(alert).close(), 5000);
    });

    // Submit button loading
    document.querySelectorAll('button[type="submit"]').forEach(button => {
        button.addEventListener('click', function() {
            if (this.closest('form').checkValidity()) {
                setTimeout(() => {
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                    this.disabled = true;
                }, 100);
            }
        });
    });

    // Date validation
    const startDate = document.getElementById('start_date');
    const endDate = document.getElementById('end_date');
    if (startDate && endDate) {
        const today = new Date().toISOString().split('T')[0];
        startDate.min = endDate.min = today;
        startDate.addEventListener('change', () => {
            endDate.min = startDate.value;
            if (endDate.value < startDate.value) endDate.value = startDate.value;
        });
    }

    // Admin panel
    if (document.querySelector('.admin-panel')) {
        // Status update
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                updateStatus(this.dataset.id, this.dataset.type, this.value);
            });
        });
        
        // Delete confirmation
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm(`Hapus data "${this.dataset.name}"?`)) {
                    window.location.href = this.href;
                }
            });
        });
        
        // Search
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const term = this.value.toLowerCase();
                document.querySelectorAll('tbody tr').forEach(row => {
                    row.style.display = row.textContent.toLowerCase().includes(term) ? '' : 'none';
                });
            });
        }
    }
});

// AJAX status update
function updateStatus(id, type, status) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('type', type);
    formData.append('status', status);
    formData.append('action', 'update_status');
    
    fetch('admin/ajax.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => showAlert(data.success ? 'Status berhasil diperbarui' : 'Gagal memperbarui status', data.success ? 'success' : 'danger'))
        .catch(() => showAlert('Terjadi kesalahan', 'danger'));
}

// Show alert
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    
    (document.getElementById('alertContainer') || document.body).insertBefore(alertDiv, document.body.firstChild);
    setTimeout(() => new bootstrap.Alert(alertDiv).close(), 3000);
}

// Utility functions
function printTable() { window.print(); }
function resetForm(formId) {
    if (confirm('Kosongkan form?')) {
        const form = document.getElementById(formId);
        form.reset();
        form.classList.remove('was-validated');
    }
}
