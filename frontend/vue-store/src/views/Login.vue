<template>
  <div class="login-wrapper">
    <a-card class="login-card" :bordered="false">
      <!-- Logo / Título -->
      <div class="login-header">
        <ShopOutlined class="login-icon" />
        <h2 class="login-title">Iniciar Sesión</h2>
        <p class="login-subtitle">Bienvenido de vuelta</p>
      </div>

      <!-- Formulario -->
      <a-form
        :model="formState"
        :rules="rules"
        layout="vertical"
        @finish="onFinish"
        @finishFailed="onFinishFailed"
      >
        <a-form-item label="Correo electrónico" name="email">
          <a-input
            v-model:value="formState.email"
            size="large"
            placeholder="correo@ejemplo.com"
          >
            <template #prefix>
              <MailOutlined class="input-icon" />
            </template>
          </a-input>
        </a-form-item>

        <a-form-item label="Contraseña" name="password">
          <a-input-password
            v-model:value="formState.password"
            size="large"
            placeholder="••••••••"
          >
            <template #prefix>
              <LockOutlined class="input-icon" />
            </template>
          </a-input-password>
        </a-form-item>

        <a-form-item>
          <div class="form-options">
            <a-checkbox v-model:checked="formState.remember">
              Recuérdame
            </a-checkbox>
            <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
          </div>
        </a-form-item>

        <a-form-item>
          <a-button
            type="primary"
            html-type="submit"
            size="large"
            block
            :loading="loading"
          >
            Ingresar
          </a-button>
        </a-form-item>

        <a-divider plain>¿No tienes cuenta?</a-divider>

        <a-button size="large" block @click="goToRegister">
          Crear una cuenta
        </a-button>
      </a-form>
    </a-card>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { MailOutlined, LockOutlined, ShopOutlined } from '@ant-design/icons-vue'
import { message } from 'ant-design-vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const loading = ref(false)

const formState = reactive({
  email: '',
  password: '',
  remember: false,
})

const rules = {
  email: [
    { required: true, message: 'Por favor ingresa tu correo', trigger: 'blur' },
    { type: 'email', message: 'Ingresa un correo válido', trigger: 'blur' },
  ],
  password: [
    { required: true, message: 'Por favor ingresa tu contraseña', trigger: 'blur' },
    { min: 6, message: 'La contraseña debe tener al menos 6 caracteres', trigger: 'blur' },
  ],
}

const onFinish = async (values) => {
  loading.value = true
  try {
    // Aquí conectas con tu API de Laravel
    console.log('Credenciales:', values)
    message.success('Inicio de sesión exitoso')
    // router.push('/dashboard') // descomentar al conectar con backend
  } catch (error) {
    message.error('Credenciales incorrectas. Intenta de nuevo.')
  } finally {
    loading.value = false
  }
}

const onFinishFailed = ({ errorFields }) => {
  console.log('Errores:', errorFields)
}

const goToRegister = () => {
  // router.push('/register') // descomentar cuando exista la ruta
  message.info('Redirigiendo al registro...')
}
</script>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 24px;
}

.login-card {
  width: 100%;
  max-width: 420px;
  border-radius: 16px;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  padding: 12px;
}

.login-header {
  text-align: center;
  margin-bottom: 32px;
}

.login-icon {
  font-size: 48px;
  color: #667eea;
  display: block;
  margin-bottom: 12px;
}

.login-title {
  font-size: 26px;
  font-weight: 700;
  color: #1a1a2e;
  margin: 0 0 4px;
}

.login-subtitle {
  color: #888;
  font-size: 14px;
  margin: 0;
}

.form-options {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.forgot-link {
  color: #667eea;
  font-size: 14px;
  text-decoration: none;
}

.forgot-link:hover {
  text-decoration: underline;
}

.input-icon {
  color: #bbb;
}
</style>