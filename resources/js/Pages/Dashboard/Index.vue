<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import JetModal from "@/Components/DialogModal.vue";
import moment from "moment";
// import { useToast } from "vue-toastification";
// import "vue-toastification/dist/index.css";
import { QrcodeStream } from "vue3-qrcode-reader";
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

const props = defineProps({
    events: Object,
});

const show_scanner = ref(false);
const hide_scanner = ref(false);
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

const scanner_status = ref("Scanning...");

const form = useForm({
    mode: "",
    index: "",
    id: "",
    id_number: "",
    time: "",
});
// const toast = useToast({
//     position: "bottom-left",
//     timeout: 1500,
// });
const onInit = () => {};

const onDecode = (decodedString) => {
    if (decodedString.trim().length > 0) {
        form.id_number = decodedString;
        form.time = new Date();
        submitForm();
    }
};

const submitForm = () => {
    scanner_status.value = "Saving entry...";
    form.post(route("home.update"), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            scanner_status.value = "Entry Recorded!";
            setTimeout(() => {
                scanner_status.value = "Scanning";
                form.reset();
            }, 1500);
        },
        onError: (e) => {
            console.log(e);
            scanner_status.value = e.message;
            setTimeout(() => {
                scanner_status.value = "Scanning";
            }, 2000);
        },
    });
};

const showScanner = (mode, index, id) => {
    form.mode = mode;
    form.index = index;
    form.id = id;
    show_scanner.value = true;
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

                     More products...
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
                                    class="flex justify-center"
                                    v-for="(d, index) in events.data"
                                    :key="index"
                                >
                                    <div
                                        class="block rounded-lg shadow-lg bg-white max-w-sm text-center"
                                    >
                                        <div
                                            class="py-3 px-6 border-b border-gray-300"
                                        >
                                            {{ d.name }}
                                        </div>
                                        <div class="p-6">
                                            <h5
                                                class="text-gray-900 text-xl font-medium mb-2"
                                            ></h5>
                                            <p
                                                class="text-gray-700 text-base mb-4"
                                            >
                                                Check in
                                            </p>

                                            <div
                                                class="flex flex-wrap justify-center space-x-2"
                                            >
                                                <a
                                                    @click="
                                                        showScanner(
                                                            'in',
                                                            st_index,
                                                            d.id
                                                        )
                                                    "
                                                    href="javascript:;"
                                                    v-for="(
                                                        st, st_index
                                                    ) in d.start_time"
                                                    :key="st_index"
                                                    class="mb-1 px-4 py-2 rounded-full text-gray-500 bg-gray-200 font-semibold text-sm flex align-center w-max cursor-pointer active:bg-gray-300 transition duration-300 ease"
                                                >
                                                    {{
                                                        moment(
                                                            new Date(st)
                                                        ).format("LLL")
                                                    }}
                                                </a>
                                            </div>
                                        </div>
                                        <div
                                            class="py-3 px-6 border-t border-gray-300 text-gray-600"
                                        >
                                            <p
                                                class="text-gray-700 text-base mb-4"
                                            >
                                                Check out
                                            </p>
                                            <div
                                                class="flex flex-wrap justify-center space-x-2"
                                            >
                                                <a
                                                    @click="
                                                        showScanner(
                                                            'out',
                                                            st_index,
                                                            d.id
                                                        )
                                                    "
                                                    href="javascript:;"
                                                    v-for="(
                                                        st, st_index
                                                    ) in d.end_time"
                                                    :key="st_index"
                                                    class="mb-1 px-4 py-2 rounded-full text-gray-500 bg-gray-200 font-semibold text-sm flex align-center w-max cursor-pointer active:bg-gray-300 transition duration-300 ease"
                                                >
                                                    {{
                                                        moment(
                                                            new Date(st)
                                                        ).format("LLL")
                                                    }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div
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
                                                class="col-span-3 text-sm text-gray-600 pt-2 pb-3"
                                            >
                                                <a
                                                    @click="
                                                        showScanner(
                                                            'out',
                                                            st_index,
                                                            d.id
                                                        )
                                                    "
                                                    href="javascript:;"
                                                    class="text-sm text-blue-400 mr-2 inline-flex items-center"
                                                    v-for="(
                                                        st, st_index
                                                    ) in d.end_time"
                                                    :key="st_index"
                                                >
                                                    {{
                                                        moment(
                                                            new Date(st)
                                                        ).format("LLL")
                                                    }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Check out
                                            </div>
                                            <div
                                                class="col-span-3 text-sm text-gray-600 pt-2 pb-3"
                                            >
                                                <a
                                                    @click="
                                                        showScanner(
                                                            'out',
                                                            st_index,
                                                            d.id
                                                        )
                                                    "
                                                    href="javascript:;"
                                                    class="text-sm text-blue-400 mr-2 inline-flex items-center"
                                                    v-for="(
                                                        st, st_index
                                                    ) in d.end_time"
                                                    :key="st_index"
                                                >
                                                    {{
                                                        moment(
                                                            new Date(st)
                                                        ).format("LLL")
                                                    }}
                                                </a>
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
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <JetModal :show="show_scanner">
        <template #title>{{ scanner_status }}</template>
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
