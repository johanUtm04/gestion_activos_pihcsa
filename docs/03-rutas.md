# 🛣️ 3. Mapa de Rutas y Navegación

> **Nota para el futuro Ingeniero:**
> Las rutas son las arterias del sistema. Están organizadas por **Módulos Semánticos** y protegidas por el Middleware `auth`. Si planeas agregar una funcionalidad nueva, asegúrate de seguir la convención de nombres en español para mantener la consistencia con la interfaz de usuario.

---

## 🛰️ 3.1 Convenciones y Seguridad

El sistema utiliza el motor de enrutamiento de Laravel para gestionar el tráfico bajo tres principios:
1.  **Protección Centralizada:** Todas las rutas (excepto login) requieren una sesión activa (`auth`).
2.  **Identificadores Híbridos:** Usamos IDs incrementales para registros persistentes y **UUIDs** para el flujo volátil del Wizard.
3.  **Nomenclatura Descriptiva:** URLs en español que reflejan la acción del controlador.



---

## 📋 3.2 Catálogo de Endpoints

### 🔐 Autenticación y Sistema
| Método | Ruta | Acción | Descripción |
| :--- | :--- | :--- | :--- |
| `GET` | `/` | `Closure` | Pantalla de Login (Raíz). |
| `GET` | `/dashboard` | `Closure` | Panel de métricas e inicio. |

### 💻 Módulo: Gestión de Equipos (CRUD)
| Método | Ruta | Controlador | Descripción |
| :--- | :--- | :--- | :--- |
| `GET` | `/equipos` | `EquipoController@index` | Inventario global. |
| `POST` | `/equipos` | `EquipoController@store` | Inicia persistencia de activo. |
| `GET` | `/equipos/{equipo}/edit` | `EquipoController@edit` | Formulario de edición. |
| `PUT` | `/equipos/{equipo}` | `EquipoController@update` | Actualización masiva. |
| `DELETE` | `/equipos/{equipo}` | `EquipoController@destroy` | Eliminación física. |

### 🧙‍♂️ Módulo: Wizard de Creación (Flujo Secuencial)
*Estas rutas dependen de un `{uuid}` temporal en sesión para garantizar la integridad del registro.*

| Método | Ruta | Acción | Descripción |
| :--- | :--- | :--- | :--- |
| `GET` | `/equipos/wizard/create` | `create` | **Paso 1:** Datos Base. |
| `GET/POST` | `/equipos/{uuid}/ubicacion` | `ubicacionForm` | **Paso 2:** Localización. |
| `GET/POST` | `/equipos/{uuid}/monitores` | `monitoresForm` | **Paso 3:** Pantallas. |
| `GET/POST` | `/equipos/{uuid}/discoduro` | `discoduroForm` | **Paso 4:** Almacenamiento. |
| `GET/POST` | `/equipos/{uuid}/ram` | `ramForm` | **Paso 5:** Memoria. |
| `GET/POST` | `/equipos/{uuid}/periferico` | `perifericoForm` | **Paso 6:** Accesorios. |
| `GET/POST` | `/equipos/{uuid}/procesador` | `procesadorForm` | **Paso 7:** Finalización. |

### 🛠️ Mantenimiento y Auditoría
| Método | Ruta | Controlador | Descripción |
| :--- | :--- | :--- | :--- |
| `GET` | `/equipos/{equipo}/addwork` | `EquipoController` | Vista de mantenimiento. |
| `POST` | `/equipos/{equipo}/addwork` | `EquipoController` | Registrar acción técnica. |
| `GET` | `/historial` | `HistorialController` | Bitácora global de logs. |

### 🏢 Administración de Catálogos
| Módulo | Ruta Base | Controlador | Permiso |
| :--- | :--- | :--- | :--- |
| **Usuarios** | `/gestionUsuarios` | `GestionUsuariosController` | Solo ADMIN. |
| **Ubicaciones**| `/gestionUbicaciones`| `GestionUbicacionesController`| ADMIN / Sistemas. |
| **Finanzas** | `/depreciacion` | `DepreciacionController` | Contabilidad. |

---

## 🛡️ 3.3 Niveles de Acceso (Middleware)

El acceso está segmentado mediante roles en la base de datos, lo que afecta la visibilidad de las rutas:

1.  **ADMIN:** Posee el "Master Key". Puede acceder a todas las rutas de gestión de usuarios y configuraciones globales.
2.  **SISTEMAS:** Enfocado en la operatividad técnica (Wizard y Mantenimiento). No tiene acceso a la eliminación de logs ni gestión de personal.
3.  **CONTABILIDAD:** Acceso especializado al módulo de `/depreciacion` y reportes de auditoría de solo lectura.

---

## 🔀 3.4 Flujo de Navegación Típico
1.  **Login** ➔ **Dashboard**
2.  **Dashboard** ➔ **Equipos** (Consulta rápida)
3.  **Equipos** ➔ **Wizard** (Si se requiere un alta nueva)
4.  **Wizard (Pasos 1 al 7)** ➔ **Equipos** (Redirección automática a la página del nuevo registro).

---
**Tip de Mantenimiento:** Para depurar rutas desde la consola, utiliza el comando `php artisan route:list --path=equipos`. Esto te mostrará únicamente los endpoints relacionados con el inventario.