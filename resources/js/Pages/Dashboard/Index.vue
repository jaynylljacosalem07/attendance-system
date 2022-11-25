<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import Welcome from "@/Components/Welcome.vue";
import moment from "moment";
import { ref } from "vue";

const props = defineProps({
    events: Object,
});

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
</script>
<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <div class="sm:rounded-md">
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
                                                Start Date
                                            </div>
                                            <div
                                                class="col-span-3 text-sm text-gray-600 pt-2 pb-3"
                                            >
                                                {{
                                                    moment(
                                                        new Date(d.start_time)
                                                    ).format("MMM Do YY h:mm a")
                                                }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                End Date
                                            </div>
                                            <div
                                                class="col-span-3 text-sm text-gray-600 pt-2 pb-3"
                                            >
                                                {{
                                                    moment(
                                                        new Date(d.end_time)
                                                    ).format("MMM Do YY h:mm a")
                                                }}
                                            </div>
                                        </div>
                                    </div>
                                    <div
                                        class="border-t border-gray-300 p-2 mt-auto"
                                    >
                                        <a
                                            @click="handleDelete(d)"
                                            href="javascript:;"
                                            class="text-sm text-blue-400 mr-2 inline-flex items-center"
                                        >
                                            Check in </a
                                        ><a
                                            @click="handleEdit(d)"
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
</template>
