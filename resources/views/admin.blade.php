<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-light p-4">

<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h2>Admin Panel</h2>
        <a href="/" class="btn btn-outline-primary" target="_blank">Go to Frontend View</a>
    </div>

    <div id="alert-box" class="alert d-none"></div>

    <div class="row">
        <div class="col-md-4">
            <div class="card p-3 shadow-sm">
                <h5 id="formTitle">Add Event</h5>
                <form id="eventForm" novalidate> <input type="hidden" id="event_id">
                    
                    <div class="mb-2">
                        <label>Title</label>
                        <input type="text" id="title" class="form-control">
                        <small class="text-danger error-msg" id="error-title"></small>
                    </div>

                    <div class="mb-2">
                        <label>Description</label>
                        <textarea id="description" class="form-control"></textarea>
                        <small class="text-danger error-msg" id="error-description"></small>
                    </div>

                    <div class="mb-2">
                        <label>Date</label>
                        <input type="date" id="date" class="form-control">
                        <small class="text-danger error-msg" id="error-date"></small>
                    </div>

                    <div class="mb-2">
                        <label>Time</label>
                        <input type="time" id="time" class="form-control">
                        <small class="text-danger error-msg" id="error-time"></small>
                    </div>

                    <div class="mb-2">
                        <label>Location</label>
                        <input type="text" id="location" class="form-control">
                        <small class="text-danger error-msg" id="error-location"></small>
                    </div>

                    <button type="submit" class="btn btn-success w-100" id="saveBtn">Save</button>
                    <button type="button" class="btn btn-secondary w-100 mt-2" id="cancelBtn" style="display:none;">Cancel</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card p-3 shadow-sm">
                <h5>All Events</h5>
                <table class="table table-bordered table-hover">
                    <thead class="table-light"><tr><th>Title</th><th>Date</th><th>Action</th></tr></thead>
                    <tbody id="adminTable"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        loadAdminData();

        $('#eventForm').submit(function(e) {
            e.preventDefault();
            
            $('.error-msg').text('');
            $('#alert-box').addClass('d-none').removeClass('alert-success alert-danger');

            let isValid = true;
            let title = $('#title').val().trim();
            let description = $('#description').val().trim();
            let date = $('#date').val();
            let time = $('#time').val();
            let location = $('#location').val();

            if(title === '') {
                $('#error-title').text('Title is required.');
                isValid = false;
            }
            if(description === '') {
                $('#error-description').text('Description is required.');
                isValid = false;
            }
            if(date === '') {
                $('#error-date').text('Date is required.');
                isValid = false;
            }
            if(time === '') {
                $('#error-time').text('Time is required.');
                isValid = false;
            }
            if(location === '') {
                $('#error-location').text('Location is required.');
                isValid = false;
            }

            if(!isValid) return;

            
            let id = $('#event_id').val();
            let url = id ? `/events/${id}/update` : '/events';
            
            let data = {
                title: title,
                description: description,
                date: date,
                time: $('#time').val(),
                location: $('#location').val()
            };

            $.ajax({
                url: url,
                type: 'POST',
                data: data,
                success: function(response) {
                    showAlert('success', response.message);
                    resetForm();
                    loadAdminData();
                },
                error: function(xhr) {
                    if(xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        if(errors.title) $('#error-title').text(errors.title[0]);
                        if(errors.description) $('#error-description').text(errors.description[0]);
                        if(errors.date) $('#error-date').text(errors.date[0]);
                        showAlert('danger', 'Please fix the errors below.');
                    } else {
                        showAlert('danger', 'Something went wrong!');
                    }
                }
            });
        });

        $(document).on('click', '.editBtn', function() {
            $('.error-msg').text(''); 
            $('#alert-box').addClass('d-none');

            $.get(`/events/${$(this).data('id')}/edit`, function(data) {
                $('#event_id').val(data.id);
                $('#title').val(data.title);
                $('#description').val(data.description);
                $('#date').val(data.date);
                $('#time').val(data.time);
                $('#location').val(data.location);
                $('#saveBtn').text('Update');
                $('#formTitle').text('Edit Event');
                $('#cancelBtn').show();
            });
        });

        
        $(document).on('click', '.deleteBtn', function() {
            if(confirm('Are you sure you want to delete this event?')) {
                $.post(`/events/${$(this).data('id')}/delete`, function(response) {
                    showAlert('warning', response.message); 
                    loadAdminData();
                });
            }
        });

        $('#cancelBtn').click(function() { 
            resetForm(); 
            $('.error-msg').text('');
            $('#alert-box').addClass('d-none');
        });
    });

    
    function resetForm() {
        $('#eventForm')[0].reset();
        $('#event_id').val('');
        $('#saveBtn').text('Save');
        $('#formTitle').text('Add Event');
        $('#cancelBtn').hide();
    }

    
    function showAlert(type, message) {
        let alertClass = (type === 'success') ? 'alert-success' : (type === 'warning' ? 'alert-warning' : 'alert-danger');
        $('#alert-box').removeClass('d-none alert-success alert-danger alert-warning').addClass(alertClass).text(message);
        
        setTimeout(() => { $('#alert-box').addClass('d-none'); }, 3000);
    }

    function loadAdminData() {
        $.get('/fetch-events', function(data) {
            let html = '';
            if(data.all.length === 0) {
                 html = '<tr><td colspan="3" class="text-center">No events found</td></tr>';
            } else {
                data.all.forEach(e => {
                    html += `<tr>
                        <td>${e.title}</td>
                        <td>${e.date}</td>
                        <td>
                            <button class="btn btn-sm btn-info editBtn text-white" data-id="${e.id}">Edit</button>
                            <button class="btn btn-sm btn-danger deleteBtn" data-id="${e.id}">Delete</button>
                        </td>
                    </tr>`;
                });
            }
            $('#adminTable').html(html);
        });
    }
</script>
</body>
</html>