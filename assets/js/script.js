// Simple JavaScript for Government Guest Book & Dispensation System

document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(function(alert) {
        setTimeout(function() {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });

    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Loading button state - Fixed to prevent infinite loading
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            const form = this.closest('form');
            if (form && form.checkValidity()) {
                // Only show loading if form is actually being submitted
                setTimeout(() => {
                    if (!form.querySelector('.alert')) { // No error alerts
                        this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
                        this.disabled = true;
                    }
                }, 100);
            }
        });
    });

    // Date validation for dispensation form
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    if (startDateInput && endDateInput) {
        // Set minimum date to today
        const today = new Date().toISOString().split('T')[0];
        startDateInput.setAttribute('min', today);
        endDateInput.setAttribute('min', today);
        
        startDateInput.addEventListener('change', function() {
            endDateInput.setAttribute('min', this.value);
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        });
        
        endDateInput.addEventListener('change', function() {
            if (this.value < startDateInput.value) {
                this.value = startDateInput.value;
                showAlert('Tanggal selesai tidak boleh lebih awal dari tanggal mulai', 'warning');
            }
        });
    }

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            // Remove non-numeric characters except + and -
            this.value = this.value.replace(/[^\d+\-\s]/g, '');
        });
    });

    // Character counter for textarea
    const textareas = document.querySelectorAll('textarea[maxlength]');
    textareas.forEach(function(textarea) {
        const maxLength = textarea.getAttribute('maxlength');
        const counter = document.createElement('small');
        counter.className = 'text-muted float-end';
        counter.innerHTML = `0/${maxLength}`;
        textarea.parentNode.appendChild(counter);
        
        textarea.addEventListener('input', function() {
            const currentLength = this.value.length;
            counter.innerHTML = `${currentLength}/${maxLength}`;
            
            if (currentLength > maxLength * 0.9) {
                counter.className = 'text-warning float-end';
            } else {
                counter.className = 'text-muted float-end';
            }
        });
    });

    // Admin panel functions
    if (document.querySelector('.admin-panel')) {
        initAdminPanel();
    }
});

// Admin panel initialization
function initAdminPanel() {
    // Status update functionality
    const statusSelects = document.querySelectorAll('.status-select');
    statusSelects.forEach(function(select) {
        select.addEventListener('change', function() {
            const id = this.dataset.id;
            const type = this.dataset.type;
            const status = this.value;
            
            updateStatus(id, type, status);
        });
    });
    
    // Delete confirmation
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const name = this.dataset.name;
            if (confirm(`Apakah Anda yakin ingin menghapus data "${name}"?`)) {
                window.location.href = this.href;
            }
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr');
            
            tableRows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
}

// Update status via AJAX
function updateStatus(id, type, status) {
    const formData = new FormData();
    formData.append('id', id);
    formData.append('type', type);
    formData.append('status', status);
    formData.append('action', 'update_status');
    
    fetch('admin/ajax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Status berhasil diperbarui', 'success');
        } else {
            showAlert('Gagal memperbarui status', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'danger');
    });
}

// Show alert message
function showAlert(message, type = 'info') {
    const alertContainer = document.getElementById('alertContainer') || document.body;
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.insertBefore(alertDiv, alertContainer.firstChild);
    
    // Auto-hide after 3 seconds
    setTimeout(function() {
        const bsAlert = new bootstrap.Alert(alertDiv);
        bsAlert.close();
    }, 3000);
}

// Print functionality
function printTable() {
    window.print();
}

// Export to CSV (simple implementation)
function exportToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(function(row) {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(function(col) {
            rowData.push('"' + col.textContent.replace(/"/g, '""') + '"');
        });
        csv.push(rowData.join(','));
    });
    
    const csvContent = csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename + '.csv';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Export to Excel (XLSX format)
function exportToExcel(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    // Create workbook and worksheet
    const wb = XLSX.utils.book_new();
    
    // Convert table to worksheet
    const ws = XLSX.utils.table_to_sheet(table);
    
    // Add worksheet to workbook
    XLSX.utils.book_append_sheet(wb, ws, 'Data');
    
    // Save file
    XLSX.writeFile(wb, filename + '.xlsx');
}

// Fallback Excel export using HTML table method
function exportToExcelFallback(tableId, filename) {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    // Clone table to avoid modifying original
    const clonedTable = table.cloneNode(true);
    
    // Remove action columns (buttons)
    const actionHeaders = clonedTable.querySelectorAll('th:last-child');
    const actionCells = clonedTable.querySelectorAll('td:last-child');
    
    actionHeaders.forEach(header => header.remove());
    actionCells.forEach(cell => cell.remove());
    
    // Create Excel content
    let excelContent = `
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                table { border-collapse: collapse; width: 100%; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
            </style>
        </head>
        <body>
            ${clonedTable.outerHTML}
        </body>
        </html>
    `;
    
    // Create blob and download
    const blob = new Blob([excelContent], { 
        type: 'application/vnd.ms-excel;charset=utf-8;' 
    });
    
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename + '.xls';
    a.click();
    window.URL.revokeObjectURL(url);
}

// Main export function that tries XLSX library first, then fallback
function exportToExcel(tableId, filename) {
    if (typeof XLSX !== 'undefined') {
        // Use XLSX library if available
        const table = document.getElementById(tableId);
        if (!table) return;
        
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.table_to_sheet(table);
        XLSX.utils.book_append_sheet(wb, ws, 'Data');
        XLSX.writeFile(wb, filename + '.xlsx');
    } else {
        // Use fallback method
        exportToExcelFallback(tableId, filename);
    }
}

// Form reset with confirmation
function resetForm(formId) {
    if (confirm('Apakah Anda yakin ingin mengosongkan form?')) {
        document.getElementById(formId).reset();
        document.getElementById(formId).classList.remove('was-validated');
    }
}

// Auto-save draft (localStorage)
function enableAutoSave(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    const inputs = form.querySelectorAll('input, textarea, select');
    const storageKey = 'draft_' + formId;
    
    // Load saved data
    const savedData = localStorage.getItem(storageKey);
    if (savedData) {
        const data = JSON.parse(savedData);
        inputs.forEach(function(input) {
            if (data[input.name]) {
                input.value = data[input.name];
            }
        });
    }
    
    // Save on input
    inputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const formData = {};
            inputs.forEach(function(inp) {
                formData[inp.name] = inp.value;
            });
            localStorage.setItem(storageKey, JSON.stringify(formData));
        });
    });
    
    // Clear on submit
    form.addEventListener('submit', function() {
        localStorage.removeItem(storageKey);
    });
}

// Camera functionality for dispensation form
let camera = null;
let canvas = null;
let context = null;
let stream = null;

function initCamera() {
    camera = document.getElementById('camera');
    canvas = document.getElementById('canvas');
    context = canvas.getContext('2d');
    
    const startCameraBtn = document.getElementById('startCamera');
    const capturePhotoBtn = document.getElementById('capturePhoto');
    const retakePhotoBtn = document.getElementById('retakePhoto');
    const cameraContainer = document.getElementById('cameraContainer');
    const photoPreview = document.getElementById('photoPreview');
    const capturedPhoto = document.getElementById('capturedPhoto');
    const proofPhotoInput = document.getElementById('proof_photo');
    
    if (!startCameraBtn) return; // Not on dispensation page
    
    // Start camera
    startCameraBtn.addEventListener('click', async function() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { 
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    facingMode: 'user' // Front camera
                } 
            });
            
            camera.srcObject = stream;
            cameraContainer.style.display = 'block';
            photoPreview.style.display = 'none';
            
            startCameraBtn.style.display = 'none';
            capturePhotoBtn.style.display = 'inline-block';
            retakePhotoBtn.style.display = 'none';
            
        } catch (error) {
            console.error('Error accessing camera:', error);
            alert('Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera.');
        }
    });
    
    // Capture photo
    capturePhotoBtn.addEventListener('click', function() {
        if (!stream) return;
        
        // Set canvas size to match video
        canvas.width = camera.videoWidth;
        canvas.height = camera.videoHeight;
        
        // Draw video frame to canvas
        context.drawImage(camera, 0, 0, canvas.width, canvas.height);
        
        // Convert to base64
        const photoData = canvas.toDataURL('image/jpeg', 0.8);
        
        // Display captured photo
        capturedPhoto.src = photoData;
        photoPreview.style.display = 'block';
        cameraContainer.style.display = 'none';
        
        // Store photo data
        proofPhotoInput.value = photoData;
        
        // Update buttons
        capturePhotoBtn.style.display = 'none';
        retakePhotoBtn.style.display = 'inline-block';
        
        // Stop camera stream
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
            stream = null;
        }
        
        // Remove required validation error if photo is taken
        proofPhotoInput.setCustomValidity('');
    });
    
    // Retake photo
    retakePhotoBtn.addEventListener('click', function() {
        photoPreview.style.display = 'none';
        proofPhotoInput.value = '';
        
        startCameraBtn.style.display = 'inline-block';
        retakePhotoBtn.style.display = 'none';
        
        // Set validation error back
        proofPhotoInput.setCustomValidity('Foto bukti sakit wajib diambil');
    });
    
    // Form validation for photo - Made optional
    const dispensationForm = document.getElementById('dispensationForm');
    if (dispensationForm) {
        dispensationForm.addEventListener('submit', function(e) {
            // Photo is now optional, no validation needed
            if (proofPhotoInput) {
                proofPhotoInput.setCustomValidity('');
            }
        });
    }
}

// Initialize auto-save for forms
document.addEventListener('DOMContentLoaded', function() {
    enableAutoSave('guestBookForm');
    enableAutoSave('dispensationForm');
    
    // Initialize camera functionality
    initCamera();
});
