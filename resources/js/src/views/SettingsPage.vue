<script setup>
import { ref } from 'vue'
import axios from 'axios'

const url = ref('')
const error = ref('')
const loading = ref(false)

const save = async () => {
    error.value = ''

    const value = url.value.trim()

    if (!value) {
        error.value = 'Введите ссылку.'
        return
    }

    if (!value.startsWith('https://yandex.ru/maps')) {
        error.value = 'Укажите ссылку на Яндекс Карты.'
        return
    }

    loading.value = true

    try {
        await axios.post('/api/setPlace', {
            url: value,
        })
    } catch (e) {
        error.value = 'Не удалось сохранить.'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <div class="card card--wide">
        <h1>Ссылка на организацию</h1>

        <form @submit.prevent="save">
            <label for="url">
                Ссылка на Яндекс Карты
            </label>

            <input
                id="url"
                v-model.trim="url"
                type="url"
                placeholder="https://yandex.ru/maps/..."
                :disabled="loading"
            >

            <p
                v-if="error"
                class="error"
            >
                {{ error }}
            </p>

            <button
                type="submit"
                :disabled="loading"
            >
                {{ loading ? 'Сохранение...' : 'Сохранить' }}
            </button>
        </form>
    </div>
</template>

<style scoped>
@import "@/assets/main.css";
</style>