<template>
    <div class="item">
            <div>{{this.content}} <button>Edit</button><button>Delete</button></div>
    </div>
</template>

<script>
export default {
    name: 'Item',
    props: ["content", "id", "listid"]
};
</script>
