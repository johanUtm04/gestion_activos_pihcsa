# 🎮 HistorialController

> **Nota para el futuro Ingeniero:**
> Este controlador es el "Auditor" del sistema. Su función no es crear datos, sino permitir la consulta de la línea de tiempo de cada activo. La lógica aquí es bidireccional: puedes ver el historial general o filtrar equipos específicos para ver sus eventos (Mantenimientos, Cambios de Usuario, etc.). Un punto técnico clave es el filtrado a través de relaciones anidadas.

## 📌 Responsabilidades
* Centralizar la consulta de logs y eventos del sistema.
* Gestionar filtros complejos que cruzan las tablas de `historiales_log` con `equipos`.
* Proveer una vista de auditoría detallada para la toma de decisiones basada en el uso de los activos.

---

## 🛠️ Métodos Principales

### `index(Request $request)`
Gestiona la visualización de la bitácora con capacidades de filtrado dinámico.
* **Eager Loading**: Carga `equipo` y `usuario` de entrada para evitar el problema de N+1 consultas al listar los logs.
* **Filtrado por Registro**: Permite aislar tipos de eventos específicos (ej. "MANTENIMIENTO").
* **Filtro Avanzado (`whereHas`)**: 
    * Realiza una búsqueda "especial": filtra los registros de historial basándose en atributos de una tabla relacionada (`equipos`). 
    * Ejemplo: "Tráeme todos los logs, pero solo de aquellos que sean del tipo 'Laptop'".
* **Consolidación de Datos**: Recupera equipos junto con sus historiales y usuarios relacionados de "un solo golpe" para la vista.

---

## 🔍 Lógica de Filtrado Especial

```php
// Filtrar historiales basados en una columna de la tabla relacionada (Equipos)
if ($request->filled('tipo_equipo')) {
    $query->whereHas('equipo', function($q) use ($request) {
        $q->where('tipo_equipo', $request->tipo_equipo);
    });
}