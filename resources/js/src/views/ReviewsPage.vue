<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const place = ref({})
const reviews = ref({ data: [], meta: null })
const loading = ref(false)
const page = ref(1)

const fetchPlace = async (pageNumber = 1) => {
    loading.value = true

    try {
        const res = await axios.get('/api/getPlace', {
            params: {
                page: pageNumber
            }
        })

        place.value = {
            title: res.data.title,
            rating: res.data.rating,
            reviewsCount: res.data.reviewsCount,
            ratingsCount: res.data.ratingsCount,
        }

        reviews.value = res.data.reviews
    } finally {
        loading.value = false
    }
}

const changePage = (newPage) => {
    page.value = newPage
    fetchPlace(newPage)
}

onMounted(() => {
    fetchPlace()
})
</script>

<template>
    <div class="card card--fullscreen">
        <h1>{{ place.title }}</h1>

        <div class="stats" v-if="place">
            <div>Рейтинг: {{ place.rating }}</div>
            <div>Отзывов: {{ place.reviewsCount }}</div>
            <div>Оценок: {{ place.ratingsCount }}</div>
        </div>

        <div v-if="loading" class="loading">
            Загрузка...
        </div>

        <table v-else class="table">
            <thead>
                <tr>
                    <th>Автор</th>
                    <th>Рейтинг</th>
                    <th>Отзыв</th>
                    <th>Дата</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="review in reviews.data" :key="review.id">
                    <td>{{ review.author }}</td>
                    <td>{{ review.rating }}</td>
                    <td>{{ review.text }}</td>
                    <td>{{ review.publishedAt }}</td>
                </tr>
            </tbody>
        </table>

        <div class="pagination" v-if="reviews.meta">
            <button
                :disabled="reviews.meta.currentPage === 1"
                @click="changePage(reviews.meta.currentPage - 1)"
            >
                ←
            </button>

            <span>
                {{ reviews.meta.currentPage }} / {{ reviews.meta.lastPage }}
            </span>

            <button
                :disabled="reviews.meta.currentPage === reviews.meta.lastPage"
                @click="changePage(reviews.meta.currentPage + 1)"
            >
                →
            </button>
        </div>
    </div>
</template>

<style scoped>
@import '@/assets/main.css';
</style>