SIS3M – Mini ERP de Gestión.

SIS3M es un mini ERP web pensado para centralizar la gestión de un taller / servicio técnico, integrando clientes, stock, servicios y uso de vehículos.  
Está desarrollado con PHP (backend), MySQL y HTML/JS/CSS (frontend), siguiendo una separación clara entre lógica de servidor y capa de presentación.

Todos los datos de ejemplo incluidos en la base de datos son ficticios y se utilizan solo con fines de prueba y desarrollo.

===== OBJETIVO DEL SISTEMA =====

El sistema busca unificar en una sola aplicación:

- La gestión de clientes y sus datos de contacto.
- El catálogo de productos y el stock en distintas ubicaciones.
- El circuito de servicios técnicos (trabajos, intervenciones, materiales utilizados).
- El uso de vehículos por parte del personal.
- La administración de usuarios y roles del sistema.

===== FUNCIONALIDADES PRINCIPALES =====

----- Clientes -----

- Alta, baja y edición de clientes (personas y empresas).
- Múltiples direcciones por cliente.
- Múltiples contactos por cliente (teléfonos, emails, etc.).
- Sistema de tags para clasificar clientes (rubro, prioridad, tipo, etc.).

----- Productos y stock -----

- Catálogo de productos con:
 Categoría, marca, unidad de medida.
 Proveedor principal.
 Ubicación física (depósito, estantería, sector, etc.).

- Gestión de stock actual por producto.

- Registro histórico de movimientos de stock:
 Entrada / salida / transferencia.
 Cantidades, costos unitarios y totales.
 Origen y destino.
 Usuario responsable.
 Referencia al origen del movimiento (trabajo, ajuste, compra, etc.).

- Manejo de reservas de stock asociadas a otros módulos (por ejemplo, trabajos).

----- Servicios técnicos -----

- Gestión de trabajos:
 Cliente, ubicación, prioridad, estado, descripción, fechas de alta y cierre.

- Registro de intervenciones:
 Técnico asignado, fechas y horas, duración, observaciones.
 Materiales utilizados (vinculados a productos del stock).
 Campos preparados para firmas y comprobantes.

----- Flota de vehículos -----

- ABM de vehículos (patente, modelo, año, estado, etc.).
- Definición de roles habilitados para usar cada vehículo.

- Registro de usos de vehículo:
 Usuario que retira y devuelve.
 Fecha y hora de salida y regreso.
 Km de salida y regreso.
 Motivo y destino del viaje.
 Observaciones y cierre del uso.

----- Usuarios y roles -----

- Gestión de usuarios del sistema.
- Roles con permisos diferenciados (concepto de perfiles de acceso).
- Campos de auditoría en varias tablas (creado por, actualizado por, timestamps).

---

===== ARQUITECTURA DEL PROYECTO =====

El proyecto está organizado en dos grandes capas:

----- backend (Lado servidor) -----
- Módulos PHP organizados por entidad (clientes, productos, trabajos, vehículos, etc.).
- Acceso a datos mediante PDO y consultas preparadas.
- Endpoints que responden JSON para ser consumidos desde el frontend.
- Configuración de conexión a base de datos en `backend/config/db.php`.

----- frontend/ (Lado cliente) -----
- Páginas HTML para cada módulo (clientes, usuarios, productos, trabajos, etc.).
- JavaScript modular por pantalla (uso de `fetch` y `FormData` para consumir la API).
- CSS propio con variables y layout tipo dashboard.


Estructura simplificada del repo:

/
├── backend/
│   ├── config/
│   ├── modules/
│   └── ... (scripts PHP por entidad / funcionalidad)
├── frontend/
│   ├── html/
│   ├── js/
│   └── css/
├── old_scripts/        # Versión anterior / legacy (no usada en la versión actual)
└── trimod_bdd.sql      # Script con el modelo de base de datos y datos de prueba
