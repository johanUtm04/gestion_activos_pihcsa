# 🎮 GestionUbicacionesController

> **Nota para el futuro Ingeniero:**
> Este controlador gestiona los puntos físicos donde residen los activos. Al igual que en la gestión de usuarios, aquí se implementa la lógica de **posicionamiento inteligente**. Un detalle importante es que el `PER_PAGE` está seteado en **3**, lo cual es ideal para pruebas de paginación o interfaces con tarjetas grandes; si la lista crece, ajusta la constante al inicio del archivo.

## 📌 Responsabilidades
* Administración del catálogo de sedes, oficinas o almacenes.
* Control de códigos únicos por ubicación para evitar ambigüedad en el inventario.
* Mantenimiento de la integridad referencial (los equipos dependen de estas IDs).

---

## 🛠️ Métodos Principales

### `index()`
Lista todas las ubicaciones registradas.
* **Paginación**: Utiliza `self::PER_PAGE` (actualmente 3) para segmentar la lista.

### `store(Request $request)`
Registra una nueva ubicación.
* **Validación de Código**: El campo `codigo` es **único**. Esto es vital para integrar el sistema con etiquetas de inventario físico o códigos de barras en el futuro.
* **UX de Inserción**: Tras guardar, el sistema calcula en qué página quedó la nueva ubicación y te redirige ahí, resaltando el nuevo registro.

### `update(Request $request, Ubicacion $ubicacion)`
Actualiza los datos de una sede existente.
* **Excepción de Unicidad**: La validación del `codigo` permite que el registro actual conserve su código sin disparar un error de duplicidad, pero impide que use uno que ya pertenezca a otra ubicación.

### `destroy(Ubicacion $ubicacion)`
Elimina el registro de la sede.
* **Integridad**: Antes de borrar, el controlador calcula la página de retorno para que el administrador no pierda su lugar en la lista tras la recarga.

---

## 🔧 Lógica de Posicionamiento (`getReturnPage`)

Este método privado es el estándar de navegación que definiste para este proyecto:
1. Cuenta cuántos registros existen con un ID menor o igual al procesado (`where('id', '<=', $id)`).
2. Divide el conteo por la constante de paginación.
3. El resultado redondeado hacia arriba (`ceil`) es la página de destino.

---

## 📝 Reglas de Validación

| Campo | Regla | Descripción |
| :--- | :--- | :--- |
| `nombre` | `required \| string` | Nombre descriptivo (ej. "Oficina Central", "Planta 1"). |
| `codigo` | `unique \| max:255` | Identificador alfanumérico único para logística interna. |

---
**Tip de Mantenimiento:** Si en el futuro una ubicación no se puede borrar, revisa las llaves foráneas en la base de datos. Actualmente, la tabla `equipos` está configurada para poner el campo `ubicacion_id` en `NULL` si borras la ubicación, evitando errores de restricción.