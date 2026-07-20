<h1 align="center"> Gestion de Activos Pihcsa </h1>

<p align="center">
  Enterprise-grade, multi-company Asset Management System engineered for modern hardware allocation, vehicle fleet tracking, maintenance workflows, and fiscal asset depreciation.
</p>

<p align="center">
  <img alt="Build" src="https://img.shields.io/badge/Build-Passing-brightgreen?style=for-the-badge">
  <img alt="Issues" src="https://img.shields.io/badge/Issues-0%20Open-blue?style=for-the-badge">
  <img alt="Contributions" src="https://img.shields.io/badge/Contributions-Welcome-orange?style=for-the-badge">
  <img alt="License" src="https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge">
</p>
<!-- 
  **Note:** These are static placeholder badges. Replace them with your project's actual badges.
  You can generate your own at https://shields.io
-->

---

### 📋 Table of Contents
- [Overview](#-overview)
- [Key Features](#-key-features)
- [Tech Stack & Architecture](#-tech-stack--architecture)
- [Project Structure](#-project-structure)
- [Getting Started](#-getting-started)
- [Usage](#-usage)
- [Contributing](#-contributing)
- [License](#-license)

---

## 🌟 Overview

**Gestion de Activos Pihcsa** is a unified administrative web application developed to handle the complete lifecycle of corporate physical assets. From specialized IT infrastructure to vehicle fleets, the application streamlines acquisition records, internal component updates, ongoing maintenance, and fiscal calculations. Operating with multi-tenant capabilities, the system allows large organizations to toggle across distinct business entities (*Empresas*) and local branches (*Sucursales*), maintaining isolated data partitions under a robust role-based security framework.

> ### ⚠️ The Real-World Problem
> Modern enterprises struggle with fragmented tracking systems for physical assets. Hardware components (RAM, CPU, Hard Drives) get swapped without audit trails, vehicle maintenance schedules slip through manual logs, and fiscal depreciation is calculated on disconnected spreadsheets with outdated inflation parameters (INPC). This lack of cohesion leads to financial inaccuracies, unaccounted hardware drift, and operational inefficiencies across regional branches.

**Gestion de Activos Pihcsa** resolves these challenges by consolidating IT asset tracking (including granular component-level swaps), vehicle maintenance auditing, and automated financial depreciation calculations into a highly responsive, modern interface. Powered by Laravel and integrated with the AdminLTE dashboard, the platform guarantees full accounting transparency, systematic maintenance schedules, and robust hardware inventory control.

---

## ✨ Key Features

### 💻 Granular IT Asset Lifecycle Management
- **IT Hardware Tracking:** Register computer workstations (*Equipos*) with structured attributes like Model, Department, Folio, Invoice Number, and Usage Commencement Date.
- **Dynamic Component Wizard:** Utilize a multi-step registration wizard (`EquipoWizardController`) to attach specific hardware specs including:
  - **Processors:** Clock speed (GHz) and models.
  - **Memory:** RAM size, technology, and type.
  - **Storage:** Hard Drives (HDD/SSD) tracking with individual serial numbers.
  - **Monitors & Peripherals:** Specialized peripherals associated with corresponding workstations.
- **The "Bolsa de Cambios" (Component Swap):** Record modifications and hardware swaps to map exact inventory evolution without losing trace of physical parts.

### 🚗 Vehicle Fleet & Maintenance Log
- **Fleet Classification:** Catalog transport units categorized by brand and specialized vehicle profiles (`CatTipoVehiculo`).
- **Comprehensive Documentation:** Store vehicle certifications, registration plates, and specialized paperwork (`VehiculoDocumentacion`).
- **Maintenance Schedulers:** Log regular and preventative maintenance procedures (`MantenimientoVehiculo`) backed by specialized Observers that automatically trigger status adjustments upon work completion.

### 📈 Financial Depreciation & Inflation Calculations (INPC)
- **Fiscal Compliance Integration:** Calculate automated depreciation of assets based on custom percentage tables (`Tasa`) and the Mexican *Índice Nacional de Precios al Consumidor* (`Inpc`), which accounts for official inflation indexation.
- **Reporting Services:** Generate comprehensive annual matrices and exportable asset reports via `DepreciacionReportService` to facilitate tax declaration alignments.

### 🏢 Multi-Tenant Enterprise Structure
- **Entity Selection:** Seamlessly pivot the session context between independent registered corporate entities (*Empresas*) and local operating centers (*Sucursales*).
- **Context-Aware Connections:** The platform enforces custom middleware (`EnsureEmpresaIsSelected`, `EnsureSucursalIsSelected`, and `SetSucursalConnection`) to isolate query boundaries, ensuring users only access localized scopes relevant to their current assignments.

### 🔍 Bulletproof Audit Trail & Recovery
- **Historial Logs:** Log every administrative shift, structural component modification, and vehicle status update using automated triggers monitored by model observers (`EquipoObserver`, `RamObserver`, `MantenimientoObserver`, etc.).
- **Soft Delete Recycle Bin:** Safeguard against accidental data losses with an integrated platform trash can (*Papelera*), allowing the restoration of items with a single click.

---

## 🛠️ Tech Stack & Architecture

The application is built upon a standard **MVC (Model-View-Controller)** pattern, utilizing Laravel for robust server-side processing, database migrations, and observer event patterns. The client-side is rendered with standard Laravel Blade templates styled through Tailwind CSS and the comprehensive AdminLTE admin panel.

| Layer | Technology | Purpose | Why it was Chosen |
| :--- | :--- | :--- | :--- |
| **Backend Framework** | Laravel (PHP) | Application routing, security, ORM (Eloquent), and business logic. | Provides solid MVC scaffolding, secure authentication, database seeding, and extensive DB migrations support. |
| **Admin UI Dashboard** | AdminLTE v3.2.0 | Layout panels, structural tables, and user panels. | Offers a responsive, battle-tested UI template loaded with ready-to-use widgets, sidebars, and custom forms. |
| **CSS styling** | Tailwind CSS | Utility-first responsive components and custom layouts. | Allows rapid interface modifications and utility-based responsive controls outside the AdminLTE defaults. |
| **Asset Compiler** | Vite | Compilation of frontend assets (CSS, JS). | Offers blistering-fast hot-module replacement and optimized bundle generation for rapid production. |
| **Database Engines** | MySQL (SQL Scripts) | Relational storage for transactions, tracking components, and audit logs. | Implements reliable relational constraints, indexing, and transactional rollbacks required for asset tracing. |
| **Client Utilities** | jQuery, Select2, SweetAlert2 | Frontend interactions, searchable select dropdowns, and modal responses. | Standardized extensions to boost usability for complex selections (such as picking specific processors or serial numbers). |

---

## 📁 Project Structure

Below is an overview of the critical folders and application entry points of the project:

```
johanUtm04-gestion_activos_pihcsa-936357a/
├── 📄 artisan                       # Laravel CLI entry point
├── 📄 composer.json                 # Backend PHP dependency manifest
├── 📄 package.json                  # Frontend JS/CSS dependency manifest
├── 📄 postcss.config.js             # CSS post-processing configuration
├── 📄 tailwind.config.js            # Tailwind CSS style overrides
├── 📄 vite.config.js                # Vite build utility settings
├── 📂 app/
│   ├── 📂 Console/                  # Artisan custom tasks
│   │   └── 📂 Commands/
│   │       ├── 📄 GenerarHistorialInicial.php
│   │       └── 📄 RespaldoSemanal.php # Weekly backup automation task
│   ├── 📂 Exceptions/               # Core exception handlers
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/          # Request handshakes and redirection
│   │   │   ├── 📄 DepreciacionController.php
│   │   │   ├── 📄 EquipoController.php
│   │   │   ├── 📄 EquipoWizardController.php
│   │   │   ├── 📄 InpcController.php
│   │   │   └── 📄 VehiculoController.php
│   │   └── 📂 Middleware/           # Routing security layers
│   │       ├── 📄 EnsureEmpresaIsSelected.php
│   │       ├── 📄 EnsureSucursalIsSelected.php
│   │       └── 📄 SetSucursalConnection.php
│   ├── 📂 Models/                   # Database mapping layer
│   │   ├── 📄 Equipo.php
│   │   ├── 📄 Inpc.php
│   │   ├── 📄 MantenimientoVehiculo.php
│   │   ├── 📄 Sucursal.php
│   │   └── 📄 Vehiculo.php
│   ├── 📂 Observers/                # Lifecycle hooks triggers
│   │   ├── 📄 EquipoObserver.php
│   │   ├── 📄 MantenimientoObserver.php
│   │   └── 📄 RamObserver.php
│   ├── 📂 Providers/                # App registration bootstraps
│   └── 📂 Services/                 # Business execution engines
│       ├── 📄 AuditService.php      # Audit log recorder
│       ├── 📄 DepreciacionReportService.php # Depreciation compiler
│       └── 📄 ExportService.php     # System exporter
├── 📂 database/
│   ├── 📄 backup_actualizado.sql    # System DB Backup file
│   ├── 📄 gestion_activos_SUPER.sql # Core relational SQL setup
│   ├── 📂 migrations/               # Chronological schema structures
│   └── 📂 seeders/                  # Mock dataset inserts
├── 📂 public/                       # Static public assets
│   ├── 📂 css/                      # Modules styling directories
│   └── 📂 js/                       # Dynamic event triggers
├── 📂 resources/
│   ├── 📂 css/
│   │   └── 📄 app.css               # Core entry style sheet
│   ├── 📂 js/
│   │   └── 📄 app.js                # Frontend entry JS
│   └── 📂 views/                    # Blade visualization layouts
│       ├── 📂 depreciacion/
│       ├── 📂 equipos/              # Workstation forms, wizards & views
│       ├── 📂 empresas/
│       ├── 📂 layouts/
│       └── 📂 vehiculos/            # Fleet records, updates, and modals
└── 📂 tests/                        # Pest/PHPUnit verification tests
```

---

## 🚀 Getting Started

Follow these steps to set up the **Gestion de Activos Pihcsa** application on your local machine:

### 📋 Prerequisites
- **PHP** $\ge$ 8.1
- **Composer** (PHP dependency manager)
- **Node.js** (including NPM)
- **MySQL / MariaDB** server running locally

### ⚙️ Installation & Configuration

1. **Clone the Repository:**
   ```bash
   git clone https://github.com/your-username/gestion-activos-pihcsa.git
   cd database/
   ```

2. **Initialize Database Schema:**
   Create a local database instance (e.g., named `gestion_activos`) and import the provided structured database file to pre-populate required enterprise catalog models, permissions, and INPC parameters:
   ```bash
   mysql -u root -p your_database_name < gestion_activos_SUPER.sql
   ```
   *(Alternatively, you can configure your standard database connection parameters and run native Laravel migrations and seeders):*
   ```bash
   php artisan migrate --seed
   ```

3. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

4. **Install Frontend Dependencies:**
   ```bash
   npm install
   ```

5. **Generate local Environment configurations:**
   Duplicate the template parameters:
   ```bash
   cp .env.example .env
   ```
   Ensure you configure the `.env` database block to point directly to your imported instance:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gestion_activos
   DB_USERNAME=your_db_username
   DB_PASSWORD=your_db_password
   ```

6. **Generate Application encryption key:**
   ```bash
   php artisan key:generate
   ```

7. **Compile Assets using Vite:**
   For development environments:
   ```bash
   npm run dev
   ```
   For optimized production packaging:
   ```bash
   npm run build
   ```

8. **Start the local Web Server:**
   ```bash
   php artisan serve
   ```
   Navigate with your preferred browser to `http://127.0.0.1:8000`.

---

## 🔧 Usage

### 1. Selecting Corporate Context
Upon login, you will be redirected to the context selector page if none is locked in. Select your target **Empresa** (Enterprise Entity) and corresponding local **Sucursal** (Operating Branch) to unlock the respective inventory databases.

```
Dashboard -> Select Empresa [Pihcsa Corp] -> Select Sucursal [Matriz] -> View Local Assets
```

### 2. Using the IT Workstation Wizard
To register complete IT packages smoothly without missing component details:
1. Navigate to **Equipos** and click **Nuevo Equipo (Asistente)**.
2. **Step 1: Procesador:** Pick or define processor core counts and CPU models.
3. **Step 2: RAM:** Set memory configurations (Size, DDR Speed Type).
4. **Step 3: Storage:** Input serial codes, capacities, and technology details.
5. **Step 4: Accessories:** Tie in monitors and peripheral accessories.
6. **Step 5: Details:** Fill in serial tags, invoice references, and users to compile and instantiate your asset item.

### 3. Monitoring Vehicles & Maintenance
Maintain exact schedules for transport items:
- Navigate to the **Vehículos** section to view registered cars or cargo units.
- Upload mandatory tracking documentation under **Documentos**.
- Add scheduled servicing under **Mantenimiento**. The platform's automated system observers flag items in need of mechanical supervision based on log updates.

### 4. Running Financial Depreciation Matrices
Generate fiscal depreciation breakdowns instantly:
- Go to **Depreciación**.
- Select the tracking period (Year) and target assets.
- The system automatically polls registered index adjustments (`INPC`) and custom percentage coefficients (`Tasas`), generating a live annual spreadsheet breakdown ready for export.

---

## 🤝 Contributing

We welcome contributions to improve **Gestion de Activos Pihcsa**! Your input helps make this project better for everyone.

### How to Contribute

1. **Fork the repository** - Click the 'Fork' button at the top right of this page.
2. **Create a feature branch** 
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Make your changes** - Improve code, database structures, documentation, or design modules.
4. **Test thoroughly** - Ensure all changes integrate with existing models and observers.
   ```bash
   ./vendor/bin/phpunit
   ```
5. **Commit your changes** - Write clear, descriptive commit messages.
   ```bash
   git commit -m 'Add: Dynamic validation to RAM component limits'
   ```
6. **Push to your branch**
   ```bash
   git push origin feature/amazing-feature
   ```
7. **Open a Pull Request** - Submit your changes for review.

### Development Guidelines

- ✅ Follow the existing code style and naming conventions (PSR-12 for PHP).
- 📝 Add comments for complex logic (e.g., custom database connection handlers or custom depreciation equations).
- 🧪 Write migration rollbacks and seeding configurations for any added structures.
- 📚 Update relevant documentation if workflow properties or permissions change.

### Ideas for Contributions

We are currently looking for support on:
- 🐛 **Bug Fixes:** Resolving edge cases on nested multi-company asset transfers.
- ✨ **New Features:** Adding scheduled asset notification triggers for pending service checkups.
- 📖 **Documentation:** Localizing dashboard user guides for dynamic field values.
- 🧪 **Testing:** Adding comprehensive Pest test cases for database switching observers.

---

## 📝 License

This project is licensed under the **MIT License** - see the [LICENSE](LICENSE) file for complete details.

### What this means:

- ✅ **Commercial use:** You can use this project commercially.
- ✅ **Modification:** You can modify the code to match custom fiscal systems.
- ✅ **Distribution:** You can distribute this software freely.
- ✅ **Private use:** You can use this project privately inside closed infrastructures.
- ⚠️ **Liability:** The software is provided "as is", without warranty of any kind.

---

<p align="center">Made with ❤️ by the Pihcsa Asset Management Dev Team</p>
<p align="center">
  <a href="#">⬆️ Back to Top</a>
</p>