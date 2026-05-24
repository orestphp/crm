<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Управління Тікетами Клієнта</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .stats-card { transition: transform 0.2s; }
        .stats-card:hover { transform: translateY(-3px); }
        .spinner-wrapper { display: none; }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5 border-bottom pb-3">
        <div>
            <h1 class="fw-bold text-dark">Customer Service</h1>
        </div>
        <button id="btn-create-ticket" class="btn btn-primary btn-lg shadow-sm">
            <i class="fa-solid fa-plus me-2"></i> Create new ticket
        </button>
    </div>

    <!-- Statistics -->
    <div id="statistics-panel" class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm bg-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small font-weight-bold">Total tickets</h6>
                        <h3 id="stat-total" class="fw-bold mb-0 text-dark">-</h3>
                    </div>
                    <div class="p-3 bg-primary bg-opacity-10 rounded text-primary"><i class="fa-solid fa-list-check fa-xl"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm bg-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small font-weight-bold">New</h6>
                        <h3 id="stat-new" class="fw-bold mb-0 text-danger">-</h3>
                    </div>
                    <div class="p-3 bg-danger bg-opacity-10 rounded text-danger"><i class="fa-solid fa-circle-exclamation fa-xl"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm bg-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small font-weight-bold">In process</h6>
                        <h3 id="stat-process" class="fw-bold mb-0 text-warning">-</h3>
                    </div>
                    <div class="p-3 bg-warning bg-opacity-10 rounded text-warning"><i class="fa-solid fa-spinner fa-xl animate-spin"></i></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stats-card border-0 shadow-sm bg-white h-100">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h6 class="text-muted text-uppercase small font-weight-bold">Processed</h6>
                        <h3 id="stat-processed" class="fw-bold mb-0 text-success">-</h3>
                    </div>
                    <div class="p-3 bg-success bg-opacity-10 rounded text-success"><i class="fa-solid fa-circle-check fa-xl"></i></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert container -->
    <div id="alert-container"></div>

    <!-- List tickets -->
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="card-title mb-0 fw-bold text-secondary">Processed</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tickets-table">
                    <thead class="table-light text-uppercase fs-7">
                    <tr>
                        <th class="ps-4" style="width: 80px;">ID</th>
                        <th>Description</th>
                        <th style="width: 150px;">Status</th>
                        <th style="width: 180px;">Date</th>
                        <th class="text-end pe-4" style="width: 120px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody id="tickets-list">
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="spinner-border text-primary spinner-border-sm me-2" role="status"></div>
                            Loading tickets ...
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create/Edit) -->
<div class="modal fade" id="ticketModal" data-bs-backdrop="static" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="ticketModalLabel">Create New Ticket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="ticket-form" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <!-- ticket_id -->
                    <input type="hidden" id="ticket-id" name="ticket_id">

                    <!-- text/description -->
                    <div class="mb-4">
                        <label for="text" class="form-label fw-semibold">Опис проблеми чи запиту <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="text" name="text" rows="5" placeholder="Детально опишіть суть вашої проблеми..." required></textarea>
                        <div class="invalid-feedback" id="error-text">Будь ласка, заповніть це поле.</div>
                    </div>

                    <!-- ticket status (edit) -->
                    <div class="mb-4 d-none" id="status-group">
                        <label for="status" class="form-label fw-semibold">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="new">New</option>
                            <option value="in process">In Process</option>
                            <option value="processed">Processed</option>
                        </select>
                    </div>

                    <!-- Attach Files -->
                    <div class="mb-2">
                        <label for="attachments" class="form-label fw-semibold">Attache files / Screenshots</label>
                        <div class="input-group">
                            <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                            <label class="input-group-text" for="attachments"><i class="fa-solid fa-cloud-arrow-up"></i></label>
                        </div>
                        <div class="form-text text-muted mt-2">You can choose multiple files</div>
                    </div>
                </div>
                <div class="modal-footer bg-light px-4 py-3">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" id="btn-submit" class="btn btn-primary px-4">
                        <span class="spinner-border spinner-border-sm me-2 spinner-wrapper" id="submit-spinner" role="status"></span>
                        <span id="submit-btn-text">Save Ticket</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function () {
        const API_BASE_URL = "http://127.0.0.1:8080/api/tickets/";
        const ticketModal = new bootstrap.Modal(document.getElementById('ticketModal'));

        // Initialize data
        loadTickets();
        loadStatistics();

        // GET tickets
        function loadTickets() {
            $.ajax({
                url: API_BASE_URL,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    let html = '';
                    let tickets = response.data ? response.data : response;

                    if (tickets.length === 0) {
                        html = `<tr><td colspan="4" class="text-center py-5 text-muted">You have no tickets.</td></tr>`;
                    } else {
                        tickets.forEach(ticket => {
                            let statusBadge = '';
                            if (ticket.status === 'new') statusBadge = '<span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">New</span>';
                            else if (ticket.status === 'in process') statusBadge = '<span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3">In Process</span>';
                            else statusBadge = '<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">Processed</span>';

                            let date = new Date(ticket.created_at).toLocaleString('uk-UA', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute:'2-digit' });

                            html += `
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#${ticket.id}</td>
                                    <td class="text-wrap text-break">${escapeHtml(ticket.text)}</td>
                                    <td>${statusBadge}</td>
                                    <td class="text-muted small pe-4">${date}</td>
                                </tr>
                            `;
                        });
                    }
                    $('#tickets-list').html(html);
                },
                error: function () {
                    showAlert('Could not reload tickets, check API.', 'danger');
                    $('#tickets-list').html(`<tr><td colspan="4" class="text-center py-5 text-danger fw-semibold">Error connecting API.</td></tr>`);
                }
            });
        }

        // GET statistics
        function loadStatistics() {
            $.ajax({
                url: `${API_BASE_URL}statistics`,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#stat-total').text(data.total ?? 0);
                    $('#stat-new').text(data.new ?? 0);
                    $('#stat-process').text(data.in_process ?? data['in process'] ?? 0);
                    $('#stat-processed').text(data.processed ?? 0);
                },
                error: function () {
                    console.error("Couldn't load statistics from API.");
                }
            });
        }

        // Reset form
        $('#btn-create-ticket').on('click', function () {
            $('#ticket-form')[0].reset();
            ticketModal.show();
        });

        // POST / PUT: Submit form (Multipart FormData for files)
        $('#ticket-form').on('submit', function (e) {
            e.preventDefault();

            const id = $('#ticket-id').val();
            const isEdit = id !== '';

            let formData = new FormData(this);

            // Laravel Imitation of PUT/PATCH via FormData
            if (isEdit) {
                formData.append('_method', 'PUT');
            }

            // URL config
            let ajaxUrl = isEdit ? `${API_BASE_URL}${id}` : API_BASE_URL;

            // Freeze UI while loading
            $('#btn-submit').prop('disabled', true);
            $('#submit-spinner').removeClass('spinner-wrapper');

            $.ajax({
                url: API_BASE_URL,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    ticketModal.hide();
                    showAlert('Ticket successfully created!', 'success');

                    // Reload data
                    loadTickets();
                    loadStatistics();
                },
                error: function (xhr) {
                    let errorMsg = 'Could`t send data.';
                    if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }
                    showAlert(errorMsg, 'danger');
                },
                complete: function () {
                    // Completed
                    $('#btn-submit').prop('disabled', false);
                    $('#submit-spinner').addClass('spinner-wrapper');
                }
            });
        });

        // Bootstrap show alert
        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fa-solid ${type === 'success' ? 'fa-square-check' : 'fa-triangle-exclamation'} me-2 fs-5"></i>
                        <div>${message}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('#alert-container').html(alertHtml);

            // close after 5 sec
            setTimeout(() => {
                $('.alert').alert('close');
            }, 5000);
        }

        // XSS text protection
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
    });
</script>
</body>
</html>
