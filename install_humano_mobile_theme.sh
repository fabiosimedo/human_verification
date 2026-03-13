#!/usr/bin/env bash
set -euo pipefail

echo "== HUMANO UI Mobile‑First Theme Installer =="
echo ""

PROJECT_ROOT="$(pwd)"

echo "Project root: $PROJECT_ROOT"

BACKUP_DIR="storage/backups/ui_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

backup_file () {
  FILE="$1"
  if [ -f "$FILE" ]; then
    mkdir -p "$BACKUP_DIR/$(dirname "$FILE")"
    cp "$FILE" "$BACKUP_DIR/$FILE"
    echo "Backup created: $BACKUP_DIR/$FILE"
  fi
}

write_file () {
  FILE="$1"
  mkdir -p "$(dirname "$FILE")"
  cat > "$FILE"
  echo "Updated: $FILE"
}

FILES=(
resources/css/app.css
resources/views/layouts/app.blade.php
resources/views/layouts/guest.blade.php
resources/views/layouts/navigation.blade.php
resources/views/components/input-label.blade.php
resources/views/components/text-input.blade.php
resources/views/components/primary-button.blade.php
resources/views/components/secondary-button.blade.php
resources/views/components/danger-button.blade.php
resources/views/components/auth-session-status.blade.php
)

echo ""
echo "Creating backups..."
for f in "${FILES[@]}"; do
  backup_file "$f"
done

echo ""
echo "Applying new UI files..."

write_file resources/css/app.css <<'EOF'
@tailwind base;
@tailwind components;
@tailwind utilities;

:root{
--hum-bg:#07111f;
--hum-surface:#0f1c31;
--hum-text:#f5f7fb;
--hum-primary:#9eff00;
}

body{
background:#07111f;
color:#f5f7fb;
}

.hum-card{
background:linear-gradient(180deg,#0f1c31,#0a1321);
border-radius:24px;
padding:24px;
border:1px solid rgba(255,255,255,0.06);
box-shadow:0 20px 40px rgba(0,0,0,0.4);
}

.hum-btn-primary{
background:#9eff00;
color:#0b111b;
padding:12px 20px;
border-radius:14px;
font-weight:600;
}

.hum-btn-primary:hover{
filter:brightness(1.1);
}

.hum-input{
background:#0a1628;
border:1px solid rgba(255,255,255,0.1);
border-radius:12px;
padding:12px;
width:100%;
color:white;
}

.hum-label{
font-size:14px;
margin-bottom:6px;
display:block;
}
EOF

write_file resources/views/layouts/app.blade.php <<'EOF'
<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name','Humano') }}</title>

@vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body>

<div class="min-h-screen">

@include('layouts.navigation')

<main class="p-6">
{{ $slot }}
</main>

</div>

</body>
</html>
EOF

write_file resources/views/layouts/guest.blade.php <<'EOF'
<!DOCTYPE html>
<html lang="{{ str_replace('_','-',app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">

<title>{{ config('app.name','Humano') }}</title>

@vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="flex items-center justify-center min-h-screen">

<div class="hum-card max-w-md w-full">
{{ $slot }}
</div>

</body>
</html>
EOF

write_file resources/views/layouts/navigation.blade.php <<'EOF'
<nav class="p-4 border-b border-white/10 flex justify-between">

<a href="{{ route('dashboard') }}" class="font-bold">
HUMANO
</a>

<div class="flex gap-4 text-sm">

<a href="{{ route('dashboard') }}">Dashboard</a>
<a href="{{ route('dashboard.links.index') }}">Links</a>
<a href="{{ route('dashboard.profile.edit') }}">Perfil</a>

<form method="POST" action="{{ route('logout') }}">
@csrf
<button>Sair</button>
</form>

</div>

</nav>
EOF

write_file resources/views/components/input-label.blade.php <<'EOF'
<label {{ $attributes->merge(['class'=>'hum-label']) }}>
{{ $value ?? $slot }}
</label>
EOF

write_file resources/views/components/text-input.blade.php <<'EOF'
<input {{ $attributes->merge(['class'=>'hum-input']) }}>
EOF

write_file resources/views/components/primary-button.blade.php <<'EOF'
<button {{ $attributes->merge(['class'=>'hum-btn-primary']) }}>
{{ $slot }}
</button>
EOF

write_file resources/views/components/secondary-button.blade.php <<'EOF'
<button {{ $attributes->merge(['class'=>'px-4 py-2 rounded border border-white/10']) }}>
{{ $slot }}
</button>
EOF

write_file resources/views/components/danger-button.blade.php <<'EOF'
<button {{ $attributes->merge(['class'=>'px-4 py-2 rounded bg-red-500 text-white']) }}>
{{ $slot }}
</button>
EOF

write_file resources/views/components/auth-session-status.blade.php <<'EOF'
@if ($status)
<div class="mb-4 text-sm text-green-400">
{{ $status }}
</div>
@endif
EOF

echo ""
echo "UI installation finished."
echo ""
echo "Now rebuild frontend:"
echo "npm install"
echo "npm run build"
echo ""
echo "Done."
