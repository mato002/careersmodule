<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug Error - {{ $error['error'] ?? 'Error' }}</title>
    <style>
        body {
            font-family: monospace;
            padding: 20px;
            background: #1e1e1e;
            color: #d4d4d4;
        }
        .error-container {
            max-width: 1200px;
            margin: 0 auto;
            background: #252526;
            border: 1px solid #3e3e42;
            border-radius: 5px;
            padding: 20px;
        }
        h1 {
            color: #f48771;
            border-bottom: 2px solid #3e3e42;
            padding-bottom: 10px;
        }
        h2 {
            color: #4ec9b0;
            margin-top: 20px;
        }
        .detail-box {
            background: #1e1e1e;
            border-left: 3px solid #4ec9b0;
            padding: 15px;
            margin: 10px 0;
            border-radius: 3px;
        }
        .slug-list {
            list-style: none;
            padding: 0;
        }
        .slug-list li {
            padding: 5px;
            margin: 3px 0;
            background: #2d2d30;
            border-radius: 3px;
        }
        .jobs-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .jobs-table th, .jobs-table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #3e3e42;
        }
        .jobs-table th {
            background: #2d2d30;
            color: #4ec9b0;
        }
        .trace {
            background: #1e1e1e;
            padding: 15px;
            border-radius: 3px;
            overflow-x: auto;
            white-space: pre-wrap;
            font-size: 12px;
            max-height: 400px;
            overflow-y: auto;
        }
        .highlight {
            color: #f48771;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>{{ $error['error'] ?? 'Error' }}</h1>
        
        <div class="detail-box">
            <strong>Message:</strong> {{ $error['message'] ?? 'No message' }}
        </div>

        <h2>Request Details</h2>
        <div class="detail-box">
            <strong>Slug Requested:</strong> <span class="highlight">{{ $error['slug_requested'] ?? 'N/A' }}</span>
        </div>

        <h2>Available Slugs</h2>
        <div class="detail-box">
            @if(isset($error['available_slugs']) && count($error['available_slugs']) > 0)
                <ul class="slug-list">
                    @foreach($error['available_slugs'] as $slug)
                        <li>{{ $slug }}</li>
                    @endforeach
                </ul>
            @else
                <p>No jobs found in database!</p>
            @endif
        </div>

        <h2>All Jobs in Database</h2>
        <div class="detail-box">
            @if(isset($error['all_jobs']) && count($error['all_jobs']) > 0)
                <table class="jobs-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($error['all_jobs'] as $job)
                            <tr>
                                <td>{{ $job['id'] }}</td>
                                <td>{{ $job['title'] }}</td>
                                <td><span class="highlight">{{ $job['slug'] }}</span></td>
                                <td>{{ $job['is_active'] ? 'Yes' : 'No' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p>No jobs found in database!</p>
            @endif
        </div>

        <h2>Exception Details</h2>
        <div class="detail-box">
            <p><strong>File:</strong> {{ $error['exception_file'] ?? 'N/A' }}</p>
            <p><strong>Line:</strong> {{ $error['exception_line'] ?? 'N/A' }}</p>
        </div>

        <h2>Stack Trace</h2>
        <div class="trace">{{ $error['trace'] ?? 'No trace available' }}</div>
    </div>
</body>
</html>




