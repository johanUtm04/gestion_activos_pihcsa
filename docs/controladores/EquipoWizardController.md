# 🎮 EquipoWizardController

> **Nota para el futuro Ingeniero:**
> Este controlador orquesta un flujo de registro dividido en 7 pasos (Wizard). La clave aquí es que **nada se guarda en la base de datos hasta el paso final**. Los datos viajan en la "mochila" de la sesión (`wizard_equipo`) identificados por un **UUID**. Si el usuario cierra el navegador o la sesión expira antes del paso 7, la base de datos se mantiene limpia de registros incompletos.

## 📌 Flujo del Wizard (User Journey)
El registro sigue este orden estrictamente:
1. **Datos Base** (Equipo) -> 2. **Ubicación** -> 3. **Monitores** -> 4. **Discos Duros** -> 5. **RAM** -> 6. **Perifericos** -> 7. **Procesador** (Finalización).

---

## 🛠️ Lógica de Persistencia Temporal (Sesión)

### 🔑 Generación de UUID
En el método `store()`, se genera un `Str::uuid()`. Este identificador se vuelve un requisito de seguridad para todas las rutas siguientes (`ubicacionForm($uuid)`, `ramForm($uuid)`, etc.). Si el UUID de la URL no coincide con el de la sesión, el sistema lanza un `abort(403)`.

### 🧹 Limpieza de Datos (`array_filter`)
En cada paso de guardado intermedio (ej. `saveMonitor`, `saveRam`), se utiliza `array_filter($request->only(...))`. 
* **Propósito**: Si el usuario deja los campos de un componente vacíos (porque el equipo no tiene ese componente), el sistema elimina esa clave de la sesión en lugar de guardar un registro vacío.

---

## 🚀 Método Maestro: `saveProcesador(Request $request, $uuid)`

Este es el método de **Consolidación**. Realiza las siguientes acciones en orden:

1. **Recolección Final**: Guarda los datos del procesador en la sesión.
2. **Creación del Padre**: Ejecuta `Equipo::create(...)` usando el operador *spread* (`...$wizard['equipo']`) para inyectar todos los datos base de un solo golpe.
3. **Creación de Hijos (Relaciones)**:
    * Verifica cada apartado de la sesión (`monitor`, `ram`, `disco_duro`, etc.).
    * Si existen datos, dispara el método `create()` a través de la relación de Eloquent (ej. `$equipo->monitores()->create(...)`).
4. **Finalización de Sesión**: Ejecuta `session()->forget('wizard_equipo')` para liberar memoria del servidor.
5. **Redirección UX**: Calcula la página exacta donde quedó el nuevo equipo para que el usuario lo vea resaltado inmediatamente.

---

## 🛡️ Seguridad y Validaciones

| Paso | Validación Clave | Nota Técnica |
| :--- | :--- | :--- |
| **1. Equipo** | `exists:users,id` | Asegura que el responsable asignado sea válido. |
| **2. Ubicación** | `exists:ubicaciones,id` | Obliga a que el equipo tenga un lugar físico real. |
| **Global** | `UUID Match` | Evita que se inyecten datos en sesiones de otros usuarios. |

---

## 📊 Estructura de la Sesión `wizard_equipo`

```json
{
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "equipo": { "marca": "HP", "serial": "...", "usuario_id": 1 },
  "ubicacion": { "ubicacion_id": 5 },
  "monitor": { "marca": "LG", "pulgadas": "24" },
  "ram": { "capacidad_gb": "16", "tipo_chz": "DDR4" },
  "...": "..."
}