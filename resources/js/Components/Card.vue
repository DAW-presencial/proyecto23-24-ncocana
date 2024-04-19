<template>
    <div class='border border-gray-300 mt-4 p-4 min-h-36 m-5 rounded-lg'>
        <!-- BUTTONS -->
        <div class="flex gap-2 justify-end h-6">
            <SecundaryButton class="bg-red-700 text-white hover:bg-red-800">DELETE</SecundaryButton>
            <SecundaryButton class="bg-green-700 text-white hover:bg-green-800">ADD
            </SecundaryButton>
        </div>
        <div>
            <slot>

            </slot>
        </div>
    </div>
</template>

<script setup>
import SecundaryButton from '@/Components/SecondaryButton.vue'

</script>
