---
paths:
  - resources/views/partials/sidebar.blade.php
---

# Partials

## Deleting sidebar items must preserve @endif balance
Sebelum menghapus 1 item menu di sidebar, pastikan Anda tidak menghapus @endif yang menutup blok @if (request()->routeIs('admin.*')) / ('student.*'). Struktur: item admin terakhir (Kelola Galeri) lalu @endif, kemudian @if student.*.
