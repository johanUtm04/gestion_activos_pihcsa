# 🏗️ 2. Arquitectura del Sistema

> **Nota para el futuro Ingeniero:**
> Estás ante una arquitectura desacoplada diseñada para la integridad. El sistema no solo separa la lógica de la vista (MVC), sino que utiliza un flujo de persistencia diferida (Wizard) para asegurar que un activo solo nazca en la base de datos cuando todos sus componentes críticos estén validados.

---

## 🛰️ 2.1 Visión General

El sistema de Gestión de Activos de **PIHCSA** es una **aplicación web empresarial** robusta. Su arquitectura está optimizada para transformar el ciclo de vida de un activo —desde su adquisición hasta su depreciación— en un flujo de datos auditable y seguro.

---

## 🏛️ 2.2 Patrón de Diseño: MVC (Laravel)

La aplicación implementa el patrón **Modelo-Vista-Controlador**, garantizando que el mantenimiento del hardware no interfiera con la lógica de presentación.

* **📦 Modelos (Models):** El ADN del sistema. Gestionan no solo las tablas, sino las relaciones complejas (`HasMany`, `BelongsTo`) entre el equipo y sus componentes (RAM, Discos, etc.).
* **🎮 Controladores (Controllers):** El cerebro operativo. Aquí reside la lógica de negocio, como el cálculo de páginas de retorno y la orquestación del Wizard.
* **🎨 Vistas (Views):** Motor **Blade** + **AdminLTE**. Proporcionan una interfaz administrativa de alto rendimiento, responsiva y clara para el usuario final.

---

## 🔄 2.3 Flujo Lógico de la Aplicación

El sistema sigue una secuencia de eventos diseñada para prevenir la entrada de "datos basura":

1.  **🔐 Autenticación:** Verificación de identidad y rol (Admin/Sistemas).
2.  **📝 Captura (Wizard):** Recolección secuencial de datos técnicos en sesión volátil.
3.  **🛡️ Validación:** Reglas de negocio estrictas antes de la escritura en disco.
4.  **💾 Persistencia:** Escritura atómica en **MySQL**.
5.  **📜 Auditoría:** Registro automático en `historiales_log` (El "Black Box" del activo).
6.  **📢 Feedback:** Notificaciones visuales de éxito/error mediante componentes de sesión.

---

## 🧩 2.4 Módulos Core (Componentes Principales)

| Módulo | Descripción Técnica |
| :--- | :--- |
| **Wizard de Activos** | Registro incremental que asocia componentes periféricos de forma dinámica. |
| **RBAC (Roles & Permissions)** | Control de acceso basado en el perfil del usuario (Seguridad Laravel). |
| **Motor de Auditoría** | Registro JSON de cambios para reconstrucción histórica de activos. |
| **Gestión Técnica** | Módulo de seguimiento para mantenimientos preventivos y correctivos. |

---

## 📊 2.5 Arquitectura de Datos y Seguridad

### Estrategia de Datos
El sistema utiliza **MySQL** con un diseño altamente normalizado.
* **Integridad Referencial:** Uso de llaves foráneas con reglas `ON DELETE SET NULL` y `CASCADE` según la criticidad del componente.
* **Escalabilidad:** Estructura preparada para el crecimiento de tablas de componentes sin afectar la tabla maestra de equipos.

### Capa de Seguridad
* **Protección de Rutas:** Middleware de autenticación para evitar accesos no autorizados.
* **Sanitización:** Validación de entradas para prevenir inyecciones SQL y ataques XSS.
* **Hashing:** Encriptación de credenciales mediante algoritmos de grado industrial (Bcrypt).

---

## 🌐 2.6 Entorno de Despliegue (Stack Lemp/Lamp)

La aplicación está diseñada para ser agnóstica al sistema operativo del cliente pero optimizada para servidores **Linux**.

* **Runtime:** PHP 8.2+ (Optimizado para el manejo de colecciones y tipos).
* **Web Server:** Nginx / Apache.
* **Database:** MySQL 8.0+.
* **Entornos:** Configuración mediante archivos `.env` para separar Desarrollo, Testing y Producción.

---
**Tip de Mantenimiento:** Si la carga de usuarios aumenta, la arquitectura MVC de Laravel permite migrar la base de datos a un servidor independiente o implementar Redis para el manejo de las sesiones del Wizard sin cambiar el código core.