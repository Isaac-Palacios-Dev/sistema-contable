# Sistema Contable

Sistema contable simple, en PHP puro con patrón MVC hecho a mano (sin framework), pensado para correr en local con Laragon.

## Qué incluye

- **Login** con sesiones y contraseñas cifradas (bcrypt).
- **Catálogo de cuentas**: crear, editar, desactivar. Tipos (activo, pasivo, capital, ingreso, gasto) y naturaleza (deudora/acreedora).
- **Asientos contables** por partida doble: el formulario no deja guardar si el debe y el haber no cuadran (validado con Vue en el navegador y de nuevo en el servidor).
- **Libro mayor** por cuenta, con saldo acumulado.
- **Balance de comprobación**, a una fecha de corte.
- **Estado de resultados** simplificado (ingresos - gastos), por rango de fechas.

## Stack

- **Backend:** PHP 8.3, PDO con MySQL, MVC propio (sin Composer, sin dependencias).
- **Frontend:** Bootstrap 5 (estilos) + Vue 3 (la parte dinámica del formulario de asientos), ambos por CDN. No hace falta `npm install` ni compilar nada.

## Cómo correrlo en local (con Laragon)

1. **Prender MySQL** desde Laragon.
2. **Crear la base de datos** importando el esquema:
   ```
   C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe -u root < database/schema.sql
   ```
   (o abrí `database/schema.sql` desde HeidiSQL / phpMyAdmin de Laragon y ejecutalo ahí).
3. **Configurar el entorno**: copiá `.env.example` a `.env`. Los valores por defecto ya funcionan con Laragon (usuario `root`, sin contraseña).
4. **Levantar el servidor**, desde la carpeta del proyecto:
   ```
   php -S localhost:8000 -t public
   ```
5. Abrí **http://localhost:8000** en el navegador.

### Usuario de prueba

- Correo: `admin@contable.local`
- Contraseña: `Admin123*`

## Estructura

```
app/
  Core/         Router, Database, Controller y Model base (el "framework" propio)
  Controllers/  Un controlador por sección
  Models/       Un modelo por tabla, con la lógica contable en Asiento.php
  Views/        Plantillas PHP, con layout.php como envoltorio común
config/         Lee el .env y arma la configuración
database/       schema.sql con las tablas y datos iniciales
public/         Punto de entrada (index.php) y archivos públicos
```

## Nota

Proyecto hecho como pieza de portafolio individual, con fines de aprendizaje y referencia laboral.
