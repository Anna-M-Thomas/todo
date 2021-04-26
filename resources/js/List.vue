<template>
    <div class="list">
        <h2>{{this.name}} <button>delete list</button></h2>
        <item 
            v-for="item in filteredItems" 
            v-bind="item"
            :key="item.id"
        ></item>
        <form @submit.prevent="newItem">
            <input type="text" class="input" name="itemcontent" v-model="newitemcontent" placeholder="Add new item" />
            <button type="submit">submit</button>
        </form>
    </div>
</template>

<script>
import Item from "./Item.vue";

export default {
  name: 'List',
  data() {
        return {
            newitemcontent: "",
        };
    },
    methods: {
        newItem(){
            this.$emit("newItem", this.id, this.newitemcontent)
        }
    },
    computed:{
        filteredItems(){
            return this.items.filter(item=>item.listid===this.id);
        }
    },
 props: ["name", "id", "items"],
  components: {
      Item
  }
}
</script>