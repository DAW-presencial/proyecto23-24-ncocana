<template>
    <div class="border border-gray-400 p-4 rounded-lg shadow-lg">
        <!-- BUTTONS -->
        <div class="flex gap-2 justify-end h-auto mb-4 float-end">

            <SecundaryButton v-if="id_collection" class="bg-yellow-600 text-white hover:bg-yellow-700"
                @click="showModalDeleteCollection = true">{{ $t("Delete from collection") }}
            </SecundaryButton>

            <SecundaryButton v-if="candelete" class="bg-red-700 text-white hover:bg-red-800"
                @click="showModalDelete = true">
                {{ $t('DELETE') }}

            </SecundaryButton>
            <SecundaryButton v-if="nameButton == 'UPDATE'" class="bg-green-700 text-white hover:bg-green-800"
                @click="update">{{ $t(nameButton) }}
            </SecundaryButton>
            <SecundaryButton v-if="nameButton == 'SHOW'" class="bg-green-700 text-white hover:bg-green-800"
                :href="modifyLink">{{ $t(nameButton) }}
            </SecundaryButton>

        </div>
        <div>
            <Modal :show="showModalDelete" maxWidth="2xl">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ $t('Are you sure you want to delete the bookmark?') }}
                    </h2>

                    <div class="mt-6 flex justify-end gap-2">
                        <SecundaryButton class="bg-green-700 text-white hover:bg-green-800"
                            @click="showModalDelete = false">
                            {{ $t('Cancel') }}
                        </SecundaryButton>

                        <SecundaryButton class="bg-red-700 text-white hover:bg-red-800" @click="deleteBookmark">
                            {{ $t('Delete') }}
                        </SecundaryButton>
                    </div>
                </div>
            </Modal>

            <Modal :show="showModalDeleteCollection" maxWidth="2xl">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ $t('Are you sure you want to delete the bookmark?') }}
                    </h2>

                    <div class="mt-6 flex justify-end gap-2">
                        <SecundaryButton class="bg-green-700 text-white hover:bg-green-800"
                            @click="showModalDeleteCollection = false">
                            {{ $t('Cancel') }}
                        </SecundaryButton>

                        <SecundaryButton class="bg-red-700 text-white hover:bg-red-800"
                            @click="deleteBookmarkCollection">
                            {{ $t('Delete') }}
                        </SecundaryButton>
                    </div>
                </div>
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
    id_bookmark: String,
    id_collection: String,

    update: Function,
    delete_collection: String
});

const showModalDelete = ref(false);
const showModalDeleteCollection = ref(false);

const deleteBookmark = () => {
    axios
        .delete(`api/v1/bookmarks/${props.id}`)
        .then(() => {
            window.location.href = "/bookmarks";
        });
};

const deleteBookmarkCollection = () => {
    axios
        .delete (`api/v1/collections/${props.id_collection}/bookmarks/${props.id_bookmark}`)
        .then(() => {
            window.location.reload();
        } )
}
</script>
