# 📚 Un Rincón en Velaris – Blog de Libros (PHP MVC)

Pequeño **CMS / blog de reseñas de libros** desarrollado en PHP con arquitectura MVC, URL amigables y autenticación de usuarios.  
Permite crear, editar y borrar reseñas, gestionar portadas de libros y administrar usuarios desde un panel privado.

---

## ✨ Características principales

- 🧭 **Enrutamiento con URLs amigables**
  - Rutas limpias del tipo `/blog/temporada-de-turistas` en lugar de `index.php?page=post&id=1`.

- 🧱 **Arquitectura MVC**
  - Separación clara entre **Modelos**, **Vistas** y **Controladores**.

- 👤 **Sistema de usuarios**
  - Registro e inicio de sesión.
  - Roles: `user`, `admin`, `banned`.  
  - Panel de administración para gestionar usuarios (banear / desbanear). :contentReference[oaicite:0]{index=0}  

- 🔐 **Autenticación segura**
  - Contraseñas protegidas con `password_hash()` y comprobadas con `password_verify()`.
  - Sesiones con `$_SESSION` para mantener el estado de login y restringir zonas privadas.

- 📝 **Gestión de reseñas**
  - Crear, listar, editar y borrar reseñas de libros.
  - Filtro por género.
  - Cada reseña se asocia a un usuario autor.

- 🖼️ **Subida de portadas**
  - Las reseñas permiten subir una imagen de portada (validada y guardada en un directorio seguro).

- 💜 **Interfaz cuidada**
  - Diseño pastel con modo claro/oscuro.
  - Tarjetas de reseñas con portada, datos del libro, reseñador, género y acciones sociales (like / corazón / guardar).   

- 🧪 **Validación & saneamiento de datos**
  - Uso de `trim()`, `htmlspecialchars()` y filtros antes de guardar o mostrar la información.
  - Sentencias preparadas con PDO para evitar inyecciones SQL. :contentReference[oaicite:2]{index=2}  

---

## 🛠️ Tecnologías utilizadas

- **PHP 8+**
- **MySQL** + **PDO**
- HTML5, CSS3
- Algo de JavaScript vanilla para efectos visuales
- PlantUML para los diagramas de clases (inicial y final)

---

## 🗂️ Estructura de carpetas (simplificada)

```bash
.
├── config.php              # Configuración de base de datos, constantes, etc.
├── index.php               # Punto de entrada y enrutador principal
├── modelo/
│   ├── Database.php        # Clase singleton para la conexión PDO
│   ├── User.php            # Modelo de usuario
│   └── Post.php            # Modelo de reseñas (posts)
├── controlador/
│   ├── AuthController.php  # Login / logout / registro
│   ├── PostController.php  # Listado y gestión de reseñas
│   ├── AdminController.php # Gestión de usuarios (admin)
│   └── PerfilController.php# Edición del perfil
├── vista/
│   ├── partials/
│   │   ├── header.php
│   │   └── footer.php
│   ├── home.php
│   ├── detalle_post.php
│   ├── admin_posts.php
│   ├── admin_usuarios.php
│   └── ...
├── public/
│   ├── css/
│   │   └── estilos.css
│   └── uploads/
│       └── portadas/       # Imágenes subidas de los libros
└── docs/
    ├── diagrama_inicial.puml
    └── diagrama_final.puml
