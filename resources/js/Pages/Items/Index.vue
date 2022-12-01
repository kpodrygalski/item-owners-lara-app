<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Inertia } from '@inertiajs/inertia';
import { onMounted, ref, reactive } from 'vue';

const items = ref([]); // EMPTY ARRAY

const itemObject = reactive({
    name: '',
    quantity: null
});

const patchObject = reactive({
    name: null
});

const onSubmitPatchForm = async (itemId) => {
    const res = await axios.patch(`/api/items/${itemId}/set-name`, patchObject, {
        headers: {
            'Authorization': 'Bearer 1|sIev095eyS78NUOBTUUR42ePOTFx4mrq7fpDzhf0'
        }
    });

    const data = await res.data.item;

    if (res.status === 200) {
        items.value = items.value.map((item) => item.id === itemId ? { ...item, ...data } : item);
        $("#staticBackdropPatchName_" + itemId).modal('hide');
        Inertia.visit('/views/items');
    }
};

const onSubmitCreateForm = async () => {
    const res = await axios.post('/api/items', itemObject, {
        headers: {
            'Authorization': 'Bearer 1|sIev095eyS78NUOBTUUR42ePOTFx4mrq7fpDzhf0'
        }
    });

    const data = await res.data.item;
    items.value = [...items.value, data];

    if (res.status === 201) {
        itemObject.name = '';
        itemObject.quantity = null;
        $("#staticBackdropCreate").modal('hide');
        Inertia.visit('/views/items');
    } else {
        alert("NOT_OK");
    }
};

const deleteItem = async (itemId) => {
    const res = await axios.delete(`/api/items/${itemId}`, {
        headers: {
            'Authorization': 'Bearer 1|sIev095eyS78NUOBTUUR42ePOTFx4mrq7fpDzhf0'
        }
    });

    if (res.status === 200) {
        items.value = items.value.filter((item) => item.id !== itemId);
        alert("OK");
    } else {
        alert("NOT_OK");
    }
};

onMounted(async () => {
    const res = await axios.get('/api/items', {
        headers: {
            'Authorization': 'Bearer 1|sIev095eyS78NUOBTUUR42ePOTFx4mrq7fpDzhf0'
        }
    });

    items.value = await res.data.items;
});
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Items
            </h2>
        </template>
        <div class="container mx-auto my-3">
            <div class="flex justify-end mb-3">
                <button class="btn btn-sm btn-dark mr-1" data-bs-toggle="modal"
                    data-bs-target="#staticBackdropCreate">Create</button>
            </div>
            <div class="card mb-3" v-for="(item, index) in items" :key="index">
                <div class="card-header">
                    <h5 class="card-title text-2xl font-bold uppercase">
                        {{ item.name }}
                    </h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Quantity: {{ item.quantity }}</p>
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-success mr-2" data-bs-toggle="modal"
                        :data-bs-target='("#staticBackdropShowItem_" + item.id)'>
                        Show
                    </button>

                    <a :href="route('views-items.edit-form', item)" class="btn btn-sm btn-warning mr-2">Edit</a>
                    <button class="btn btn-sm btn-danger mr-2" @click="deleteItem(item.id)">Delete</button>
                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                        :data-bs-target='("#staticBackdropPatchName_" + item.id)'>Set
                        name</button>
                </div>
                <!-- Modal Show-->
                <div class="modal fade" :id='("staticBackdropShowItem_" + item.id)' data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1" :aria-labelledby='("staticBackdropShowItemLabel" + item.id)'
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" :id='("staticBackdropShowItemLabel" + item.id)'>Info
                                </h1>
                                <button class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <span class="font-semibold uppercase">{{ item.name }}</span>
                                <hr />
                                <p class="text-red-500">
                                    Quantity: {{ item.quantity }}
                                </p>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button class="btn btn-primary">Understood</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal Patch Name -->
                <div class="modal fade" :id='("staticBackdropPatchName_" + item.id)' data-bs-backdrop="static"
                    data-bs-keyboard="false" tabindex="-1"
                    :aria-labelledby='("staticBackdropPatchNameLabel_" + item.id)' aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" :id='("staticBackdropPatchNameLabel_" + item.id)'>Set name
                                </h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form @submit.prevent="onSubmitPatchForm(item.id)">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="formControlInputPatchName" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="formControlInputPatchName"
                                            placeholder="Item new name" v-model="patchObject.name">
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- Modal Create -->
        <div class="modal fade" id="staticBackdropCreate" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="staticBackdropCreateLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="staticBackdropCreateLabel">Create item</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form @submit.prevent="onSubmitCreateForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="formControlInputNameEdit" class="form-label">Name</label>
                                <input type="text" class="form-control" id="formControlInputNameEdit"
                                    placeholder="Name of item..." v-model="itemObject.name">
                            </div>
                            <div class="mb-3">
                                <label for="formControlInputQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="formControlInputQuantity" min="1"
                                    placeholder="Quantity of item..." v-model="itemObject.quantity">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">X</button>
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<style>

</style>
