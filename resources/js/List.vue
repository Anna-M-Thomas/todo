<template>
    <div class="list">
        <div v-if="!editingList">
            <h2>{{this.name}} <button v-on:click="deleteList">delete list</button><button v-on:click="enableEditingList">edit list</button></h2>
        </div>
        <div v-if="editingList">
            <input v-model="templistValue" class="input"/>
            <button @click="disableEditingList"> Cancel </button>
            <button @click="saveEdit"> Save </button>
        </div>
        <item 
            v-for="item in items" 
            v-bind="item"
            @deleteItem="deleteItem"
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
            editingList: false,
            templistValue: null
        };
    },
    methods: {
        enableEditingList: function(){
        this.templistValue = this.value;
        this.editingList = true;
        },
        disableEditingList: function(){
        this.templistValue = null;
        this.editingList = false;
        },
        newItem(){
            this.$emit("newItem", this.id, this.newitemcontent)
            this.newitemcontent="";
        },
        deleteItem(id){
            this.$emit("deleteItem", id)
        },
        deleteList(){
            this.$emit("deleteList", this.id)
        },
    },
 props: ["name", "id", "items"],
  components: {
      Item
  }
}
</script>