<template>
    <div class="list">
        <div v-if="!editing">
            <h2>
                {{ this.name }}
                <button @click="deleteList">delete list</button
                ><button @click="enableEditing">edit list</button>
            </h2>
        </div>
        <div v-if="editing">
            <input type="text" class="input" v-model="tempname" />
            <button @click="disableEditing">Cancel</button>
            <button @click="editList">Save</button>
        </div>
        <Draggable
            :list="items"
            group="items"
            @end="endDragged"
            :data-list="this.id"
        >
            <item
                v-for="item in items"
                v-bind="item"
                @deleteItem="deleteItem"
                @editItem="editItem"
                :key="item.id"
                :data-itemid="item.id"
            ></item>
        </Draggable>
        <form @submit.prevent="newItem">
            <input
                type="text"
                class="input"
                name="itemcontent"
                v-model="newitemcontent"
                placeholder="Add new item"
            />
            <button type="submit">submit</button>
        </form>
    </div>
</template>

<script>
import Item from "./Item.vue";
import Draggable from "vuedraggable";

export default {
    name: "List",
    data() {
        return {
            newitemcontent: "",
            editing: false,
            tempname: ""
        };
    },
    emits: ["editlist", "newItem", "deleteItem", "editItem", "deleteList"],
    methods: {
        endDragged: function(event) {
            let id = event.item.dataset.itemid;
            let newlist = event.to.dataset.list;
            let oldlist = event.from.dataset.list;
            let content = "NO_CHANGE";
            this.editItem(id, newlist, oldlist, content);
        },
        enableEditing: function() {
            this.tempname = this.value;
            this.editing = true;
        },
        disableEditing: function() {
            this.tempname = null;
            this.editing = false;
        },
        editList() {
            if (this.tempname) {
                this.$emit("editList", this.tempname, this.id);
            }
            this.disableEditing();
        },
        newItem() {
            this.$emit("newItem", this.id, this.newitemcontent);
            this.newitemcontent = "";
        },
        deleteItem(id) {
            this.$emit("deleteItem", id);
        },
        editItem(id, newlist, oldlist, content) {
            this.$emit("editItem", id, newlist, oldlist, content);
        },
        deleteList() {
            this.$emit("deleteList", this.id);
        }
    },
    props: ["name", "id", "items"],
    components: {
        Item,
        Draggable
    }
};
</script>
