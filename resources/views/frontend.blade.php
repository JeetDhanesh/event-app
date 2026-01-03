<!DOCTYPE html>
<html>
<head>
    <title>Event Viewer</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-white p-5">

<div class="container">
    <div class="d-flex justify-content-between mb-4">
        <h1>Events</h1>
        <button class="btn btn-primary" onclick="loadFrontendData()">Refresh Events</button>
    </div>

    <div class="accordion" id="eventsAccordion">
        
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseToday">
                    Today's Events
                </button>
            </h2>
            <div id="collapseToday" class="accordion-collapse collapse show" data-bs-parent="#eventsAccordion">
                <div class="accordion-body" id="todayList"></div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFuture">
                    Future Events
                </button>
            </h2>
            <div id="collapseFuture" class="accordion-collapse collapse" data-bs-parent="#eventsAccordion">
                <div class="accordion-body" id="futureList"></div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePast">
                    Past Events
                </button>
            </h2>
            <div id="collapsePast" class="accordion-collapse collapse" data-bs-parent="#eventsAccordion">
                <div class="accordion-body" id="pastList"></div>
            </div>
        </div>

    </div>
</div>

<script>
    $(document).ready(function() {
        loadFrontendData();
    });

    function loadFrontendData() {
        $.get('/fetch-events', function(data) {
            renderList('#todayList', data.today);
            renderList('#futureList', data.future);
            renderList('#pastList', data.past);
        });
    }

    function renderList(selector, events) {
        let html = '';
        if(events.length === 0) {
            html = '<p class="text-muted">No events in this category.</p>';
        } else {
            events.forEach(e => {
                html += `
                <div class="card mb-2 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">${e.title}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Date: ${e.date} | Time: ${e.time || 'N/A'}</h6>
                        <p class="card-text">${e.description}</p>
                        <small class="text-primary">Location: ${e.location || 'Online'}</small>
                    </div>
                </div>`;
            });
        }
        $(selector).html(html);
    }
</script>
</body>
</html>