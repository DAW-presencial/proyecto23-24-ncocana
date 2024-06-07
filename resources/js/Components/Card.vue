<template>
    <div class="border border-gray-400 p-4 rounded-lg shadow-lg">
        <!-- BUTTONS -->
        <div class="flex gap-2 justify-end h-auto w-auto mb-4 float-end">

            <!-- ELIMINAR BOOKMARK DE UN COLLECTION -->
            <SecundaryButton v-if="id_collection" class="bg-yellow-600 text-white hover:bg-yellow-700"
                @click="showModalDeleteBookmarkCollection = true">{{ $t("Delete from collection") }}
            </SecundaryButton>

            <!-- ELIMINAR UN BOOKMARK -->
            <SecundaryButton v-if="candelete" class="bg-red-700 text-white hover:bg-red-800"
                @click="showModalDelete = true">
                {{ $t('DELETE') }}
            </SecundaryButton>

            <!-- ELIMINAR UN COLLECTION -->
            <SecundaryButton v-if="candeletecollection" class="bg-red-700 text-white hover:bg-red-800"
                @click="showModalDeleteCollection = true">
                {{ $t('DELETE') }}
            </SecundaryButton>

            <!-- ACTUALIZAR BOOKMARKS / COLLECTIONS-->
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

            <!-- ELIMINAR BOOKMARK DE UN COLLECTION -->
            <Modal :show="showModalDeleteBookmarkCollection" maxWidth="2xl">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ $t('Are you sure you want to remove the bookmark from the collection?') }}
                    </h2>

                    <div class="mt-6 flex justify-end gap-2">
                        <SecundaryButton class="bg-green-700 text-white hover:bg-green-800"
                            @click="showModalDeleteBookmarkCollection = false">
                            {{ $t('Cancel') }}
                        </SecundaryButton>

                        <SecundaryButton class="bg-red-700 text-white hover:bg-red-800"
                            @click="deleteBookmarkCollection">
                            {{ $t('Delete') }}
                        </SecundaryButton>
                    </div>
                </div>
            </Modal>

            <!-- ELIMINAR COLLECTION -->
            <Modal :show="showModalDeleteCollection" maxWidth="2xl">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ $t('Are you sure you want to delete the collection?') }}
                    </h2>

                    <div class="mt-6 flex justify-end gap-2">
                        <SecundaryButton class="bg-green-700 text-white hover:bg-green-800"
                            @click="showModalDeleteCollection = false">
                            {{ $t('Cancel') }}
                        </SecundaryButton>

                        <SecundaryButton class="bg-red-700 text-white hover:bg-red-800" @click="deleteCollection">
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
    candeletecollection: Boolean,
    modifyLink: String,
    nameButton: String,
    id_bookmark: String,
    id_collection: String,
    id: String,
    update: Function,
    delete_collection: String
});

const showModalDelete = ref(false);
const showModalDeleteCollection = ref(false);
const showModalDeleteBookmarkCollection = ref(false);

const deleteBookmark = () => {
    axios
        .delete(`api/v1/bookmarks/${props.id}`)
        .then(() => {
            window.location.href = "/bookmarks";
        });
};

const deleteBookmarkCollection = () => {
    axios
        .delete(`api/v1/collections/${props.id_collection}/bookmarks/${props.id_bookmark}`)
        .then(() => {
            window.location.reload();
        })
}

const deleteCollection = () => {
    axios
        .delete(`api/v1/collections/${props.id}`)
        .then(() => {
            window.location.reload();
        })
}
</script>
