<template>
    <div>
        <h2>Demonic</h2>
        <ul>
            <li v-for="item in demoCracy" :key="item.id">
                <h4>{{ item.title }}</h4>
                <div>{{ item.description }}</div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: "Demonic",
        data() {
            return {
                demoCracy: {}
            }
        },
        async mounted() {
            var params =  { };
            const response = await this.axios.post('/demo', params);
            this.demoCracy = response.data;
        }
    }
</script>

<style scoped>

</style>
