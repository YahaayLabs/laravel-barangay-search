<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'laravel-barangay-search' }} — demo</title>
    <style>
        html, body { margin: 0; background: #f1f5f9; }
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: #0f172a;
            -webkit-font-smoothing: antialiased;
        }
        .demo {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .page { width: 100%; max-width: 28rem; }
        .page-header { text-align: center; margin-bottom: 1.25rem; }
        .page-title {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }
        .page-tagline { margin: 0.35rem 0 0; font-size: 0.85rem; color: #64748b; }
        .page-repo {
            display: inline-block;
            margin-top: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: #059669;
            text-decoration: none;
            word-break: break-all;
        }
        .page-repo:hover { text-decoration: underline; }
        .page-footer {
            margin: 1rem 0 0;
            text-align: center;
            font-size: 0.75rem;
            color: #94a3b8;
        }
        .page-footer a { color: #64748b; text-decoration: none; }
        .page-footer a:hover { color: #059669; text-decoration: underline; }
        .card {
            width: 100%;
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04), 0 12px 32px rgba(15, 23, 42, 0.06);
            padding: 1.75rem 1.5rem 1.5rem;
        }
        .eyebrow {
            margin: 0 0 0.25rem;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #059669;
        }
        .card-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .sub {
            margin: 0.4rem 0 0;
            font-size: 0.8rem;
            color: #64748b;
        }
        .sub code {
            font-size: 0.75rem;
            background: #f1f5f9;
            padding: 0.1rem 0.35rem;
            border-radius: 4px;
        }
        .fields { display: flex; flex-direction: column; gap: 1rem; margin-top: 1.5rem; }
        .row.two {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem;
        }
        .field { display: flex; flex-direction: column; gap: 0.35rem; }
        .field > span {
            font-size: 0.8rem;
            font-weight: 500;
            color: #475569;
        }
        .field input[type="text"],
        .field input[type="tel"],
        .field textarea {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.6rem 0.75rem;
            font-size: 0.9rem;
            color: #0f172a;
            background: #fff;
            font-family: inherit;
        }
        .field input:focus,
        .field textarea:focus {
            outline: none;
            border-color: #34d399;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
        }
        .field-barangay {
            min-height: 4.5rem;
            padding: 0.75rem;
            border: 1px dashed #6ee7b7;
            border-radius: 0.75rem;
            background: #ecfdf5;
        }
        .field-barangay > span {
            margin-bottom: 0.25rem;
            color: #065f46;
        }
        .selection {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            padding: 0.65rem 0.75rem;
            border-radius: 0.5rem;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            font-size: 0.8rem;
            font-weight: 500;
            color: #065f46;
            line-height: 1.35;
        }
        .selection-dot {
            flex-shrink: 0;
            width: 0.45rem;
            height: 0.45rem;
            margin-top: 0.3rem;
            border-radius: 999px;
            background: #10b981;
        }
        .banner {
            padding: 0.65rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
        }
        .banner.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }
        .banner.ok {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
        }
        .btn {
            margin-top: 0.25rem;
            width: 100%;
            border: none;
            border-radius: 0.5rem;
            padding: 0.7rem 1rem;
            font-size: 0.9rem;
            font-weight: 600;
            color: #fff;
            background: #059669;
            cursor: pointer;
        }
        .btn:hover { background: #047857; }
        @media (max-width: 420px) {
            .row.two { grid-template-columns: 1fr; }
        }
    </style>
    @livewireStyles
</head>
<body>
    <div class="demo">
        <div class="page">
            <header class="page-header">
                <h1 class="page-title">laravel-barangay-search</h1>
                <p class="page-tagline">Philippine barangay autocomplete — Laravel Livewire demo</p>
                <a
                    class="page-repo"
                    href="https://github.com/YahaayLabs/laravel-barangay-search"
                    target="_blank"
                    rel="noopener noreferrer"
                >https://github.com/YahaayLabs/laravel-barangay-search</a>
            </header>

            {{ $slot }}

            <p class="page-footer">
                <a href="https://github.com/YahaayLabs/laravel-barangay-search" target="_blank" rel="noopener noreferrer">GitHub</a>
                ·
                <a href="https://gis.ph" target="_blank" rel="noopener noreferrer">gis.ph</a>
                ·
                <a href="https://packagist.org/packages/yahaaylabs/laravel-barangay-search" target="_blank" rel="noopener noreferrer">Packagist</a>
            </p>
        </div>
    </div>
    @livewireScripts
</body>
</html>
