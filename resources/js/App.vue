
<template>
  <div id="app">
    <div v-for="list in lists" :key="list.id">
        <h2>{{list.name}} <button>delete list</button></h2>
        <item 
            v-for="item in filterItems(list.id)" 
            v-bind="item"
            :key="item.id"
            @newItem="newItem"
        ></item>
        <form @submit.prevent="newItem">
            <input type="text" class="input" name="itemname" v-model="newitemname" placeholder="Add new item" />
            <button type="submit" >submit</button>
        </form>
    </div>
    <div> 
        <form @submit.prevent="newList">
            <input type="text" class="input" name="listname" v-model="newlistname" placeholder="Add new list" />
            <button type="submit" >submit</button>
        </form>
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
            newlistname: "",
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
        async newItem(user=1, list, content) {
           const { data } = window.axios.post('/api/items', {user, list, content});
           //consolelog before figure out how to push
           console.log(data);
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
      Item
  }
}
</script>