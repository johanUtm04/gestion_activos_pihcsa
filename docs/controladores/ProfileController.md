# 🎮 ProfileController

> **Nota para el futuro Ingeniero:**
> Este controlador gestiona la seguridad y los datos personales del usuario autenticado. A diferencia de `GestionUsuariosController`, aquí el usuario solo tiene poder sobre sus propios datos. La seguridad se refuerza mediante un "Closure" de validación que obliga a verificar la identidad antes de permitir cualquier cambio crítico.

## 📌 Responsabilidades
* Visualización del formulario de perfil del usuario en sesión.
* Actualización segura de credenciales (Nombre, Email y Contraseña).
* Gestión del cierre de cuenta y limpieza de sesiones activas.

---

## 🛠️ Métodos Principales

### `edit(Request $request)`
Carga la vista de edición.
* **Contexto**: Retorna los datos del usuario que dispara la petición mediante `$request->user()`.

### `update(Request $request)`
Procesa los cambios del perfil con lógica de validación manual:
* **Verificación de Identidad**: Implementa una función anónima (Closure) en `current_password` que usa `Hash::check` para comparar lo ingresado con el hash de la base de datos.
* **Cambio de Password Condicional**: 
    * Si el campo `password` está vacío, se elimina del arreglo de datos (`unset`) para no sobreescribir la contraseña actual con un valor nulo.
    * Si contiene datos, se aplica `Hash::make` y se cumplen las reglas de `Password::defaults()` de Laravel Breeze.
* **Email Único**: Valida que el correo no esté duplicado, pero ignora el ID del usuario actual para permitir mantener el mismo email.

### `destroy(Request $request)`
Elimina la cuenta del usuario actual:
* **Seguridad**: Requiere la contraseña actual para confirmar la acción.
* **Limpieza**: Tras eliminar el registro, invalida la sesión y regenera el token CSRF para evitar ataques de fijación de sesión antes de redirigir al inicio.

---

## 🛡️ Lógica de Validación Especial

```php
// Validación personalizada de la contraseña actual
'current_password' => ['required', function($attribute, $value, $fail) use ($user){
    if (!Hash::check($value, $user->password)) {
        $fail("El campo Contraseña Actual es incorrecto");
    }
}],