<template>
  <a-layout class="layout">

    <!-- NAVBAR -->
    <a-layout-header class="navbar">
      <div class="nav-inner">
        <div class="nav-logo">
          <ShopOutlined class="nav-logo-icon" />
          <span class="nav-logo-text">VueStore — Admin</span>
        </div>
        <a-button type="primary" ghost @click="$router.push('/')">
          <template #icon><ArrowLeftOutlined /></template>
          Volver al catálogo
        </a-button>
      </div>
    </a-layout-header>

    <a-layout-content class="content-wrap">
      <div class="container">

        <!-- TÍTULO -->
        <div class="page-header">
          <h1 class="page-title">Gestión de Productos</h1>
          <p class="page-sub">Agrega, edita o elimina productos. Los cambios se reflejan en el catálogo al instante.</p>
        </div>

        <!-- FORMULARIO -->
        <a-card class="form-card" title="Nuevo Producto">
          <template #extra>
            <a-tag color="blue">{{ isEditing ? 'Modo edición' : 'Modo creación' }}</a-tag>
          </template>

          <a-form
            :model="formState"
            :rules="rules"
            layout="vertical"
            ref="formRef"
            @finish="onSubmit"
          >
            <a-row :gutter="24">
              <!-- Nombre -->
              <a-col :xs="24" :md="12">
                <a-form-item label="Nombre del producto" name="name">
                  <a-input
                    v-model:value="formState.name"
                    placeholder="Ej: Auriculares Bluetooth"
                    size="large"
                  >
                    <template #prefix><TagOutlined /></template>
                  </a-input>
                </a-form-item>
              </a-col>

              <!-- Categoría -->
              <a-col :xs="24" :md="12">
                <a-form-item label="Categoría" name="category">
                  <a-select
                    v-model:value="formState.category"
                    placeholder="Selecciona una categoría"
                    size="large"
                  >
                    <a-select-option v-for="cat in categoryOptions" :key="cat" :value="cat">
                      {{ cat }}
                    </a-select-option>
                  </a-select>
                </a-form-item>
              </a-col>

              <!-- Precio -->
              <a-col :xs="24" :sm="12" :md="8">
                <a-form-item label="Precio ($)" name="price">
                  <a-input-number
                    v-model:value="formState.price"
                    :min="0"
                    :precision="2"
                    placeholder="0.00"
                    size="large"
                    style="width: 100%"
                  >
                    <template #prefix>$</template>
                  </a-input-number>
                </a-form-item>
              </a-col>

              <!-- Precio original -->
              <a-col :xs="24" :sm="12" :md="8">
                <a-form-item label="Precio original ($)" name="originalPrice">
                  <a-input-number
                    v-model:value="formState.originalPrice"
                    :min="0"
                    :precision="2"
                    placeholder="0.00 (opcional)"
                    size="large"
                    style="width: 100%"
                  />
                </a-form-item>
              </a-col>

              <!-- Descuento -->
              <a-col :xs="24" :sm="12" :md="8">
                <a-form-item label="Descuento (%)" name="discount">
                  <a-input-number
                    v-model:value="formState.discount"
                    :min="0"
                    :max="100"
                    placeholder="0 (opcional)"
                    size="large"
                    style="width: 100%"
                  />
                </a-form-item>
              </a-col>

              <!-- Rating -->
              <a-col :xs="24" :sm="12">
                <a-form-item label="Calificación" name="rating">
                  <a-rate v-model:value="formState.rating" allow-half />
                </a-form-item>
              </a-col>

              <!-- Reseñas -->
              <a-col :xs="24" :sm="12">
                <a-form-item label="Número de reseñas" name="reviews">
                  <a-input-number
                    v-model:value="formState.reviews"
                    :min="0"
                    placeholder="0"
                    size="large"
                    style="width: 100%"
                  />
                </a-form-item>
              </a-col>

              <!-- Imagen URL -->
              <a-col :xs="24">
                <a-form-item label="URL de imagen" name="image">
                  <a-input
                    v-model:value="formState.image"
                    placeholder="https://... (deja vacío para imagen aleatoria)"
                    size="large"
                  >
                    <template #prefix><PictureOutlined /></template>
                  </a-input>
                </a-form-item>
              </a-col>

              <!-- Vista previa imagen -->
              <a-col :xs="24" v-if="previewImage">
                <a-form-item label="Vista previa">
                  <img :src="previewImage" alt="preview" class="img-preview" />
                </a-form-item>
              </a-col>
            </a-row>

            <!-- BOTONES -->
            <a-space>
              <a-button type="primary" html-type="submit" size="large">
                <template #icon>
                  <EditOutlined v-if="isEditing" />
                  <PlusOutlined v-else />
                </template>
                {{ isEditing ? 'Guardar cambios' : 'Agregar producto' }}
              </a-button>
              <a-button size="large" @click="resetForm" v-if="isEditing">
                Cancelar edición
              </a-button>
              <a-button size="large" @click="resetForm" v-else>
                Limpiar
              </a-button>
            </a-space>
          </a-form>
        </a-card>

        <!-- TABLA DE PRODUCTOS -->
        <a-card class="table-card" title="Productos registrados">
          <template #extra>
            <a-badge :count="products.length" :overflow-count="999" color="blue" />
          </template>

          <a-table
            :dataSource="products"
            :columns="columns"
            row-key="id"
            :pagination="{ pageSize: 6 }"
            :scroll="{ x: 800 }"
          >
            <!-- Imagen -->
            <template #bodyCell="{ column, record }">
              <template v-if="column.key === 'image'">
                <img :src="record.image" :alt="record.name" class="table-img" />
              </template>

              <!-- Precio -->
              <template v-else-if="column.key === 'price'">
                <span class="price-text">${{ record.price }}</span>
                <span v-if="record.originalPrice" class="price-original">
                  ${{ record.originalPrice }}
                </span>
              </template>

              <!-- Descuento -->
              <template v-else-if="column.key === 'discount'">
                <a-tag v-if="record.discount" color="red">-{{ record.discount }}%</a-tag>
                <span v-else class="text-muted">—</span>
              </template>

              <!-- Categoría -->
              <template v-else-if="column.key === 'category'">
                <a-tag :color="record.categoryColor">{{ record.category }}</a-tag>
              </template>

              <!-- Rating -->
              <template v-else-if="column.key === 'rating'">
                <a-rate :value="record.rating" disabled allow-half style="font-size:12px" />
              </template>

              <!-- Acciones -->
              <template v-else-if="column.key === 'actions'">
                <a-space>
                  <a-button type="primary" size="small" @click="editProduct(record)">
                    <template #icon><EditOutlined /></template>
                  </a-button>
                  <a-popconfirm
                    title="¿Eliminar este producto?"
                    ok-text="Sí, eliminar"
                    cancel-text="Cancelar"
                    @confirm="removeProduct(record.id)"
                  >
                    <a-button danger size="small">
                      <template #icon><DeleteOutlined /></template>
                    </a-button>
                  </a-popconfirm>
                </a-space>
              </template>
            </template>
          </a-table>
        </a-card>

      </div>
    </a-layout-content>

    <a-layout-footer class="footer">
      © 2026 VueStore · Panel de administración
    </a-layout-footer>

  </a-layout>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import {
  ShopOutlined, ArrowLeftOutlined, TagOutlined, PictureOutlined,
  PlusOutlined, EditOutlined, DeleteOutlined
} from '@ant-design/icons-vue'
import { message } from 'ant-design-vue'
import { useProducts } from '../composables/useProducts.js'

const { products, addProduct, deleteProduct, updateProduct } = useProducts()

const formRef = ref(null)
const isEditing = ref(false)
const editingId = ref(null)

const categoryOptions = ['Electrónica', 'Ropa', 'Hogar', 'Deportes', 'Libros', 'Otros']

const formState = ref({
  name: '',
  category: undefined,
  price: null,
  originalPrice: null,
  discount: null,
  rating: 5,
  reviews: 0,
  image: '',
})

const rules = {
  name:     [{ required: true, message: 'El nombre es obligatorio', trigger: 'blur' }],
  category: [{ required: true, message: 'Selecciona una categoría', trigger: 'change' }],
  price:    [{ required: true, type: 'number', message: 'El precio es obligatorio', trigger: 'blur' }],
}

// Vista previa de imagen
const previewImage = computed(() => {
  if (formState.value.image?.trim()) return formState.value.image.trim()
  if (formState.value.name?.trim()) {
    const seed = formState.value.name.toLowerCase().replace(/\s+/g, '-')
    return `https://picsum.photos/seed/${seed}/400/280`
  }
  return ''
})

const onSubmit = () => {
  const imageUrl = formState.value.image?.trim()
    || (formState.value.name
      ? `https://picsum.photos/seed/${formState.value.name.toLowerCase().replace(/\s+/g, '-')}/400/280`
      : 'https://picsum.photos/seed/product/400/280')

  if (isEditing.value) {
    updateProduct({ ...formState.value, id: editingId.value, image: imageUrl })
    message.success('Producto actualizado correctamente ✏️')
  } else {
    addProduct({ ...formState.value, image: imageUrl })
    message.success('Producto agregado al catálogo 🎉')
  }
  resetForm()
}

const editProduct = (product) => {
  isEditing.value = true
  editingId.value = product.id
  formState.value = {
    name:          product.name,
    category:      product.category,
    price:         product.price,
    originalPrice: product.originalPrice,
    discount:      product.discount,
    rating:        product.rating,
    reviews:       product.reviews,
    image:         product.image,
  }
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const removeProduct = (id) => {
  deleteProduct(id)
  message.success('Producto eliminado 🗑️')
}

const resetForm = () => {
  isEditing.value = false
  editingId.value = null
  formState.value = {
    name: '', category: undefined, price: null,
    originalPrice: null, discount: null, rating: 5, reviews: 0, image: ''
  }
  formRef.value?.resetFields()
}

const columns = [
  { title: 'Imagen',    key: 'image',    width: 90 },
  { title: 'Nombre',   dataIndex: 'name', key: 'name', ellipsis: true },
  { title: 'Categoría',key: 'category',  width: 130 },
  { title: 'Precio',   key: 'price',     width: 150 },
  { title: 'Descuento',key: 'discount',  width: 110 },
  { title: 'Rating',   key: 'rating',    width: 150 },
  { title: 'Acciones', key: 'actions',   width: 110, fixed: 'right' },
]
</script>

<style scoped>
.layout { min-height: 100vh; background: #f0f2f5; }

.navbar {
  background: #1a1a2e;
  padding: 0;
  height: 64px;
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
.nav-logo-icon { font-size: 26px; color: #667eea; }
.nav-logo-text { color: #fff; font-size: 18px; font-weight: 700; }

.content-wrap { padding: 40px 24px; }
.container { max-width: 1280px; margin: 0 auto; }

.page-header { margin-bottom: 32px; }
.page-title { font-size: 28px; font-weight: 800; color: #1a1a2e; margin: 0 0 8px; }
.page-sub { color: #888; margin: 0; }

.form-card { border-radius: 12px; margin-bottom: 32px; }
.table-card { border-radius: 12px; }

.img-preview {
  height: 140px;
  width: auto;
  border-radius: 8px;
  object-fit: cover;
  border: 2px solid #e8e8e8;
}

.table-img {
  width: 60px;
  height: 45px;
  object-fit: cover;
  border-radius: 6px;
}

.price-text { font-weight: 700; color: #667eea; margin-right: 6px; }
.price-original { font-size: 12px; color: #aaa; text-decoration: line-through; }
.text-muted { color: #bbb; }

.footer { background: #1a1a2e; color: #aaa; text-align: center; }
</style>

