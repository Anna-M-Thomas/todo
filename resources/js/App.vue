<template>
    <div id="app">
        <div v-if="!user">
            <form action="#" @submit.prevent="handleLogin">
                <div>
                    <input type="email" v-model="formData.email" />
                </div>
                <div>
                    <input type="password" v-model="formData.password" />
                </div>
                <div>
                    <button type="submit">Sign in</button>
                </div>
            </form>
        </div>
        <div v-if="user">
            <button v-on:click="handleLogout">Log out</button>
            <List
                v-for="list in lists"
                :key="list.id"
                v-bind:name="list.name"
                v-bind:id="list.id"
                v-bind:items="list.items"
                @newItem="newItem"
                @editItem="editItem"
                @deleteItem="deleteItem"
                @deleteList="deleteList"
                @editList="editList"
            ></List>
            <br />
            <form @submit.prevent="newList">
                <input
                    type="text"
                    class="input"
                    name="listname"
                    v-model="newlistname"
                    placeholder="MAKE NEW LIST"
                />
                <button type="submit">submit</button>
            </form>
        </div>
    </div>
</template>

<script>
import List from "./List.vue";

export default {
    name: "App",
    data() {
        return {
            newlistname: "",
            lists: [],
            user: null,
            formData: {
                email: "",
                password: ""
            }
        };
    },
    methods: {
        handleLogin() {
            axios.get("/sanctum/csrf-cookie").then(response => {
                axios
                    .post("/api/login", this.formData)
                    .then(response => {
                        console.log("User signed in!");
                    })
                    .then(response => {
                        axios.get("/api/user").then(response => {
                            console.log(response.data);
                            this.user = response.data;
                            this.getLists();
                        });
                    })
                    .catch(error => console.log(error)); // credentials didn't match
            });
        },
        handleLogout() {
            axios
                .post("/api/logout")
                .then(response => {
                    console.log("....logged out??");
                    this.user = null;
                })
                .catch(error => console.log(error)); // credentials didn't match
        },
        async getLists() {
            console.log("I was called");
            const listresponse = await window.axios.get("/api/lists");
            const returnedLists = listresponse.data;
            const itemsresponse = await window.axios.get("/api/items");
            const returnedItems = itemsresponse.data;
            returnedLists.forEach(list => {
                const newList = {
                    id: list.id,
                    name: list.name,
                    items: returnedItems.filter(
                        item => item.todolist_id == list.id
                    )
                };
                this.lists.push(newList);
            });
        },
        //Get changed list out of lists, items.push(). list param is list id
        async newItem(list, content) {
            let user = 1;
            const { data } = await window.axios.post("/api/items", {
                user,
                list,
                content
            });
            let updatedlist = this.lists.find(oldlist => oldlist.id === list);
            updatedlist.items.push({
                content: data.content,
                id: data.id,
                list: data.todolist_id
            });
            this.newlistname = "";
        },
        async newList() {
            let user = 1;
            let name = this.newlistname;
            const { data } = await window.axios.post("/api/lists", {
                user,
                name
            });
            this.lists.push({ id: data.id, name: data.name, items: [] });
            this.newlistname = "";
        },
        async editItem(id, newlist, oldlist, content) {
            const { data } = await window.axios.put(`api/items/${id}`, {
                newlist,
                oldlist,
                content
            });
            if (content !== "NO_CHANGE") {
                let updatedlist = this.lists.find(oldlist =>
                    oldlist.items.some(item => item.id === id)
                );
                updatedlist.items = updatedlist.items.map(item =>
                    item.id === id ? data : item
                );
            }
        },
        async editList(newname, listid) {
            const { data } = await window.axios.put(`api/lists/${listid}`, {
                name: newname
            });
            let updatedlist = this.lists.find(oldlist => oldlist.id === listid);
            updatedlist.name = data.name;
        },
        async deleteItem(id) {
            const response = await window.axios.delete(`/api/items/${id}`);
            if (response.status == 200) {
                let updatedlist = this.lists.find(oldlist =>
                    oldlist.items.some(item => item.id === id)
                );
                updatedlist.items = updatedlist.items.filter(
                    item => item.id !== id
                );
            }
        },
        async deleteList(id) {
            const response = await window.axios.delete(`/api/lists/${id}`);
            if (response.status == 200) {
                this.lists = this.lists.filter(oldlist => oldlist.id !== id);
            }
        }
    },
    created() {
        //
    },
    components: {
        List
    }
};
</script>
