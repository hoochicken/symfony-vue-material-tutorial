<template>
    <div>
        <h2>Demonic</h2>
        <md-app>
            <md-app-toolbar class="md-primary">
                <span class="md-title">My Title</span>
            </md-app-toolbar>

            <md-app-drawer md-permanent="full">
                <md-toolbar class="md-transparent" md-elevation="0">
                    Navigation
                </md-toolbar>

                <md-list>
                    <md-list-item>
                        <md-icon>move_to_inbox</md-icon>
                        <span class="md-list-item-text">Inbox</span>
                    </md-list-item>

                    <md-list-item>
                        <md-icon>send</md-icon>
                        <span class="md-list-item-text">Sent Mail</span>
                    </md-list-item>

                    <md-list-item>
                        <md-icon>delete</md-icon>
                        <span class="md-list-item-text">Trash</span>
                    </md-list-item>

                    <md-list-item>
                        <md-icon>error</md-icon>
                        <span class="md-list-item-text">Spam</span>
                    </md-list-item>
                </md-list>
            </md-app-drawer>

            <md-app-content>
                <ul>
                    <li v-for="item in demoCracy" :key="item.id">
                        <h4>{{ item.title }}</h4>
                        <div>{{ item.description }}</div>
                    </li>
                </ul>
                <pagination :totalPage="listState.totalPage" @btnClick="changePage"></pagination>
            </md-app-content>
        </md-app>
    </div>
</template>

<script>
    export default {
        name: "Demonic",
        data() {
            return {
                response: {},
                demoCracy: {},
                searchterm: '',
                listState: {
                    maxResults: 3,
                    currentPage: 0,
                    totalPage: 0,
                    totalItems: 0
                },
                listStateDefault: {
                    maxResults: 3,
                    currentPage: 0,
                    totalPage: 0,
                    totalItems: 0
                }
            }
        },
        async mounted() {
            this.list();
        },
        methods: {
            async list() {
                let params = new URLSearchParams();
                params.append('searchterm', this.searchterm);
                params.append('listState', JSON.stringify(this.listState));
                const response = await this.axios.post('/demo', params);
                this.response = response;
                this.demoCracy = response.data.items;
                this.listState = response.data.listState;
            },
            async changePage (n) {
                this.listState.currentPage = n > 0 ? n - 1 : n;
                this.list();
            }
        }
    }
</script>

<style scoped>

</style>
