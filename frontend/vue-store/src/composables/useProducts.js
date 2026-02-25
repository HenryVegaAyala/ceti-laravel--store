import { ref } from 'vue'

// Estado global compartido entre vistas
const customProducts = ref([
  {
    id: 1, name: 'Auriculares Bluetooth Pro', category: 'Electrónica', categoryColor: 'blue',
    price: 899, originalPrice: 1199, discount: 25, rating: 4.5, reviews: 312,
    image: 'https://picsum.photos/seed/headphones/400/280'
  },
  {
    id: 2, name: 'Smartwatch Serie X', category: 'Electrónica', categoryColor: 'blue',
    price: 1299, originalPrice: null, discount: null, rating: 4, reviews: 187,
    image: 'https://picsum.photos/seed/smartwatch/400/280'
  },
  {
    id: 3, name: 'Zapatillas Runner Elite', category: 'Deportes', categoryColor: 'green',
    price: 650, originalPrice: 850, discount: 24, rating: 4.5, reviews: 95,
    image: 'https://picsum.photos/seed/shoes/400/280'
  },
  {
    id: 4, name: 'Camiseta Premium Algodón', category: 'Ropa', categoryColor: 'purple',
    price: 199, originalPrice: null, discount: null, rating: 4, reviews: 430,
    image: 'https://picsum.photos/seed/tshirt/400/280'
  },
  {
    id: 5, name: 'Lámpara de Escritorio LED', category: 'Hogar', categoryColor: 'orange',
    price: 349, originalPrice: 499, discount: 30, rating: 5, reviews: 68,
    image: 'https://picsum.photos/seed/lamp/400/280'
  },
  {
    id: 6, name: 'Teclado Mecánico RGB', category: 'Electrónica', categoryColor: 'blue',
    price: 750, originalPrice: null, discount: null, rating: 4.5, reviews: 214,
    image: 'https://picsum.photos/seed/keyboard/400/280'
  },
  {
    id: 7, name: 'Mochila Urbana 30L', category: 'Deportes', categoryColor: 'green',
    price: 420, originalPrice: 560, discount: 25, rating: 4, reviews: 153,
    image: 'https://picsum.photos/seed/backpack/400/280'
  },
  {
    id: 8, name: 'El Principito (Edición especial)', category: 'Libros', categoryColor: 'cyan',
    price: 120, originalPrice: null, discount: null, rating: 5, reviews: 890,
    image: 'https://picsum.photos/seed/book1/400/280'
  },
])

let nextId = 9

const categoryColorMap = {
  'Electrónica': 'blue',
  'Ropa':        'purple',
  'Hogar':       'orange',
  'Deportes':    'green',
  'Libros':      'cyan',
  'Otros':       'default',
}

export function useProducts() {
  const addProduct = (product) => {
    customProducts.value.push({
      ...product,
      id: nextId++,
      categoryColor: categoryColorMap[product.category] ?? 'default',
      rating: product.rating ?? 5,
      reviews: product.reviews ?? 0,
    })
  }

  const deleteProduct = (id) => {
    customProducts.value = customProducts.value.filter(p => p.id !== id)
  }

  const updateProduct = (updated) => {
    const idx = customProducts.value.findIndex(p => p.id === updated.id)
    if (idx !== -1) {
      customProducts.value[idx] = {
        ...customProducts.value[idx],
        ...updated,
        categoryColor: categoryColorMap[updated.category] ?? 'default',
      }
    }
  }

  return { products: customProducts, addProduct, deleteProduct, updateProduct }
}

