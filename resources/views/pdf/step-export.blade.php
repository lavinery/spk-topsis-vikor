<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Step Export</title>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            margin: 20px;
        }
        h3 { 
            color: #374151; 
            margin-bottom: 15px; 
            font-size: 14px;
        }
        table { 
            width: 100%; 
            border border-neutral-line-collapse: collapse; 
            margin-top: 10px;
        }
        th, td { 
            border border-neutral-line: 1px solid #d1d5db; 
            padding: 6px 8px; 
            text-align: left;
        }
        th { 
            background: #f3f4f6; 
            font-weight: bold;
            color: #374151;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .numeric {
            text-align: right;
        }
    </style>
</head>
<body>
    <h3>TOPSIS Step: {{ $step }}</h3>
    <table>
        @foreach ($rows as $r)
            @if ($loop->first)
                <tr>
                    @foreach ($r as $cell) 
                        <th>{{ $cell }}</th> 
                    @endforeach
                </tr>
            @else
                <tr>
                    @foreach ($r as $cell) 
                        <td class="{{ is_numeric($cell) && $loop->index > 0 ? 'numeric' : '' }}">{{ $cell }}</td> 
                    @endforeach
                </tr>
            @endif
        @endforeach
    </table>
</body>
</html>
