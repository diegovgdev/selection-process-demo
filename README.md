# Selection Process Demo

Demostración de una plataforma de **gestión de procesos de selección de personal**, construida con Laravel 10 y Livewire 3 como proyecto de portafolio.

> ⚠️ **Proyecto DEMO con datos completamente ficticios.**
> Es un sistema nuevo y autónomo. No contiene código, datos, documentos ni reglas de ningún sistema, institución o proceso de selección real.

## Capturas

_Sección reservada para capturas de pantalla (dashboard, postulantes, ficha, procesos y ranking). Se agregarán próximamente._

## Stack tecnológico

- PHP 8.1+ · Laravel 10
- Livewire 3 · Blade
- Tailwind CSS 3 (con `@tailwindcss/forms`) · Vite
- SQLite
- PHPUnit 10 · Laravel Pint

## Funcionalidades

- **Dashboard**: totales, postulaciones activas, personas en evaluación y seleccionadas; distribución por etapa; actividad reciente y accesos rápidos.
- **Postulantes**: listado de 16 postulantes ficticios con búsqueda (nombre, código o cargo) y filtro por estado.
- **Ficha de postulante**: identificador `DEMO-NNN`, línea de tiempo, resultados de evaluación, puntaje consolidado, comentario ficticio y cambio de etapa con validación (incluye control de cupos).
- **Procesos**: 3 procesos ficticios con cupos, postulaciones, estado y progreso visual.
- **Ranking** por proceso con puntaje documental, entrevista, técnica y final, calculado a partir de los datos guardados en SQLite.
- **Reiniciar demo**: restaura el conjunto de datos ficticios inicial dentro de una transacción.

### Cálculo del ranking

`final = 30 % documental + 30 % entrevista + 40 % técnica`. Si falta algún componente (aún no evaluado), se omite y los pesos restantes se reescalan. Desempate por fecha de postulación más antigua; las postulaciones sin puntaje quedan al final.

## Requisitos

- PHP 8.1 o superior (extensiones `pdo_sqlite`, `mbstring`, `openssl`, `fileinfo`)
- Composer
- Node.js y npm

## Instalación local

```bash
git clone https://github.com/diegovgdev/selection-process-demo.git
cd selection-process-demo

composer install
npm install
npm run build

composer setup
```

`composer setup` copia `.env.example` a `.env`, genera la clave de la aplicación, crea `database/database.sqlite`, y ejecuta migraciones y seeders.

Si prefieres hacerlo manualmente:

```bash
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
```

### Ejecutar el servidor

```bash
php artisan serve
```

Abre <http://127.0.0.1:8000>. La aplicación carga directamente el dashboard (sin autenticación). Para desarrollo con recarga de estilos: `npm run dev`.

### Restaurar datos desde la terminal

```bash
composer demo:reset
```

(equivale a `php artisan migrate:fresh --seed`; también puedes usar el botón **Reiniciar demo** de la interfaz).

## Pruebas y calidad de código

```bash
composer test           # PHPUnit: ranking, reinicio de datos, filtros y cambio de etapa
composer format:check   # Laravel Pint (solo verifica)
composer format         # Laravel Pint (corrige)
```

## Estructura breve

```
app/
├── Enums/Stage.php              # Etapas del proceso, etiquetas y colores
├── Livewire/                    # Dashboard, Candidates, Processes, Ranking, DemoResetButton
├── Models/                      # SelectionProcess, Candidate, Application, Evaluation, ActivityLog
└── Services/                    # RankingCalculator, DemoResetter
database/
├── factories/ · migrations/
└── seeders/DemoSeeder.php       # Datos ficticios deterministas
resources/views/                 # Layout, componentes Blade y vistas Livewire
tests/                           # Pruebas unitarias y de funcionalidad
```

## Decisiones técnicas

- **Componentes Livewire de página completa** con rutas claras (`/postulantes`, `/procesos`, `/ranking`) y filtros sincronizados en la URL.
- **Lógica de negocio fuera de las vistas**: el cálculo del ranking vive en `RankingCalculator` y el reinicio en `DemoResetter`.
- **Seeder determinista** (sin aleatoriedad): el estado base es siempre idéntico, lo que permite probar el reinicio.
- **Reinicio transaccional**: si falla, los datos previos se conservan. Solo toca las tablas de la propia demo.
- **Enum de etapas** como única fuente de verdad para etiquetas, colores y validación.
- **Sin autenticación ni usuarios**, para que cualquier persona pueda explorar la demo directamente.
- **Laravel 10**: se mantiene por requisito del proyecto. Esta versión ya no recibe actualizaciones de seguridad y Composer reporta avisos de seguridad sobre ella, por lo que `composer.json` desactiva el bloqueo por avisos (`audit.block-insecure`). Es aceptable para una demo local sin datos reales; no la expongas con datos sensibles.

## Limitaciones

- Demo local, sin autenticación ni control de acceso.
- Sin carga de documentos reales ni integraciones externas.
- Todos los datos son ficticios y se pueden modificar libremente (recuerda que «Reiniciar demo» restaura el estado base para todos los visitantes).
- Las páginas son de solo lectura salvo el cambio de etapa y el reinicio.

## Privacidad y seguridad

- Todas las personas, procesos, puntajes y comentarios son inventados; los identificadores usan el prefijo `DEMO-`.
- No se almacenan datos personales reales ni campos sensibles.
- El repositorio no incluye `.env`, bases de datos SQLite, documentos, claves ni credenciales (ver `.gitignore`).

## Licencia

Todos los derechos reservados. Este repositorio es público únicamente con fines de demostración y **no** se concede licencia de uso, copia, modificación ni distribución.
