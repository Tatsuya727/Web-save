<script setup>
import { ref, computed, reactive } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';
import { Link } from '@inertiajs/inertia-vue3';

// definePropsを使用してpropsを定義
const props = defineProps({
    urls: Object,
});

const dialog = ref(false);

const form = reactive({
    url: '',
    title: '',
    description: '',
    favicon: '',
    errors: {},
});

const search = ref('');

const rules = [
    (value) => {
        if (value) return true;
        return 'URLを入力してください';
    },
];

const urlsGroupedByDate = computed(() => {
    return props.urls.data.reduce((groupedUrls, url) => {
        const date = url.created_at.split('T')[0];
        if (!groupedUrls[date]) {
            groupedUrls[date] = [];
        }
        groupedUrls[date].push(url);
        return groupedUrls;
    }, {});
});

const storeUrl = async () => {
    try {
        Inertia.post('/urls', form, {
            onSuccess: (page) => {
                if (page.props.flash.success) {
                    dialog.value = false;
                    form.url = '';
                    form.title = '';
                    form.description = '';
                    form.favicon = '';
                    props.urls.data.unshift(page.props.flash.url);
                }
            },
            onError: (errors) => {
                form.errors = errors;
            },
        });
    } catch (error) {
        console.log(error);
        errorMessage = error.message;
    }
};

const searchUrls = () => {
    Inertia.get(route('urls.index', { search: search.value }));
};

const links = [
    ['mdi-inbox-arrow-down', 'Inbox'],
    ['mdi-send', 'Send'],
    ['mdi-delete', 'Trash'],
    ['mdi-alert-octagon', 'Spam'],
];

const drawer = ref(null);
</script>

<template>
    <v-app>
        <v-navigation-drawer v-model="drawer">
            <v-sheet color="grey-lighten-4" class="pa-4">
                <!-- <v-avatar class="mb-4" color="grey-darken-1" size="64"></v-avatar> -->

                <div>
                    <div>{{ $page.props.auth.user.name }}</div>
                </div>
            </v-sheet>

            <v-divider></v-divider>

            <v-list>
                <v-list-item v-for="[icon, text] in links" :key="icon" link>
                    <template v-slot:prepend>
                        <v-icon>{{ icon }}</v-icon>
                    </template>

                    <v-list-item-title>{{ text }}</v-list-item-title>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-main>
            <v-app-bar :elevation="2">
                <div class="ml-10 text-center">
                    <input label="Search" v-model="search" class="w-full px-3 py-2 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:shadow-outline-blue focus:border-blue-300 border border-indigo-600" placeholder="Search..." />
                    <button class="bg-blue-300 py-2 px-2 border border-indigo-600" @click="searchUrls">検索</button>
                    <v-btn class="ml-10" color="primary" variant="outlined">
                        URLを登録

                        <v-dialog v-model="dialog" activator="parent" width="auto">
                            <v-sheet width="800" class="mx-10">
                                <v-form @submit.prevent="storeUrl">
                                    <v-text-field v-model="form.url" :rules="rules" label="URL" :error-messages="form.errors.url"></v-text-field>
                                    <v-btn type="submit" block class="bg-green">登録</v-btn>
                                    <!-- <v-autocomplete clearable chips label="タグ" :items="search_items" multiple></v-autocomplete> -->
                                </v-form>
                            </v-sheet>
                        </v-dialog>
                    </v-btn>
                </div>
                <template v-slot:append>
                    <Link href="/logout" method="post" as="button" class="ml-10 bg-blue hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"> ログアウト </Link>
                </template>
            </v-app-bar>
            <v-container class="py-8 px-6" fluid>
                <v-row>
                    <v-col v-for="(urlsByDate, date) in urlsGroupedByDate" :key="date" cols="12">
                        <v-card>
                            <v-list lines="two">
                                <v-list-subheader>{{ date }}</v-list-subheader>
                                <template v-for="url in urlsByDate" :key="url.id">
                                    <v-list-item>
                                        <template v-slot:prepend>
                                            <img v-if="url.favicon" :src="url.favicon" alt="Favicon" width="42" height="42" class="mr-5" />
                                            <v-avatar v-else color="grey-darken-1"></v-avatar>
                                        </template>

                                        <v-list-item-title>
                                            <a :href="url.url" target="_blank">{{ url.title }}</a>
                                        </v-list-item-title>

                                        <v-list-item-subtitle>
                                            {{ url.description }}
                                        </v-list-item-subtitle>
                                    </v-list-item>
                                </template>
                            </v-list>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
            <Pagination class="text-center mb-10" :links="urls.links"></Pagination>
        </v-main>
    </v-app>
</template>

<style scoped></style>
