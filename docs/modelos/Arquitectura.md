# 🧬 Documentación de Modelos y Arquitectura de Datos (Eloquent)

Este documento detalla la lógica de las clases de Eloquent que gestionan la persistencia de datos en el sistema **PIHCSA**. La arquitectura se basa en un modelo centralizado de activos con componentes modulares.

---

## 🏗️ 1. Esquema de Relaciones (Estructura de Estrella)

El sistema utiliza un diseño donde el modelo **Equipo** actúa como el núcleo, y los componentes de hardware, usuarios y ubicaciones orbitan a su alrededor mediante relaciones de integridad referencial.



---

## 💻 2. El Modelo Central: `Equipo`
Es la entidad principal del inventario. Representa el activo físico y coordina todas las piezas de hardware asociadas.

* **Traits Especiales**: Utiliza `SoftDeletes` para permitir la "eliminación lógica", asegurando que los datos no se borren permanentemente sin supervisión.
* **Asignación Masiva**: El arreglo `$fillable` protege la integridad, permitiendo la inserción controlada de datos técnicos y financieros (`marca_equipo`, `serial`, `valor_inicial`, etc.).

### 🔗 Relaciones Administrativas (Pertenece a)
* `usuario()`: Conecta con `User` vía `usuario_id`. Define al responsable del resguardo.
* `ubicacion()`: Conecta con `Ubicacion` vía `ubicacion_id`. Define la sede física.

### 🔌 Relaciones de Hardware (Tiene muchos - 1:N)
El equipo funciona como un contenedor. Todas las tablas hijas dependen de la FK `equipo_id`:
* `monitores()`, `discosDuros()`, `rams()`, `perifericos()`, `procesadores()`.
* `historials()`: Conecta con los logs de auditoría mediante la FK `activo_id`.

---

## 📜 3. Trazabilidad: `Historial_log`
Gestiona la "Caja Negra" del sistema para auditorías.

* **Configuración**: Apunta explícitamente a la tabla `historiales_log`.
* **Manejo de JSON**: Utiliza `$casts` para convertir la columna `detalles_json` automáticamente en un **array de PHP**, permitiendo guardar cambios de hardware de forma dinámica.
* **Relaciones**:
    * `equipo()`: El activo afectado por el cambio.
    * `usuario()`: El administrador que ejecutó la acción (`usuario_accion_id`).

---

## 👤 4. Identidad y Acceso: `User`
Representa al personal técnico o administrativo.

* **Seguridad**: Gestiona contraseñas mediante hashing y maneja campos de contacto y departamento.
* **Relaciones**:
    * `equiposResponsables()`: Lista todos los equipos que el usuario tiene asignados bajo su firma.
    * `historialLogs()`: Rastrea qué registros ha creado o modificado este usuario en el sistema.

---

## 📍 5. Catálogo de Sedes: `Ubicacion`
Define los puntos geográficos o departamentos donde reside el hardware.

* **Relación Inversa**: Mediante `equipos()`, permite consultar qué activos se encuentran en una oficina específica para inventarios rápidos.

---

## 🛠️ 6. Diccionario de Componentes Satélites
Hardware específico que se ensambla de forma opcional al equipo principal.

| Modelo | Tabla | Características |
| :--- | :--- | :--- |
| **`DiscoDuro`** | `discos_duros` | Capacidad, tecnología (SSD/HDD), interfaz y serial. |
| **`Monitor`** | `monitores` | Registro de pantallas y números de serie. |
| **`Ram`** | `rams` | Módulos de memoria, capacidad y frecuencia. |
| **`Procesador`** | `procesadores` | Especificaciones de la CPU integrada. |
| **`Periferico`** | `perifericos` | Accesorios adicionales (teclados, mouses, etc.). |

---

## 💡 Notas Técnicas para el Ingeniero

1.  **Integridad de Datos**: Se utiliza un esquema mixto de `$fillable` (para inserción masiva) y `$guarded = ['id']` (para protección de llaves primarias).
2.  **Convención de Nombres**: Se define la propiedad `$table` manualmente en modelos críticos para mantener la consistencia con nombres en español pluralizado dentro de la base de datos MySQL.
3.  **Persistencia del Wizard**: El sistema de modelos está diseñado para soportar la creación incremental. El modelo `Equipo` debe existir antes de que los componentes satélites intenten asociarse mediante su `equipo_id`.

---
**Última Actualización:** 2026  