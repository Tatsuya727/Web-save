<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
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

const cards = ['Today', 'Yesterday'];
const drawer = ref(null);
</script>

<template>
    <Head title="Welcome" />
    <v-app>
        <v-navigation-drawer v-model="drawer">
            <v-sheet color="grey-lighten-4" class="pa-4">
                <!-- <v-avatar class="mb-4" color="grey-darken-1" size="64"></v-avatar> -->

                <div>
                    <div>ゲストさん</div>
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
                    <div class="ml-2 text-center"></div>
                    <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('urls.index')"
                            class="bg-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >マイページ</Link
                        >

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="mx-5 bg-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >ログイン</Link
                            >

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-green hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                >新規登録</Link
                            >
                        </template>
                    </div>
                </v-app-bar>
            </div>
            <!-- レスポンシブ対応（モバイル） -->
            <div v-else>
                <v-app-bar :elevation="2">
                    <div class="ml-10 text-center">
                        <input
                            label="Search"
                            class="w-full px-3 py-2 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 border border-indigo-600"
                            placeholder="Search..."
                        />
                        <button class="bg-blue-300 py-2 px-2 border border-indigo-600">検索</button>
                    </div>
                    <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('urls.index')"
                            class="bg-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                            >マイページ</Link
                        >

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="mx-5 bg-green hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                >ログイン</Link
                            >

                            <Link
                                v-if="canRegister"
                                :href="route('register')"
                                class="bg-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                >新規登録</Link
                            >
                        </template>
                    </div>
                </v-app-bar>
            </div>
            <v-container class="py-8 px-6" fluid>
                <v-row>
                    <div v-if="isMobile" class="ml-9">
                        <div class="ml-10 text-center">
                            <input
                                label="Search"
                                class="w-full px-3 py-2 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 border border-indigo-600"
                                placeholder="Search..."
                            />
                            <button class="bg-blue-300 py-2 px-2 border border-indigo-600">
                                検索
                            </button>
                        </div>
                    </div>
                    <v-col v-for="card in cards" :key="card" cols="12">
                        <v-card>
                            <v-list lines="two">
                                <v-list-subheader>{{ card }}</v-list-subheader>
                                <template v-for="n in 6" :key="n">
                                    <v-list-item>
                                        <template v-slot:prepend>
                                            <v-avatar color="grey-darken-1"></v-avatar>
                                        </template>

                                        <v-list-item-title>URL {{ n }}</v-list-item-title>

                                        <v-list-item-subtitle> サイトの説明 </v-list-item-subtitle>
                                    </v-list-item>

                                    <v-divider
                                        v-if="n !== 6"
                                        :key="`divider-${n}`"
                                        inset
                                    ></v-divider>
                                </template>
                            </v-list>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
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
