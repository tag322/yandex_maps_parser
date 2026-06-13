<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({
    email: '',
    password: '',
})

const errors = ref({})
const loading = ref(false)
const formError = ref('')
const shake = ref(false)

const login = async () => {
    loading.value = true
    errors.value = {}

    try {
        // Получаем CSRF cookie
        await axios.get('/sanctum/csrf-cookie')

        // Авторизация
        await axios.post('/login', form.value)

        // После успешного входа
        router.push('/')
    } catch (e) {
        if (e.response?.status === 422) {
            errors.value = e.response.data.errors ?? {}
        } else if (e.response?.status === 401) {
            formError.value = 'Email или пароль неверны'
            triggerErrorUI()
        } else {
            formError.value = 'Ошибка сервера'
            triggerErrorUI()
        }
    } finally {
        loading.value = false
    }
}

const triggerErrorUI = () => {
    shake.value = true

    setTimeout(() => {
        shake.value = false
        formError.value = ''
        errors.value = {}
    }, 5000)
}
</script>

<template>
    <div class="card">
        <h1>Тест</h1>

        <p v-if="formError" class="form-error">
            {{ formError }}
        </p>

        <form @submit.prevent="login">

            <label for="email">
                Email
            </label>

            <input
                id="email"
                v-model="form.email"
                type="email"
                :class="{ errorInput: errors.email || formError }"
            />

            <p
                v-if="errors.email"
                class="error"
            >
                {{ errors.email[0] }}
            </p>

            <label for="password">
                Пароль
            </label>

           <input
                id="password"
                v-model="form.password"
                type="password"
                :class="{ errorInput: errors.password || formError }"
            />

            <p
                v-if="errors.password"
                class="error"
            >
                {{ errors.password[0] }}
            </p>

            <button
                type="submit"
                :disabled="loading"
            >
                {{ loading ? 'Вход...' : 'Войти' }}
            </button>

        </form>
    </div>
</template>

<style scoped>
@import '@/assets/main.css';
</style>