# 🎨 Arquitectura de Vistas y Experiencia de Usuario (UX)

> **Nota para el Ingeniero:**
> La capa de presentación de **PIHCSA** ha sido diseñada bajo principios de **modularidad y reutilización (DRY)**. Se utiliza **Laravel Blade** para la lógica de componentes y **AdminLTE 3** para una interfaz administrativa profesional y responsiva.

---

## 🏗️ 1. Organización y Modularidad del Directorio `views/`

En lugar de archivos masivos, la interfaz se divide en piezas pequeñas y manejables, facilitando el mantenimiento y la consistencia visual.

* **`layouts/`**: Contiene el esqueleto maestro (`app.blade.php`). Gestiona la herencia de plantillas, la inyección de scripts vía `@stack` y la estructura de navegación global.
* **`components/`**: Fragmentos UI genéricos y reutilizables como botones, alertas y tarjetas de estadísticas.
* **`equipos/partials/`**: Esta es la capa de **micro-componentes de hardware**. Permite renderizar detalles técnicos (RAM, Discos, Procesadores) en múltiples pantallas (Detalles, Edición, Historial) usando una única fuente de verdad.



---

## 🧙‍♂️ 2. Estrategia de UX: El Asset Wizard (Flujo Guiado)

La funcionalidad estrella del sistema es el **Wizard de Registro**. Para mitigar el error humano y la fatiga del usuario, se fragmentó el formulario de activos en un flujo lógico de 7 pasos:

| Vista | Rol en el Flujo | Técnica de Implementación |
| :--- | :--- | :--- |
| `wizard.blade.php` | Orquestador | Contenedor principal con indicadores de progreso. |
| `wizard-ubicacion.blade.php` | Geolocalización | Carga dinámica de sedes desde el modelo `Ubicacion`. |
| `wizard-rams.blade.php` | Configuración Modular | Formulario incremental para múltiples módulos de memoria. |
| `wizard-procesador.blade.php` | Punto de Control | Validación final antes de la persistencia atómica en la DB. |

**Beneficio Técnico:** El uso de vistas separadas (`wizard-*.blade.php`) permite validar cada sección de hardware de forma independiente antes de avanzar.

---

## 🧩 3. Componentización de Hardware (`partials/`)

Para presentar la ficha técnica de los equipos, se crearon componentes específicos que transforman datos crudos en información legible:

* **`item-disco.blade.php`**: Gestiona la lógica visual para mostrar capacidades (GB/TB) y tipos de tecnología (SSD/HDD).
* **`item-procesador.blade.php`**: Centraliza la visualización de modelos y frecuencias del CPU.
* **`item-monitor.blade.php`**: Renderiza la información de periféricos de salida asociados.

---

## 🛡️ 4. Feedback y Auditoría Visual

La interfaz garantiza que el usuario siempre tenga control y visibilidad sobre sus acciones:

1.  **Notificaciones de Estado**: Componentes que escuchan variables de sesión (`success`, `error`) para disparar alertas visuales inmediatas tras cada operación.
2.  **Modales de Seguridad**: Ubicados en `profile/partials/`, se encargan de confirmar acciones destructivas (borrado de cuenta o equipo) mediante capas de confirmación.
3.  **Timeline de Historial**: La vista `historial/index.blade.php` renderiza la bitácora de auditoría, convirtiendo registros JSON complejos en una línea de tiempo intuitiva.

---

## 🛠️ Stack de Presentación

* **Engine:** Laravel Blade (Uso extensivo de `@extends`, `@include` y `@component`).
* **UI Framework:** Bootstrap 4 + AdminLTE 3.
* **Interactividad:** JavaScript inyectado por secciones para validaciones en tiempo real y manejo de formularios dinámicos.
* **Iconografía:** FontAwesome 5 para una referencia visual rápida de cada tipo de hardware.

---
**Enfoque de Desarrollo:** "Clean UI, Robust Logic". Esta estructura permite que el sistema crezca en tipos de activos sin necesidad de rediseñar las vistas existentes.