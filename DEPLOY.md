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

## ⚠️ Esta rama y el sitio en vivo NO están conectados

**Pushear a GitHub no actualiza `alquilar.site.je`.** Son dos cosas
totalmente separadas: esta rama (`integracion-completa-pendiente-revision`)
vive en GitHub, y el sitio en vivo corre con los archivos que estén,
físicamente, en `htdocs/` del File Manager de InfinityFree en ese momento
— lo que haya ahí es una fotografía de un commit puntual, tomada el día que
se hizo el deploy (26 oct 2026). No hay ningún webhook ni Action que
sincronice uno con otro.

Esto ya causó un bug real (28-29 oct 2026): el campo `capacidad` se arregló
en el código y se probó localmente, pero quedó guardando `NULL` en
producción durante un día entero porque nadie había resubido los archivos
modificados al servidor — el fix vivía en GitHub, no en InfinityFree.

**Regla práctica:** después de cualquier cambio a un `Controller`, `Model`
o vista con lógica PHP en esta rama, antes de darlo por "andando", hay que
resubir a mano los archivos tocados al `htdocs/` real (vía File Manager,
editando el archivo existente y reemplazando su contenido, o subiendo el
archivo nuevo). `git log` entre el último commit que sí se subió y `HEAD`
dice exactamente qué archivos faltan resubir (`git diff --stat
<ultimo-commit-deployado>..HEAD`).

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

   **Ojo si subís un `.zip` y lo extraés con la opción del File Manager:**
   el resultado final tiene que ser `htdocs/app/`, `htdocs/public/`, etc.
   — directo, sin ninguna carpeta contenedora de por medio. Si el zip
   que armaste (o el que bajaste de GitHub) tiene todo adentro de una
   carpeta como `Alquilar-integracion-completa-pendiente-revision/`, al
   extraerlo te va a quedar `htdocs/Alquilar-.../app/...`, un nivel de
   más. Con esa estructura el sitio tira **403 Forbidden** en `/` y
   también en `/public/`, porque Apache busca `index.php` directo en
   `htdocs/` y no lo encuentra ahí (esto pasó en el deploy real del 26
   oct 2026 y costó varias vueltas diagnosticarlo mal como permisos).
   Si pasa: entrá a esa carpeta contenedora, seleccioná todo su
   contenido, "Move" un nivel para arriba (a `htdocs/`), y después borrá
   la carpeta contenedora ya vacía.
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

> **Nota de algo que falló la primera vez (deploy real, 26 oct 2026):**
> `mysqldump` genera las tablas en orden alfabético, y las envuelve en
> `SET FOREIGN_KEY_CHECKS=0` para poder crear tablas con llaves foráneas
> que apuntan a otra tabla que todavía no existe (ej. `alquileres`, que
> arranca con "a", apunta a `herramientas` y `usuarios`, que vienen
> después en orden alfabético). **InfinityFree no respeta ese
> `SET FOREIGN_KEY_CHECKS=0`** — es un filtro de seguridad típico de
> hosting compartido gratuito — así que la importación tira
> `#1005 - Error: 150 "Foreign key constraint is incorrectly formed"` al
> llegar a `alquileres`. La solución, ya aplicada en el
> `alquilar_esquema.sql` que te compartí: las tablas están en orden de
> dependencia real (`usuarios` → `herramientas` → `alquileres` → `pagos`
> → `migrations`), no alfabético, así cada `CREATE TABLE` solo referencia
> tablas que ya existen. Si en algún momento se regenera este archivo con
> `mysqldump`, hay que pasarle los nombres de tabla en ese orden
> explícitamente (`mysqldump ... alquilar usuarios herramientas
> alquileres pagos migrations`), no dejarlo en orden alfabético por
> default.

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

## Cambios de esquema pendientes de aplicar en el sitio en vivo

El deploy real a InfinityFree (26 oct 2026) se hizo con el esquema de ese
momento. Si despues de esa fecha se agrega una migracion nueva (una columna,
una tabla), **no alcanza con pushear el codigo a GitHub**: como ahi no hay
consola, `php spark migrate` nunca corre solo. Hay que aplicar el cambio a
mano por phpMyAdmin, en la pestaña **SQL** de la base, o el sitio en vivo
va a tirar un error SQL ("Unknown column") en cuanto el codigo nuevo
intente leer o escribir esa columna.

- **Columna `capacidad` en `herramientas`** (agregada el 28 oct 2026, despues
  del deploy real — mismo tipo de bug que "categoria": el campo existia en el
  formulario pero nunca se guardaba). Mientras esto no se corra en la base de
  InfinityFree, publicar o editar una herramienta ahi va a fallar. Correr una
  sola vez en phpMyAdmin → pestaña SQL:

  ```sql
  ALTER TABLE herramientas ADD COLUMN capacidad VARCHAR(100) NULL AFTER precio_hora;
  ```

## Problemas conocidos / cosas para probar en vivo

- **El `.htaccess` de la raíz, confirmado funcionando** en el deploy real del
  26 oct 2026, con una correción: la regla final tiene que usar
  `/public/$1` (con la barra inicial), no `public/$1` — sin la barra, la
  URL raíz `/` daba 403 porque Apache no resolvía bien la ruta relativa.
  Ya está corregido en el repo. El otro 403 que salió en el mismo deploy
  no era del `.htaccess` sino de la carpeta contenedora de más al subir
  el zip (ver Paso 3).
- **HTTPS, confirmado y ya corregido** (29 oct 2026). `app.baseURL` se dejó
  en `http://` al principio porque no sabíamos si el SSL del subdominio ya
  estaba activo. Una vez confirmado que `https://alquilar.site.je/` carga
  sin advertencias, **hay que dejar `app.baseURL` en `https://` siempre**,
  no solo por prolijidad: InfinityFree tiene un sistema anti-bot que exige
  que cada pedido JavaScript/cookie "pase" como del mismo origen. Con
  `baseURL` en `http://`, los navegadores modernos (ej. el "HTTPS-Only
  Mode" de Firefox) reescriben por su cuenta el `fetch()` de
  `publicar.php` a `https://`, mientras la página se sirvió por
  `http://` — ese desajuste de protocolo hace que el sistema anti-bot de
  InfinityFree deje de considerarlo "mismo origen" y responda con una
  página HTML de desafío JS ("This site requires Javascript to work")
  en vez de dejar pasar el pedido hasta el Controller. El síntoma en el
  navegador es "⚠️ No pudimos conectar con el servidor" aunque el pedido
  haya dado HTTP 200. Si esto vuelve a pasar en cualquier flujo con
  `fetch()` (publicar, editar, pausar, etc.), lo primero a revisar es que
  `app.baseURL` en el `.env` del servidor coincida en protocolo con cómo
  se está accediendo al sitio.
- **Fotos subidas**: `public/uploads/herramientas/` necesita permisos de
  escritura para que `Herramientas::crear()` pueda guardar ahí. Si al
  publicar con foto te da error, puede ser un tema de permisos de esa
  carpeta — avisame.
