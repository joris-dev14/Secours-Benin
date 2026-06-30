<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $content->title }} - Secours Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f8fafc; color: #0A2540; }
        .legal-card { background: white; border-radius: 20px; padding: 2rem; box-shadow: 0 10px 35px rgba(0,0,0,0.06); }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="legal-card">
            <h1 class="fw-bold mb-2">{{ $content->title }}</h1>
            <p class="text-muted mb-4">{{ $content->subtitle }}</p>
            @php
                $contentData = $content->content_json ?? [];
                if (is_string($contentData)) {
                    $contentData = json_decode($contentData, true) ?? [];
                } elseif (is_object($contentData)) {
                    $contentData = (array) $contentData;
                }
                $sections = $contentData['sections'] ?? [];
            @endphp
            @foreach($sections as $section)
                <div class="mb-4">
                    <h4 class="fw-semibold">{{ $section['title'] }}</h4>
                    <p class="text-muted mb-0">{{ $section['body'] }}</p>
                </div>
            @endforeach
            <a href="/" class="btn btn-primary mt-3">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
