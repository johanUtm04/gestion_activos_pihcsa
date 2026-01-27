# 🗄️ Documentación del Esquema de Base de Datos

> **Nota para el futuro Ingeniero:**
> Estás ante el corazón del sistema de gestión de activos de **PIHCSA**. Esta base de datos fue diseñada para mantener una integridad referencial estricta. La lógica principal dicta que los **Equipos** son la entidad central: si un equipo se elimina, sus componentes físicos (Discos, RAM, Procesador) se eliminan en cascada para mantener la limpieza, pero el historial de auditoría y la relación con el usuario se preservan para trazabilidad legal y administrativa.

---

## 📊 Entidades Principales

### 💻 El Núcleo: `equipos`
Es la tabla maestra que centraliza los activos. 
* **Identificadores**: Utiliza `id` autoincremental y almacena el `serial` del fabricante.
* **Atributos Clave**: Gestiona marca, tipo (Laptop/Escritorio), sistema operativo y valores financieros.
* **Persistencia**: Implementa `deleted_at` (Soft Deletes), permitiendo la recuperación de activos eliminados lógicamente.

### 🛠️ Hardware y Componentes
Tablas que dependen directamente de la existencia de un equipo. Todas poseen una relación `ON DELETE CASCADE` con la tabla `equipos`:
* **`discos_duros`**: Almacena capacidad (GB/TB), tipo (SSD/HDD) e interfaz.
* **`rams`**: Registra capacidad, frecuencia (MHz) y tipo de memoria (DDR).
* **`procesadores`**: Detalla la marca y frecuencia del microprocesador.
* **`monitores` & `perifericos`**: Registran componentes externos asociados, marcas y números de serie.

### 👤 Seguridad y Auditoría
* **`users`**: Gestiona el acceso con roles definidos (`ADMIN`, `SISTEMAS`, `CONTABILIDAD`) y estatus de cuenta.
* **`historiales_log`**: La "caja negra" del sistema. Registra acciones (`CREATE`, `UPDATE`, `DELETE`) y guarda el estado anterior/nuevo en un campo `detalles_json`.

---

## 🏗️ Diccionario de Relaciones (Foreign Keys)

| Tabla Origen | Campo FK | Tabla Destino | Regla de Borrado (On Delete) |
| :--- | :--- | :--- | :--- |
| `equipos` | `usuario_id` | `users` | `SET NULL` (El equipo se libera del usuario) |
| `equipos` | `ubicacion_id` | `ubicaciones` | `SET NULL` (El equipo pierde su ubicación física) |
| `discos_duros` | `equipo_id` | `equipos` | `CASCADE` (Se elimina con el equipo) |
| `rams` | `equipo_id` | `equipos` | `CASCADE` (Se elimina con el equipo) |
| `historiales_log`| `activo_id` | `equipos` | `CASCADE` (Mantiene el log ligado al ID) |

---

## 🛠️ Instrucciones Técnicas para Mantenimiento

1. **Versionamiento**: Nunca alteres tablas mediante SQL manual. Utiliza siempre las migraciones de Laravel disponibles en el proyecto; el orden de ejecución está registrado en la tabla `migrations`.
2. **Integridad de Auditoría**: La tabla `historiales_log` es crítica para el cumplimiento de normas internas. Asegúrate de que cualquier nuevo módulo que afecte a `equipos` dispare un evento de log.
3. **Escalabilidad**: El campo `sistema_operativo` fue ajustado a **60 caracteres** para soportar distribuciones específicas de software profesional.

---
**Última actualización de esquema:** 05 de Enero, 2026.