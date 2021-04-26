<template>
    <div class="item">
        <div v-if="!editing">
            {{ this.content }}
            <button v-on:click="deleteItem()">Delete</button>
            <button v-on:click="enableEditing">Edit</button>
        </div>
        <div v-if="editing">
            <input v-model="tempValue" class="input" />
            <button @click="disableEditing">Cancel</button>
            <button @click="editItem">Save</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "Item",
    data() {
        return {
            editing: false,
            tempValue: null,
            changelist: null
        };
    },
    props: ["content", "id", "todolist_id"],
    methods: {
        deleteItem() {
            this.$emit("deleteItem", this.id);
        },
        enableEditing: function() {
            this.tempValue = this.value;
            this.editing = true;
        },
        disableEditing: function() {
            this.tempValue = null;
            this.editing = false;
        },
        editItem() {
            //newlist, oldlist, content
            let newlist;
            if (this.changelist) {
                newlist = this.changelist;
            } else newlist = this.todolist_id;
            console.log("id inside item", this.id);
            this.$emit(
                "editItem",
                this.id,
                newlist,
                this.todolist_id,
                this.tempValue
            );
            this.disableEditing();
        }
    }
};
</script>
