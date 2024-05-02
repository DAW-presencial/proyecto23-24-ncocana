<template>
    <div class='border border-gray-400 p-4 min-h-64 rounded-lg'>
        <!-- BUTTONS -->
        <div class="flex gap-2 justify-end h-6 float-end">
            <SecundaryButton class="bg-red-700 text-white hover:bg-red-800">@lang('actions.delete')</SecundaryButton>
            <SecundaryButton class="bg-green-700 text-white hover:bg-green-800">MODIFY
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
