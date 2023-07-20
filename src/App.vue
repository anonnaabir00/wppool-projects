<template>
    <div class="mt-6 mb-6 bg-white">
        
        <div class="mb-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="url">External URL</label>
        <input v-model="url" id="url" name="url" type="url" placeholder="https://example.com" class="shadow appearance-none border rounded w-3/6 py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="images">Project Images</label>
        <input type="button" id="add-gallery-image" class="cursor-pointer bg-black text-white pl-6 pr-6 p-3" value="Add Image">
        </div>
            
    </div>
</template>

<script>
import Vue from 'vue';

export default({
    name: 'App',
    data() {
        return {
            url: admin_settings.options.url,
            description: admin_settings.options.description,
        }
    },
    methods: {

    }
})


</script>