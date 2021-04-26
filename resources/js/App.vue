
<template>
  <div id="app">
    <List 
        v-for="list in lists" 
        :key="list.id" 
        v-bind:name="list.name"
        v-bind:id="list.id"
        v-bind:items="items"
        @newItem="newItem"
    ></List>  
    <br />
     <form @submit.prevent="newList">
            <input type="text" class="input" name="listname" v-model="newlistname" placeholder="MAKE NEW LIST" />
            <button type="submit" >submit</button>
        </form>
  </div>
</template>

<script>
import List from "./List.vue";

export default {
  name: 'App',
  data() {
        return {
            newlistname: "",
            items: [],
            lists: [],
        };
    },
    methods: {
        filterItems(listid){
            return this.items.filter(item=>item.listid===listid);
        },
        async getItems() {
            const { data } = await window.axios.get("/api/items");
            data.forEach(item=>this.items.push({content: item.content, id: item.id, listid: item.todolist_id}));
        },
        async getLists() {
            const { data } = await window.axios.get("/api/lists");
            data.forEach(list=>this.lists.push({id: list.id, name: list.name }));
        },
        async newItem(list, content) {
           let user=1;
           const { data } = await window.axios.post('/api/items', {user, list, content});
           console.log(data);
           this.items.push({content: data.content, id: data.id, list: data.todolist_id})
           this.newlistname = "";
        },
        async newList() {
            let user=1; 
            let name = this.newlistname;
            const { data } = await window.axios.post('/api/lists', {user, name});
            this.lists.push({id: data.id, name: data.name});
            this.newlistname = "";
        },
        async editItem() {
            // To do
        },
        async editList() {
            // To do
        }
    },
    created() {
        this.getItems();
        this.getLists();
    },
  components: {
      List
  }
}
</script>