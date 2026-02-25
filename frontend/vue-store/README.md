# 🛍️ VueStore — Frontend

Tienda en línea construida con **Vue 3**, **Vite**, **Vue Router** y **Ant Design Vue**.

---

## 📋 Requisitos previos

Antes de iniciar asegúrate de tener instalado en tu máquina:

| Herramienta | Versión mínima | Descarga |
|---|---|---|
| **Node.js** | 18.x o superior | https://nodejs.org |
| **pnpm** | 8.x o superior | https://pnpm.io/installation |
| **Git** | cualquier versión reciente | https://git-scm.com |

> ℹ️ Puedes verificar las versiones con:
> ```bash
> node -v
> pnpm -v
> git --version
> ```

---

## 🚀 Instalación paso a paso

### 1. Clona el repositorio

```bash
git clone <URL_DEL_REPOSITORIO>
```

### 2. Ingresa a la carpeta del proyecto

```bash
cd frontend/vue-store
```

### 3. Instala las dependencias

```bash
pnpm install
```

> Si prefieres usar **npm**:
> ```bash
> npm install
> ```
>
> O con **yarn**:
> ```bash
> yarn install
> ```

### 4. Inicia el servidor de desarrollo

```bash
pnpm dev
```

### 5. Abre el navegador

El proyecto estará disponible en:

```
http://localhost:5173
```

---

## 📁 Estructura del proyecto

```
vue-store/
├── public/                 # Archivos estáticos públicos
├── src/
│   ├── assets/             # Imágenes y recursos
│   ├── components/         # Componentes reutilizables
│   ├── composables/
│   │   └── useProducts.js  # Store reactivo compartido de productos
│   ├── router/
│   │   └── index.js        # Configuración de rutas
│   ├── views/
│   │   ├── Landing.vue     # Catálogo de productos (página principal)
│   │   ├── Login.vue       # Formulario de inicio de sesión
│   │   └── FormStore.vue   # Panel de gestión de productos (CRUD)
│   ├── App.vue
│   ├── main.js
│   └── style.css
├── index.html
├── vite.config.js
└── package.json
```

---

## 🗺️ Rutas disponibles

| Ruta | Vista | Descripción |
|---|---|---|
| `/` | `Landing.vue` | Catálogo con todos los productos |
| `/login` | `Login.vue` | Formulario de inicio de sesión |
| `/form` | `FormStore.vue` | Panel para agregar, editar y eliminar productos |

---

## 📦 Tecnologías utilizadas

| Paquete | Versión | Descripción |
|---|---|---|
| [Vue 3](https://vuejs.org/) | ^3.5.25 | Framework principal |
| [Vite](https://vitejs.dev/) | ^7.3.1 | Bundler y servidor de desarrollo |
| [Vue Router](https://router.vuejs.org/) | ^5.0.3 | Enrutamiento SPA |
| [Ant Design Vue](https://antdv.com/) | ^4.2.6 | Librería de componentes UI |
| [@ant-design/icons-vue](https://antdv.com/components/icon) | ^7.0.1 | Íconos de Ant Design |
| [unplugin-vue-components](https://github.com/unplugin/unplugin-vue-components) | ^31.0.0 | Auto-importación de componentes |

---

## 🛠️ Scripts disponibles

| Comando | Descripción |
|---|---|
| `pnpm dev` | Inicia el servidor de desarrollo en `http://localhost:5173` |
| `pnpm build` | Genera el build de producción en la carpeta `dist/` |
| `pnpm preview` | Previsualiza el build de producción localmente |

---

## ✨ Funcionalidades

- 🛒 **Catálogo de productos** con búsqueda en tiempo real y filtrado por categoría
- 🗂️ **Ordenamiento** por precio y calificación
- ➕ **Alta de productos** desde el panel admin (`/form`)
- ✏️ **Edición** de productos existentes
- 🗑️ **Eliminación** con confirmación
- 🔄 **Estado reactivo compartido** — los cambios en el admin se reflejan al instante en el catálogo
- 🔐 **Formulario de login** con validaciones
- 📱 **Diseño responsivo** para móvil, tablet y escritorio

---

## ⚠️ Solución de problemas comunes

**`pnpm` no se reconoce como comando:**
```bash
npm install -g pnpm
```

**El puerto 5173 está ocupado:**

Vite asignará automáticamente el siguiente puerto disponible (5174, 5175…). También puedes configurarlo en `vite.config.js`:
```js
export default defineConfig({
  server: {
    port: 3000
  }
})
```

**Error de dependencias / node_modules corrupto:**
```bash
pnpm store prune
pnpm install
```

---

## 👨‍💻 Desarrollado con

- ❤️ Vue 3 Composition API (`<script setup>`)
- 🎨 Ant Design Vue 4
- ⚡ Vite 7
