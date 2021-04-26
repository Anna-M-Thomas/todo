
<template>
  <div id="app">
    <div v-for="list in lists" :key="list.id">
        <h2>Here is the name of the list: {{list.name}}</h2>
        
    </div>
  </div>
</template>

<script>
import Item from "./Item.vue";

export default {
  name: 'App',
  data() {
        return {
            items: [],
            lists: [],
        };
    },
    methods: {
        async getItems() {
            const { data } = await window.axios.get("/api/items");
            data.forEach(item=>this.items.push({content: item.content, id: item.todolist_id, }));
        },
        async getLists() {
            const { data } = await window.axios.get("/api/lists");
            data.forEach(list=>this.lists.push({id: list.id, name: list.name }));
        },
        async newList() {
            // To do
        },
        async newItem() {
            // To do
        },
        async editList() {
            // To do
        },
        async editItem() {
            // To do
        },
    },
    created() {
        this.getItems();
        this.getLists();
    },

  components: {
      Item
  }
}
</script>