# 🎮 GestionUsuariosController

> **Nota para el futuro Ingeniero:**
> Este controlador centraliza la administración de accesos al sistema. Un detalle crítico aquí es el uso de la constante `PER_PAGE` y el método helper `getReturnPage`. Están diseñados para que, tras añadir, editar o borrar un usuario, el sistema te devuelva exactamente a la página de la tabla donde estabas, evitando que el administrador pierda el foco de navegación.

## 📌 Responsabilidades
* Registro de nuevos operadores del sistema con contraseñas encriptadas.
* Gestión de perfiles: Roles, Departamentos y Estatus (ACTIVO/INACTIVO).
* Control de paginación inteligente para la interfaz administrativa.
* Validación de unicidad de correos electrónicos para evitar duplicidad de cuentas.

---

## 🛠️ Métodos Principales

### `index()`
Muestra el listado de usuarios.
* **Paginación**: Utiliza la constante `PER_PAGE` fijada en **10 registros** para mantener una interfaz limpia.

### `store(Request $request)`
Crea un nuevo usuario en la base de datos.
* **Seguridad**: Implementa `Hash::make` para encriptar la contraseña antes de la persistencia.
* **Validación**: Verifica que el `email` sea único en la tabla `users` y que la contraseña coincida con su confirmación (`confirmed`).
* **Retorno**: Redirige a la página correspondiente del nuevo registro usando el helper de posición.

### `update(Request $request, User $user)`
Actualiza la información del perfil.
* **Validación dinámica**: Al validar el email, ignora el ID del usuario actual para permitir guardar cambios sin error de "email ya existe" si el correo no fue modificado.

### `destroy(User $user)`
Elimina la cuenta de usuario.
* **Cálculo preventivo**: Calcula la página de retorno **antes** de ejecutar el borrado para asegurar que la redirección sea coherente con los registros restantes.

---

## 🔧 Lógica de Helper Functions (Privadas)

### `getReturnPage($userId)`
Este método es vital para la Experiencia de Usuario (UX) administrativa:
1. Cuenta cuántos registros existen con un ID menor o igual al afectado.
2. Divide ese total entre el número de registros por página (`PER_PAGE`).
3. Aplica `ceil()` para obtener el número de página exacto.

---

## 📝 Reglas de Validación (User Management)

| Campo | Regla | Descripción |
| :--- | :--- | :--- |
| `email` | `email \| unique` | Debe tener formato de correo y no existir en la DB.
| `password` | `min:8 \| confirmed` | Mínimo 8 caracteres y debe coincidir con el campo de confirmación.
| `rol` | `required` | Define el nivel de acceso (ADMIN, SISTEMAS, etc.).
| `estatus` | `required` | Define si el usuario puede loguearse (ACTIVO/INACTIVO).

---
**Tip de Mantenimiento:** Si decides cambiar el tamaño de las tablas en el frontend, solo necesitas modificar el valor de `const PER_PAGE` en este controlador y el sistema recalculará todas las redirecciones automáticamente.