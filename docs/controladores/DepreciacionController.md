# 🎮 DepreciacionController

> **Nota para el futuro Ingeniero:**
> Este controlador está destinado a manejar la lógica financiera de los activos. Actualmente funciona como un visor de activos para contabilidad, pero su propósito final es calcular la pérdida de valor de los equipos en función de la `fecha_adquisicion`, el `valor_inicial` y la `vida_util_estimada`. 

## 📌 Responsabilidades
* Centralizar la consulta de activos para fines contables y de auditoría.
* Proveer la interfaz necesaria para el cálculo de depreciación lineal o acelerada.

---

## 🛠️ Métodos Principales

### `index(Request $request)`
Muestra el listado de activos con enfoque financiero.
* **Paginación**: Configurada a **10 registros** para visualización de tablas de datos extensas.
* **Propósito actual**: Servir de punto de entrada para que el departamento de contabilidad revise los valores base de cada equipo.

---

## 🚀 Hoja de Ruta para el Futuro Ingeniero (Implementación Contable)

Cuando llegue el momento de implementar los cálculos, considera lo siguiente:

1. **Lógica de Cálculo**: Deberás usar el campo `valor_inicial` y restarle el valor de salvamento (si aplica) dividido entre la `vida_util_estimada`.
2. **Helper de Tiempo**: Usa la librería `Carbon` para calcular la diferencia en meses entre la `fecha_adquisicion` y la fecha actual.
3. **Fórmula Sugerida (Depreciación Lineal)**:
   ```php
   // Ejemplo conceptual
   $mesesTranscurridos = $equipo->fecha_adquisicion->diffInMonths(now());
   $depreciacionMensual = $equipo->valor_inicial / ($equipo->vida_util_estimada * 12);
   $valorActual = $equipo->valor_inicial - ($depreciacionMensual * $mesesTranscurridos);