<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import '../bootstrap';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const isMobile = ref(false);

const updateIsMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

onMounted(() => {
    window.addEventListener('resize', updateIsMobile);
    updateIsMobile();
});

onUnmounted(() => {
    window.removeEventListener('resize', updateIsMobile);
});
</script>
<style scoped>
@import 'resources/css/app.css';
</style>

<template>
    <Head title="Welcome" />
    <v-app>
        <v-navigation-drawer v-model="drawer">
            <v-sheet color="grey-lighten-4" class="pa-4">
                <!-- <v-avatar class="mb-4" color="grey-darken-1" size="64"></v-avatar> -->

                <div>
                    <div>さん</div>
                </div>
            </v-sheet>

            <v-divider></v-divider>

            <!-- <v-list>
                <v-list-item v-for="[icon, text] in links" :key="icon" link>
                    <template v-slot:prepend>
                        <v-icon>{{ icon }}</v-icon>
                    </template>

                    <v-list-item-title>{{ text }}</v-list-item-title>
                </v-list-item>
            </v-list> -->
        </v-navigation-drawer>

        <v-main>
            <div v-if="isMobile">
                <v-app-bar :elevation="2">
                    <div class="ml-2 text-center">
                        <v-btn href="/urls" class="mr-2" variant="text"> TOP </v-btn>

                        <v-btn color="primary" variant="outlined">
                            URLを登録
                            <v-dialog v-model="dialog" activator="parent" width="auto">
                                <v-sheet width="350">
                                    <v-form @submit.prevent="storeUrl">
                                        <v-text-field
                                            v-model="form.url"
                                            :rules="rules"
                                            label="URL"
                                            :error-messages="form.errors.url"
                                        ></v-text-field>
                                        <v-btn type="submit" block class="bg-green">登録</v-btn>
                                        <!-- <v-autocomplete clearable chips label="タグ" :items="search_items" multiple></v-autocomplete> -->
                                    </v-form>
                                </v-sheet>
                            </v-dialog>
                        </v-btn>
                    </div>
                    <template v-slot:append>
                        <Link
                            href="/logout"
                            method="post"
                            as="button"
                            class="bg-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                        >
                            ログアウト
                        </Link>
                    </template>
                </v-app-bar>
            </div>
            <!-- レスポンシブ対応（モバイル） -->
            <div v-else>
                <v-app-bar :elevation="2">
                    <div class="ml-10 text-center">
                        <v-btn href="/urls" class="mr-10" variant="text"> TOP </v-btn>
                        <input
                            label="Search"
                            class="w-full px-3 py-2 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 border border-indigo-600"
                            placeholder="Search..."
                        />
                        <button class="bg-blue-300 py-2 px-2 border border-indigo-600">検索</button>
                        <v-btn class="ml-10" color="primary" variant="outlined">
                            URLを登録
                            <v-dialog v-model="dialog" activator="parent" width="auto">
                                <v-sheet width="800" class="mx-10">
                                    <v-form>
                                        <v-text-field></v-text-field>
                                        <v-btn type="submit" block class="bg-green">登録</v-btn>
                                    </v-form>
                                </v-sheet>
                            </v-dialog>
                        </v-btn>
                    </div>
                    <template v-slot:append>
                        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                            <Link
                                v-if="$page.props.auth.user"
                                :href="route('dashboard')"
                                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                >Dashboard</Link
                            >

                            <template v-else>
                                <Link
                                    :href="route('login')"
                                    class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                    >Log in</Link
                                >

                                <Link
                                    v-if="canRegister"
                                    :href="route('register')"
                                    class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                                    >Register</Link
                                >
                            </template>
                        </div>
                    </template>
                </v-app-bar>
            </div>
        </v-main>
    </v-app>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
