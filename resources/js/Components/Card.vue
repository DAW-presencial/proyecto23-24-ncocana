<template>
    <div class="border border-gray-400 p-4 min-h-64 rounded-lg">
        <!-- BUTTONS -->
        <div class="flex gap-2 justify-end h-6 float-end">
            <SecundaryButton v-if="candelete" class="bg-red-700 text-white hover:bg-red-800" @click="showModal = true">
                DELETE
            </SecundaryButton>
            <SecundaryButton v-if="nameButton == 'UPDATE'" class="bg-green-700 text-white hover:bg-green-800"
                @click="update">{{ nameButton }}
            </SecundaryButton>
            <SecundaryButton v-if="nameButton == 'SHOW'" class="bg-green-700 text-white hover:bg-green-800"
                :href="modifyLink">{{ nameButton }}
            </SecundaryButton>
        </div>
        <div>
            <Modal :show="showModal" maxWidth="2xl">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Are you sure you want to delete the bookmark?
                    </h2>

                    <div class="mt-6 flex justify-end gap-2">
                        <SecundaryButton class="bg-green-700 text-white hover:bg-green-800" @click="showModal = false">
                            Cancel
                        </SecundaryButton>

                        <SecundaryButton class="bg-red-700 text-white hover:bg-red-800" @click="deleteBookmark">
                            Delete
                        </SecundaryButton>
                    </div>
                </div>
                <!-- <div>
                    <p class="">¿Estás seguro que deseas eliminar?</p>
                    <button @click="deleteItem">Confirmar</button>
                    <button @click="showModal = false">Cancelar</button>
                </div> -->
            </Modal>
            <slot></slot>
        </div>

        <!-- MODAL -->
    </div>
</template>

<script setup>
import SecundaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";

const props = defineProps({
    candelete: Boolean,
    modifyLink: String,
    nameButton: String,
    id: String,
    update: Function,
});

const showModal = ref(false);

const deleteBookmark = () => {
    axios
        .delete(`/bookmarks/${props.id}`)
        .then(() => {
            window.location.href = "/bookmarks";
        });
};
</script>
