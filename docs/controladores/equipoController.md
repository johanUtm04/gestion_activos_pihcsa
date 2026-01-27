# 🎮 EquipoController

> **Nota para el futuro Ingeniero:**
> Este controlador es el cerebro operativo del sistema de activos. Su diseño principal se basa en un **Wizard de Pasos** para la creación y un sistema de **Sincronización Dinámica** para la actualización de hardware. Si necesitas modificar cómo se guardan las RAMs o Discos, el método clave es `syncRelation`.

## 📌 Responsabilidades
* Gestión del ciclo de vida completo de los Activos (Equipos).
* Filtrado avanzado de inventario por ubicación, tipo y marca.
* Orquestación de datos mediante sesiones temporales (Wizard).
* Registro de eventos de mantenimiento en el historial de auditoría.

---

## 🛠️ Métodos Principales

### `index(Request $request)`
Muestra el inventario global.
* **Eager Loading**: Carga todas las piezas (RAM, Discos, etc.) en una sola consulta para optimizar el rendimiento.
* **Filtros**: Soporta búsqueda por sección (marca/serial/tipo), ubicación física y categoría de activo.
* **Paginación**: Configurada a **11 registros** por página para ajuste de diseño.

### `store(Request $request)`
Inicia el proceso de alta.
* **Wizard**: No guarda directamente en la BD. Valida los datos base y los almacena en la sesión (`wizard_equipo`) junto con un **UUID** único.
* **Autogeneración**: Si el serial no se proporciona, genera uno interno con el formato `INT-YYYY-RAND`.

### `update(Request $request, Equipo $equipo)`
Procesa cambios masivos.
* Actualiza los datos base del equipo.
* Invoca a `syncRelation` para cada componente de hardware, permitiendo crear, actualizar o eliminar piezas desde una misma vista.
* **Cálculo de Posición**: Redirige al usuario exactamente a la página donde se encuentra el equipo editado.

### `saveWork(Equipo $equipo, Request $request)`
Registra acciones de mantenimiento.
* Almacena el evento en `historiales_log`.
* Inserta una estructura **HTML/JSON** en los detalles para facilitar la visualización del historial en el frontend.

---

## 🔧 Lógica de Helper Functions (Privadas)

### `syncRelation($relation, array $items)`
Esta es la función más crítica para el mantenimiento de hardware:
1. **Detección de Eliminación**: Si el ítem viene con la bandera `_delete`, lo remueve físicamente de la base de datos.
2. **UpdateOrCreate**: Para el resto de los ítems, busca por `id`; si existe lo actualiza con los nuevos datos, si no, crea uno nuevo vinculado al equipo.

---

## 📝 Reglas de Validación (Store/Update)

| Campo | Regla | Descripción |
| :--- | :--- | :--- |
| `tipo_equipo` | `required` | Debe ser Laptop, Escritorio, etc. |
| `sistema_operativo`| `max:35` | Limitado según la estructura de la BD. |
| `usuario_id` | `exists:users` | El usuario asignado debe existir previamente. |
| `valor_inicial` | `numeric` | Máximo 8 dígitos enteros y 2 decimales. |

---
**Tip de Mantenimiento:** Si el sistema de "Wizard" falla, asegúrate de que el driver de sesión en el `.env` sea `file` o `database` para soportar el almacenamiento de los datos temporales del equipo.