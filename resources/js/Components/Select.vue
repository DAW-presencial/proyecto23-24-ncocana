<template>
    <div>
        <label :for="id" class="block text-sm font-medium text-gray-700">{{$t(label) }}</label>
        <select :id="id" :name="name" v-model="selected" @change="updateValue"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
            <option v-for="option in options" :key="option.value" :value="option.value">{{$t(option.text) }}</option>
        </select>
    </div>
</template>

<script>
export default {
    props: {
        label: String,
        options: Array,
        value: String,
        id: String,
        name: String
    },
    data() {
        return {
            selected: this.value
        };
    },
    methods: {
        updateValue() {
            this.$emit('input', this.selected);
        }
    }
};
</script>
