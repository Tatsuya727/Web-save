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

                <div>john@google.com</div>
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
                                            <v-avatar color="grey-darken-1"></v-avatar>
                                        </template>

                                        <v-list-item-title>
                                            {{ url.url }}
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

<script setup>
import { ref, computed, onMounted } from 'vue';
import Pagination from '@/Components/Pagination.vue';

// definePropsを使用してpropsを定義
const props = defineProps({
    urls: Object,
});

onMounted(() => {
    console.log(props.urls);
    console.log(props.urls.last_page);
});

const urlsGroupedByDate = computed(() => {
    return props.urls.data.reduce((groupedUrls, url) => {
        const date = url.created_at.split('T')[0]; // Assuming the date is in '2023-06-16T00:00:00.000Z' format
        if (!groupedUrls[date]) {
            groupedUrls[date] = [];
        }
        groupedUrls[date].push(url);
        return groupedUrls;
    }, {});
});

const links = [
    ['mdi-inbox-arrow-down', 'Inbox'],
    ['mdi-send', 'Send'],
    ['mdi-delete', 'Trash'],
    ['mdi-alert-octagon', 'Spam'],
];

const search_items = ['トマト', 'キュウリ', 'ナス', 'ピーマン', 'オクラ', 'モロヘイヤ'];

const drawer = ref(null);
</script>

<style scoped></style>
