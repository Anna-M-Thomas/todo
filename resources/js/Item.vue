<template>
    <div class="item">
            <div>{{this.content}} <button>Edit</button><button v-on:click="deleteItem()">Delete</button></div>
    </div>
</template>

<script>
export default {
    name: 'Item',
    props: ["content", "id", "todolist_id"],
    methods: {
        deleteItem(){
            this.$emit("deleteItem", this.id)
        }
    }
};
</script>
