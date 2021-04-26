
<template>
  <div id="app">
    <List 
        v-for="list in lists" 
        :key="list.id" 
        v-bind:name="list.name"
        v-bind:id="list.id"
        v-bind:items="list.items"
        @newItem="newItem"
        @deleteItem="deleteItem"
        @deleteList="deleteList"
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
            lists: [],
        };
    },
    methods: {
        async getLists() {
            const listresponse = await window.axios.get("/api/lists");
            const returnedLists = listresponse.data;
            const itemsresponse = await window.axios.get("/api/items");
            const returnedItems = itemsresponse.data;
            returnedLists.forEach(list=>{
                const newList =  {id: list.id, name: list.name , items: returnedItems.filter(item=>item.todolist_id==list.id)};
                this.lists.push(newList);
                }
            );
        },
        //Get changed list out of lists, items.push(). list param is list id
        async newItem(list, content) {
           let user=1;
           const { data } = await window.axios.post('/api/items', {user, list, content});
           let updatedlist = this.lists.find(oldlist =>oldlist.id===list);
            updatedlist.items.push({content: data.content, id: data.id, list: data.todolist_id})
           this.newlistname = "";
        },
        async newList() {
            let user=1; 
            let name = this.newlistname;
            const { data } = await window.axios.post('/api/lists', {user, name});
            this.lists.push({id: data.id, name: data.name, items:[]});
            this.newlistname = "";
        },
        async editItem() {
            // To do
        },
        async editList() {
            // To do
        },
        async deleteItem(id) {
            const response = await window.axios.delete(`/api/items/${id}`);
            if(response.status==200){
                let updatedlist = this.lists.find(oldlist=>oldlist.items.some(item=>item.id===id))
                updatedlist.items = updatedlist.items.filter(item => item.id!==id);
            }
        },
        async deleteList(id) {
            const response = await window.axios.delete(`/api/lists/${id}`);
            if(response.status==200){
                this.lists = this.lists.filter(oldlist=>oldlist.id!==id);
            }
        }
    },
    created() {
        this.getLists();
    },
  components: {
      List
  }
}
</script>