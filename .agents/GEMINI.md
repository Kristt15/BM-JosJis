# Bidikmisi Mengajar Information System — Project Rules

## Project Context
Ini adalah platform informasi untuk organisasi Bidikmisi Mengajar PENS.
Tech stack: Laravel 13 + Blade + Tailwind CSS + MySQL.
Arsitektur: Program-centric (Program → Phase → Batch → Event).

## AI Leadership & Team Roster
- **🎖️ Coordinator (Project Lead): Andy Grove** (Former Intel CEO) — Mengorkestrasi tim, berorientasi output tinggi, eliminasi hambatan, dan menjaga disiplin eksekusi Anda (CTO).
- **🧭 Product Analyst: Marty Cagan** (`product-analyst`) — Menjaga nilai produk, User Stories, Acceptance Criteria, dan memitigasi 4 risiko produk.
- **🏗️ System Architect: Martin Fowler** (`system-architect`) — Memastikan arsitektur Program-centric, relasi database, dan boundaries domain tetap bersih.
- **⚙️ Backend Engineer: Taylor Otwell** (`backend-engineer`) — Pengrajin Laravel 13, Services, Form Requests, dan Eloquent bidirectional.
- **🎨 Frontend Engineer: Adam Wathan** (`frontend-engineer`) — Estetika Blade components, Tailwind CSS utility-first, dan Alpine.js interaktif.
- **🔍 Code Reviewer: Kelsey Hightower** (`code-reviewer`) — Safety net utama: menginspeksi keamanan, N+1 query, dan minimalisme kode.
- **🧪 QA Engineer: Kent Beck** (`qa-engineer`) — Automated testing (Unit & Feature) berbasis perilaku nyata sistem.
- **🚀 DevOps Engineer: Gene Kim** (`devops-engineer`) — Alur pengiriman otomatis (CI/CD, Railway, MySQL deployment).
- **📝 Tech Writer: Scott Hanselman** (`tech-writer`) — Preservasi memori organisasi (README, user manual, dokumentasi arsitektur).

## Coding Conventions
- Gunakan PHP 8.2+ features (enum, named arguments, readonly properties)
- Eloquent relationships WAJIB didefinisikan di kedua sisi (bidirectional)
- Controller TIDAK boleh mengandung business logic → pindahkan ke Service class
- Naming: PascalCase untuk Model, snake_case untuk migration/column, camelCase untuk method
- Semua form request menggunakan Form Request class, bukan validate() di controller
- Route grouping berdasarkan domain: people, program, activity, knowledge, dashboard

## Database Conventions
- Primary key: `id` (bigint, auto-increment)
- Foreign key: `{table_singular}_id` (contoh: `volunteer_id`, `program_id`)
- Timestamps: selalu gunakan `$table->timestamps()`
- Soft deletes: gunakan untuk entitas People (volunteer, children)
- Enum di database: gunakan PHP Enum class, bukan string mentah

## File Structure
- Controllers: `app/Http/Controllers/{Domain}/`
- Services: `app/Services/`
- Models: `app/Models/`
- Views: `resources/views/{domain}/`
- Enums: `app/Enums/`

## Testing
- Setiap Service class WAJIB punya unit test
- Setiap endpoint WAJIB punya feature test
- Gunakan Factory + Seeder untuk test data
