<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import JetModal from "@/Components/DialogModal.vue";
import moment from "moment";
// import { useToast } from "vue-toastification";
// import "vue-toastification/dist/index.css";
import { QrcodeStream } from "vue3-qrcode-reader";
import { onMounted, ref } from "vue";

const props = defineProps({
    events: Object,
});

const show_scanner = ref(false);
const data = ref([
    {
        name: "test",
    },
    {
        name: "test",
    },
    {
        name: "test",
    },
]);
// const toast = useToast({
//     position: "bottom-left",
//     timeout: 1500,
// });
const onInit = () => {};

const onDecode = (decodedString) => {
    console.log(decodedString);
};
onMounted(() => {
    // toast("test");
});
</script>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="sm:rounded-md">
            <div class="bg-white">
                <!-- <div
                    class="mx-auto max-w-2xl py-16 px-4 sm:py-24 sm:px-6 lg:max-w-7xl lg:px-8"
                >
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">
                        Customers also purchased
                    </h2>

                    <div
                        class="mt-6 grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-4 xl:gap-x-8"
                    >
                        <div class="group relative">
                            <div
                                class="min-h-80 aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-md bg-gray-200 group-hover:opacity-75 lg:aspect-none lg:h-80"
                            >
                                <img
                                    src="https://tailwindui.com/img/ecommerce-images/product-page-01-related-product-01.jpg"
                                    alt="Front of men&#039;s Basic Tee in black."
                                    class="h-full w-full object-cover object-center lg:h-full lg:w-full"
                                />
                            </div>
                            <div class="mt-4 flex justify-between">
                                <div>
                                    <h3 class="text-sm text-gray-700">
                                        <a href="#">
                                            <span
                                                aria-hidden="true"
                                                class="absolute inset-0"
                                            ></span>
                                            Basic Tee
                                        </a>
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Black
                                    </p>
                                </div>
                                <p class="text-sm font-medium text-gray-900">
                                    $35
                                </p>
                            </div>
                        </div>

                        <!-- More products... -->
                    </div>
                </div> -->
            </div>
            <div style="">
                <div class="flex flex-col px-4">
                    <div class="mt-2 overflow-hidden mb-2">
                        <div class="c overflow-auto">
                            <div
                                class="mt-2 grid grid-cols-3 grid-cols-3 gap-3 c overflow-y-auto overflow-x-hidden"
                            >
                                <div
                                    v-for="(d, index) in events.data"
                                    :key="index"
                                    class="bg-white shadow border-gray-300 border flex flex-col col-span-1 rounded-xl m-2 md:col-span-1 col-span-3"
                                >
                                    <div class="border-b border-gray-300 p-2">
                                        <span
                                            class="text-xs text-gray-600 bg-gray-100 rounded p-1 font-semibold"
                                        >
                                            #{{ index + 1 }}</span
                                        >
                                    </div>
                                    <div class="p-3 mt-auto">
                                        <div class="text-sm py-1">
                                            <span>{{ d.name }}</span>
                                        </div>
                                        <div class="hidden sm:block">
                                            <div class="pb-3 mt-3">
                                                <div
                                                    class="border-t border-gray-200"
                                                ></div>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-4 gap-4">
                                            <div class="text-xs text-gray-500">
                                                Check in
                                            </div>
                                            <div
                                                v-for="(
                                                    st, st_index
                                                ) in d.start_time"
                                                :key="st_index"
                                                class="col-span-3 text-sm text-gray-600 pt-2 pb-3"
                                            >
                                                {{
                                                    moment(new Date(st)).format(
                                                        "LLLL"
                                                    )
                                                }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Check out
                                            </div>
                                            <div
                                                class="col-span-3 text-sm text-gray-600 pt-2 pb-3"
                                            >
                                                {{
                                                    moment(
                                                        new Date(d.end_time)
                                                    ).format("LLLL")
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="border-t border-gray-300 p-2 mt-auto"
                                    >
                                        <a
                                            @click="show_scanner = true"
                                            href="javascript:;"
                                            class="text-sm text-blue-400 mr-2 inline-flex items-center"
                                        >
                                            Check in </a
                                        ><a
                                            @click="show_scanner = true"
                                            href="javascript:;"
                                            class="text-sm text-blue-400 inline-flex items-center"
                                        >
                                            Check out
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <JetModal :show="show_scanner">
        <template #title>QR Scanner</template>
        <template #content>
            <qrcode-stream
                @init="onInit"
                @decode="onDecode"
                class="w-1/2"
            ></qrcode-stream
        ></template>
        <template #footer>
            <JetSecondaryButton @click="show_scanner = false"
                >Close</JetSecondaryButton
            >
        </template>
    </JetModal>
</template>
