<script setup>
import { ref, computed, reactive } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';

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
});

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

const storeUrl = () => {
    Inertia.post('/urls', form);
};

const links = [
    ['mdi-inbox-arrow-down', 'Inbox'],
    ['mdi-send', 'Send'],
    ['mdi-delete', 'Trash'],
    ['mdi-alert-octagon', 'Spam'],
];

const search_items = ['トマト', 'キュウリ', 'ナス', 'ピーマン', 'オクラ', 'モロヘイヤ'];

const drawer = ref(null);
</script>

<template>
    <v-app>
        <v-system-bar>
            <v-spacer></v-spacer>

            <v-icon>mdi-square</v-icon>

            <v-icon>mdi-circle</v-icon>

            <v-icon>mdi-triangle</v-icon>
        </v-system-bar>

        <v-navigation-drawer v-model="drawer">
            <v-sheet color="grey-lighten-4" class="pa-4">
                <v-avatar class="mb-4" color="grey-darken-1" size="64"></v-avatar>

                <div>
                    <div class="text-center">
                        <v-btn color="primary">
                            URLを登録

                            <v-dialog v-model="dialog" activator="parent" width="auto">
                                <v-sheet width="800" class="mx-10">
                                    <v-form @submit.prevent="storeUrl">
                                        <v-text-field v-model="form.url" :rules="rules" label="URL"></v-text-field>
                                        <!-- <v-autocomplete clearable chips label="タグ" :items="['California', 'Colorado', 'Florida', 'Georgia', 'Texas', 'Wyoming']" multiple></v-autocomplete> -->
                                        <v-btn type="submit" block class="mt-2">Submit</v-btn>
                                    </v-form>
                                </v-sheet>
                            </v-dialog>
                        </v-btn>
                    </div>
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
            <v-container class="py-8 px-6" fluid>
                <v-row>
                    <v-autocomplete label="検索する" :items="search_items"></v-autocomplete>
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
