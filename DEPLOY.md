# Deploy de Alquil-Ar en InfinityFree

Guía para subir el proyecto a InfinityFree (hosting gratuito, sin acceso SSH).
Pensada para alguien que nunca hizo un deploy — cada paso dice el *por qué*,
no solo el *qué*.

## Por qué esto no es "copiar y pegar la carpeta"

InfinityFree, como la mayoría del hosting compartido gratuito, tiene dos
restricciones que cambian cómo se sube el proyecto:

1. **No hay acceso por consola (SSH).** Todo lo que acá hacíamos con
   `php spark migrate` o `composer install` hay que resolverlo de otra forma:
   la base se importa por phpMyAdmin, y las dependencias de Composer se
   generan en otra máquina y se suben ya armadas.
2. **La carpeta pública del sitio (`htdocs`) no se puede cambiar.** En este
   proyecto, CodeIgniter espera que el "document root" del servidor apunte a
   `public/`, no a la raíz del proyecto. Como en InfinityFree `htdocs` es fijo
   y no se puede apuntar a una subcarpeta, el proyecto entero (incluido
   `app/`, `system/`, `.env`) termina viviendo adentro de la carpeta pública.
   Eso es un riesgo de seguridad real si no se bloquea bien — por eso el
   `.htaccess` de la raíz de este repo existe y hace ese trabajo.

## Paso 1 — Crear la cuenta y la base de datos

1. Entrá a https://app.infinityfree.com/signup y registrate con tu email.
2. Elegí el subdominio gratuito (en nuestro caso: `alquilar.site.je`).
3. Una vez que el panel esté listo, andá a **MySQL Databases** y creá una
   base nueva. Anotá los 4 datos que te va a mostrar: **hostname**, **nombre
   de la base**, **usuario** y **contraseña**. El hostname casi nunca es
   `localhost` acá — suele ser algo como `sqlXXX.infinityfree.com`.

## Paso 2 — Preparar lo que se sube (ya armado en este repo)

No todo lo que usamos en desarrollo va al servidor:

- **NO subas la carpeta `vendor/` que tenés localmente.** Esa trae PHPUnit y
  herramientas de testing — pesa varios gigas, mucho más que el límite de
  almacenamiento del plan gratis. Para producción se genera una versión
  mínima con `composer install --no-dev`, que en este proyecto pesa
  apenas un par de MB (solo dos paqueticos chicos que CodeIgniter necesita
  para correr: `laminas/laminas-escaper` y `psr/log`).
- **NO subas `.git/`, `node_modules/` (no aplica acá) ni archivos de test.**

Lo que sí viaja: todo el resto del proyecto (`app/`, `system/`, `public/`,
`writable/`, `.htaccess` de la raíz, `spark`, `composer.json`) más la carpeta
`vendor/` de producción.

## Paso 3 — Subir los archivos

Por FTP (los datos de FTP están en el panel de InfinityFree, sección
**Account Details**) o por el **File Manager** del panel (más simple para
una primera vez, no necesita instalar ningún cliente FTP):

1. Entrá a `htdocs/` en el File Manager.
2. Subí ahí adentro todo el contenido del proyecto, manteniendo la
   estructura de carpetas tal cual está en el repo (`app/`, `system/`,
   `public/`, `writable/`, `.htaccess`, `spark`, `composer.json`,
   `composer.lock`).
3. Subí también la carpeta `vendor/` de producción (la mínima, no la de
   desarrollo).

## Paso 4 — Crear el `.env` en el servidor

Dentro de `htdocs/`, al lado de `app/` y `system/`, creá un archivo nuevo
llamado exactamente `.env` (con el punto, sin extensión). El contenido te lo
armé en tu sesión — tiene la base de `app.baseURL` ya apuntando a
`http://alquilar.site.je/` y los campos de la base de datos para que
completes con lo que anotaste en el Paso 1.

**Nunca subas este archivo al repositorio de GitHub** — tiene la contraseña
de la base de datos en texto plano, por eso `.gitignore` ya lo excluye.

## Paso 5 — Cargar el esquema de la base de datos

Como no hay consola, `php spark migrate` no se puede correr ahí. En cambio:

1. En el panel de InfinityFree, abrí **phpMyAdmin** (ícono en el Control
   Panel) y entrá a la base que creaste en el Paso 1.
2. Pestaña **Import** → elegí el archivo `alquilar_esquema.sql` que te
   armé (es solo la estructura de las tablas, sin usuarios ni herramientas
   de prueba — la base arranca vacía, como tiene que ser en producción).
3. Click en **Go**. Deberían aparecer 5 tablas: `usuarios`, `herramientas`,
   `alquileres`, `pagos`, `migrations`.

## Paso 6 — Probar

Entrá a `http://alquilar.site.je/`. Si ves el catálogo (vacío, está bien,
todavía no hay nada publicado), funcionó. Si da error 500 o 404, mirá la
sección de problemas conocidos más abajo.

Probá el flujo completo una vez ahí: registrarte, publicar una herramienta
con foto, reservarla con otra cuenta — los mismos pasos que probamos acá en
desarrollo.

## Verificar extensiones de PHP

El proyecto necesita `ext-intl` y `ext-mbstring`. En el panel de
InfinityFree, buscá el selector de versión/configuración de PHP y confirmá
que ambas estén activas (si no aparecen activables ahí, avisame el mensaje
de error exacto que te tire el sitio y lo vemos).

## Problemas conocidos / cosas para probar en vivo

- **El `.htaccess` de la raíz usa un truco bien conocido de CodeIgniter para
  hosting compartido** (reescribir todo hacia `public/` sin poder cambiar el
  document root), pero no lo pude probar contra un Apache real — acá solo
  tengo el servidor de desarrollo de PHP, que ignora los `.htaccess`
  totalmente. Es el patrón estándar y documentado por el propio framework,
  así que debería andar, pero si al entrar a `alquilar.site.je` te tira 404
  en todo excepto la portada, avisame con la URL exacta que falla.
- **HTTPS**: dejé `app.baseURL` en `http://` porque no sé si InfinityFree ya
  tiene el certificado SSL activo para tu subdominio recién creado (a veces
  tarda). Una vez que confirmes que `https://alquilar.site.je/` carga sin
  advertencia del navegador, cambiamos esa línea a `https://` en el `.env`.
- **Fotos subidas**: `public/uploads/herramientas/` necesita permisos de
  escritura para que `Herramientas::crear()` pueda guardar ahí. Si al
  publicar con foto te da error, puede ser un tema de permisos de esa
  carpeta — avisame.
