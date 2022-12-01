<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted, ref, reactive } from 'vue';

const props = defineProps({
    item: Object
});


const itemObject = reactive({
    name: props.item.name,
    quantity: props.item.quantity
});

const onSubmitForm = async () => {
    const res = await axios.put(`/api/items/${props.item.id}`, itemObject, {
        headers: {
            'Authorization': 'Bearer 1|sIev095eyS78NUOBTUUR42ePOTFx4mrq7fpDzhf0'
        }
    });

    if (res.status === 200) {
        alert("OK");
    } else {
        alert("NOT OK");
    }
};

</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Item
            </h2>
        </template>
        <div class="container mx-auto my-3">
            <form @submit.prevent="onSubmitForm">
                <div class="mb-3">
                    <label for="formControlInputNameEdit" class="form-label">Name</label>
                    <input type="text" class="form-control" id="formControlInputNameEdit" placeholder="Name of item..."
                        v-model="itemObject.name">
                </div>
                <div class="mb-3">
                    <label for="formControlInputQuantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="formControlInputQuantity" min="1"
                        placeholder="Quantity of item..." v-model="itemObject.quantity">
                </div>
                <div class="flex justify-end">
                    <button class="btn btn-sm btn-success">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>

<style>

</style>
