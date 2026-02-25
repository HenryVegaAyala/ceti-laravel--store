<template>
  <a-layout class="layout">

    <!-- NAVBAR -->
    <a-layout-header class="navbar">
      <div class="nav-inner">
        <div class="nav-logo">
          <ShopOutlined class="nav-logo-icon" />
          <span class="nav-logo-text">VueStore</span>
        </div>
        <a-space class="nav-actions">
          <a-input-search
            v-model:value="searchQuery"
            placeholder="Buscar productos..."
            class="nav-search"
            @search="onSearch"
          />
          <a-badge :count="cartCount" show-zero>
            <a-button shape="circle" @click="openCart">
              <template #icon><ShoppingCartOutlined /></template>
            </a-button>
          </a-badge>
          <a-button @click="$router.push('/form')">
            <template #icon><AppstoreAddOutlined /></template>
            Admin
          </a-button>
          <a-button type="primary" @click="$router.push('/login')">
            <template #icon><UserOutlined /></template>
            Ingresar
          </a-button>
        </a-space>
      </div>
    </a-layout-header>

    <a-layout-content>

      <!-- HERO -->
      <section class="hero">
        <div class="hero-content">
          <a-tag color="purple" class="hero-tag">🔥 Nuevos productos</a-tag>
          <h1 class="hero-title">Descubre los mejores<br /><span>productos del momento</span></h1>
          <p class="hero-subtitle">Envío gratis en compras mayores a $500. Hasta 50% de descuento en artículos seleccionados.</p>
          <a-space>
            <a-button type="primary" size="large" @click="scrollToProducts">
              Ver productos
            </a-button>
            <a-button size="large" ghost>
              Ver ofertas
            </a-button>
          </a-space>
        </div>
      </section>

      <!-- STATS -->
      <section class="stats-bar">
        <a-row justify="center" :gutter="[48, 16]">
          <a-col v-for="stat in stats" :key="stat.label" :xs="12" :sm="6">
            <a-statistic :title="stat.label" :value="stat.value" :prefix="stat.prefix" />
          </a-col>
        </a-row>
      </section>

      <!-- CATEGORÍAS -->
      <section class="section">
        <div class="container">
          <h2 class="section-title">Categorías</h2>
          <a-row :gutter="[16, 16]" justify="center">
            <a-col
              v-for="cat in categories"
              :key="cat.name"
              :xs="12" :sm="8" :md="4"
            >
              <a-card
                hoverable
                class="cat-card"
                :class="{ active: selectedCategory === cat.name }"
                @click="filterByCategory(cat.name)"
              >
                <div class="cat-icon">{{ cat.emoji }}</div>
                <div class="cat-name">{{ cat.name }}</div>
              </a-card>
            </a-col>
          </a-row>
        </div>
      </section>

      <!-- PRODUCTOS -->
      <section class="section products-section" id="productos">
        <div class="container">
          <div class="products-header">
            <h2 class="section-title">
              {{ selectedCategory === 'Todos' ? 'Todos los productos' : selectedCategory }}
            </h2>
            <a-select v-model:value="sortBy" class="sort-select" @change="sortProducts">
              <a-select-option value="default">Relevancia</a-select-option>
              <a-select-option value="price-asc">Precio: menor a mayor</a-select-option>
              <a-select-option value="price-desc">Precio: mayor a menor</a-select-option>
              <a-select-option value="rating">Mejor valorados</a-select-option>
            </a-select>
          </div>

          <a-spin :spinning="loading" size="large">
            <a-row :gutter="[24, 24]">
              <a-col
                v-for="product in filteredProducts"
                :key="product.id"
                :xs="24" :sm="12" :md="8" :lg="6"
              >
                <a-card hoverable class="product-card">
                  <template #cover>
                    <div class="product-img-wrap">
                      <img :src="product.image" :alt="product.name" class="product-img" />
                      <a-tag v-if="product.discount" color="red" class="product-badge">
                        -{{ product.discount }}%
                      </a-tag>
                    </div>
                  </template>
                  <a-tag :color="product.categoryColor" class="product-cat-tag">
                    {{ product.category }}
                  </a-tag>
                  <a-card-meta :title="product.name">
                    <template #description>
                      <a-rate :value="product.rating" disabled allow-half class="product-rate" />
                      <span class="product-reviews">({{ product.reviews }})</span>
                    </template>
                  </a-card-meta>
                  <div class="product-footer">
                    <div class="product-price">
                      <span class="price-current">${{ product.price }}</span>
                      <span v-if="product.originalPrice" class="price-original">
                        ${{ product.originalPrice }}
                      </span>
                    </div>
                    <a-button type="primary" size="small" @click="addToCart(product)">
                      <template #icon><ShoppingCartOutlined /></template>
                      Agregar
                    </a-button>
                  </div>
                </a-card>
              </a-col>
            </a-row>
          </a-spin>

          <!-- SIN RESULTADOS -->
          <a-empty
            v-if="filteredProducts.length === 0 && !loading"
            description="No se encontraron productos"
            class="empty-state"
          />
        </div>
      </section>

      <!-- BANNER OFERTA -->
      <section class="promo-banner">
        <div class="promo-content">
          <ThunderboltOutlined class="promo-icon" />
          <div>
            <h3 class="promo-title">¡Oferta especial de temporada!</h3>
            <p class="promo-sub">Usa el cupón <a-tag color="gold">VUETIGER</a-tag> y obtén 20% extra</p>
          </div>
          <a-button type="primary" ghost size="large">Comprar ahora</a-button>
        </div>
      </section>

    </a-layout-content>

    <!-- FOOTER -->
    <a-layout-footer class="footer">
      <div class="footer-inner">
        <span>© 2026 VueStore · Hecho con ❤️ y Ant Design Vue</span>
      </div>
    </a-layout-footer>

  </a-layout>
</template>

<script setup>
import { ref, computed } from 'vue'
import {
  ShopOutlined,
  ShoppingCartOutlined,
  UserOutlined,
  ThunderboltOutlined,
  AppstoreAddOutlined
} from '@ant-design/icons-vue'
import { message } from 'ant-design-vue'
import { useProducts } from '../composables/useProducts.js'

const { products } = useProducts()

const searchQuery = ref('')
const cartCount = ref(0)
const loading = ref(false)
const selectedCategory = ref('Todos')
const sortBy = ref('default')

const stats = [
  { label: 'Productos', value: 1200, prefix: '📦' },
  { label: 'Clientes felices', value: 8500, prefix: '😊' },
  { label: 'Marcas', value: 120, prefix: '🏷️' },
  { label: 'Entregas realizadas', value: 25000, prefix: '🚚' },
]

const categories = [
  { name: 'Todos',       emoji: '🛒' },
  { name: 'Electrónica', emoji: '💻' },
  { name: 'Ropa',        emoji: '👕' },
  { name: 'Hogar',       emoji: '🏠' },
  { name: 'Deportes',    emoji: '⚽' },
  { name: 'Libros',      emoji: '📚' },
  { name: 'Otros',       emoji: '📦' },
]

const filteredProducts = computed(() => {
  let list = products.value

  if (selectedCategory.value !== 'Todos') {
    list = list.filter(p => p.category === selectedCategory.value)
  }

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase()
    list = list.filter(p => p.name.toLowerCase().includes(q))
  }

  if (sortBy.value === 'price-asc') list = [...list].sort((a, b) => a.price - b.price)
  if (sortBy.value === 'price-desc') list = [...list].sort((a, b) => b.price - a.price)
  if (sortBy.value === 'rating') list = [...list].sort((a, b) => b.rating - a.rating)

  return list
})

const filterByCategory = (cat) => {
  selectedCategory.value = cat
}

const sortProducts = () => { /* reactivo por computed */ }

const onSearch = (val) => {
  searchQuery.value = val
}

const addToCart = (product) => {
  cartCount.value++
  message.success(`"${product.name}" agregado al carrito 🛒`)
}

const openCart = () => {
  message.info(`Tienes ${cartCount.value} producto(s) en el carrito`)
}

const scrollToProducts = () => {
  document.getElementById('productos')?.scrollIntoView({ behavior: 'smooth' })
}
</script>

<style scoped>
/* ── LAYOUT ── */
.layout { min-height: 100vh; background: #f5f5f5; }

/* ── NAVBAR ── */
.navbar {
  background: #1a1a2e;
  height: 64px;
  padding: 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 2px 12px rgba(0,0,0,.3);
}
.nav-inner {
  max-width: 1280px;
  margin: 0 auto;
  height: 100%;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}
.nav-logo { display: flex; align-items: center; gap: 10px; }
.nav-logo-icon { font-size: 28px; color: #667eea; }
.nav-logo-text { color: #fff; font-size: 20px; font-weight: 700; }
.nav-search { width: 240px; }
.nav-actions { flex-wrap: nowrap; }

/* ── HERO ── */
.hero {
  background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
  padding: 96px 24px;
  text-align: center;
}
.hero-content { max-width: 700px; margin: 0 auto; }
.hero-tag { margin-bottom: 16px; font-size: 14px; }
.hero-title {
  font-size: clamp(32px, 5vw, 56px);
  font-weight: 800;
  color: #fff;
  line-height: 1.2;
  margin: 0 0 16px;
}
.hero-title span { color: #667eea; }
.hero-subtitle { color: #aaa; font-size: 16px; margin-bottom: 32px; }

/* ── STATS ── */
.stats-bar {
  background: #fff;
  padding: 32px 24px;
  box-shadow: 0 2px 8px rgba(0,0,0,.06);
}

/* ── SECTION ── */
.section { padding: 64px 24px; }
.products-section { background: #f0f2f5; }
.container { max-width: 1280px; margin: 0 auto; }
.section-title {
  font-size: 28px;
  font-weight: 700;
  color: #1a1a2e;
  margin-bottom: 32px;
}
.products-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 32px;
  flex-wrap: wrap;
  gap: 12px;
}
.products-header .section-title { margin-bottom: 0; }
.sort-select { width: 220px; }

/* ── CATEGORÍAS ── */
.cat-card {
  text-align: center;
  cursor: pointer;
  transition: all .2s;
  border: 2px solid transparent;
}
.cat-card.active, .cat-card:hover {
  border-color: #667eea;
  transform: translateY(-4px);
}
.cat-icon { font-size: 32px; }
.cat-name { font-weight: 600; margin-top: 8px; color: #444; }

/* ── PRODUCTO ── */
.product-card { border-radius: 12px; overflow: hidden; height: 100%; }
.product-img-wrap { position: relative; }
.product-img { width: 100%; height: 200px; object-fit: cover; display: block; }
.product-badge { position: absolute; top: 8px; right: 8px; }
.product-cat-tag { margin-bottom: 8px; }
.product-rate { font-size: 12px; }
.product-reviews { color: #888; font-size: 12px; margin-left: 4px; }
.product-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 16px;
}
.price-current { font-size: 20px; font-weight: 700; color: #667eea; }
.price-original {
  font-size: 13px;
  color: #aaa;
  text-decoration: line-through;
  margin-left: 6px;
}
.empty-state { padding: 64px 0; }

/* ── PROMO BANNER ── */
.promo-banner {
  background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
  padding: 48px 24px;
}
.promo-content {
  max-width: 1280px;
  margin: 0 auto;
  display: flex;
  align-items: center;
  gap: 24px;
  flex-wrap: wrap;
  justify-content: center;
}
.promo-icon { font-size: 48px; color: #fff; }
.promo-title { color: #fff; font-size: 22px; font-weight: 700; margin: 0; }
.promo-sub { color: rgba(255,255,255,.85); margin: 4px 0 0; }

/* ── FOOTER ── */
.footer { background: #1a1a2e; color: #aaa; text-align: center; }
.footer-inner { max-width: 1280px; margin: 0 auto; }
</style>

