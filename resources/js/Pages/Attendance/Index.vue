<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Components/PrimaryButton.vue";
import JetModal from "@/Components/DialogModal.vue";
import JetPrimaryButton from "@/Components/PrimaryButton.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetInputError from "@/Components/InputError.vue";
import JetConfirmModal from "@/Components/ConfirmationModal.vue";
import Multiselect from "@vueform/multiselect";
import { useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";
import moment from "moment";
import { onMounted, reactive, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";
import exportFromJSON from "export-from-json";

const toast = useToast({
    position: "bottom-right",
    timeout: 1500,
});
const data = [{ foo: "foo" }, { bar: "bar" }];
const fileName = "download";
const exportType = exportFromJSON.types.csv;

const props = defineProps(["sheets", "course"]);
const show_add_dialog = ref(false);
const edit_student = ref(false);
const confirm_delete = ref(false);
const delete_entries = ref({
    in: [],
    out: [],
});

const form = useForm({
    id: "",
    name: "",
    start_time: [],
    start_date: Date,
    end_time: [],
    end_date: Date,
    course: [],
    year_level: [],
});

const year_levels = reactive([
    { label: "1st Year", value: 1 },
    { label: "2nd Year", value: 2 },
    { label: "3rd Year", value: 3 },
    { label: "4th Year", value: 4 },
]);
// const year_levels = reactive(["1st Year", "2nd Year", "3rd Year", "4th Year"]);
const addStudent = () => {
    let str_route = edit_student.value
        ? "attendance.update"
        : "attendance.store";
    form.post(route(str_route), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast(
                `Event successfully ${edit_student.value ? "updated" : "added"}`
            );
            if (edit_student.value) edit_student.value = !edit_student.value;
            show_add_dialog.value = false;
            form.reset();
        },
        onError: (e) => {
            console.log(e);
        },
    });
};

const showEditStudent = (student) => {
    edit_student.value = true;

    form.id = student.id;
    form.name = student.name;
    form.start_time = student.start_time;
    form.end_time = student.end_time;
    form.course = student.course_id;
    form.year_level = student.year_level;

    show_add_dialog.value = true;
    console.log(student);
};
const showConfirmModal = (student) => {
    confirm_delete.value = true;
    form.id = student.id;
};
const confirmDelete = () => {
    form.post(route("attendance.delete", { id: form.id }), {
        preserveScroll: true,
        errorBag: "deleteStudent",
        onSuccess: () => {
            toast(`Event successfully closed`);
            confirm_delete.value = false;
            form.reset();
        },
        onError: (e) => {
            console.log(e);
        },
    });
};

const removeTime = (t) => {
    switch (t) {
        case "start_time":
            delete_entries.value.in.forEach((element, key) => {
                console.log(element, key);
                if (element == true) form.start_time.splice(key, 1);
            });
            break;
        case "end_time":
            delete_entries.value.out.forEach((element, key) => {
                console.log(element, key);
                if (element == true) form.end_time.splice(key, 1);
            });
            break;
    }
};

onMounted(() => {
    // exportFromJSON({ data, fileName, exportType });
});
</script>
<template>
    <AppLayout title="Students">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Events
            </h2>
        </template>
        <div class="py-12 mb-5 mx-28">
            <button
                @click="show_add_dialog = true"
                type="button"
                data-mdb-ripple="true"
                data-mdb-ripple-color="light"
                class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
            >
                Add Events
            </button>

            <div class="flex flex-col">
                <div class="overflow-x-auto sm:-mx-6 lg:-mx-8 md:-mx-6">
                    <div class="py-2 inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="overflow-hidden">
                            <table class="min-w-full">
                                <thead class="bg-white border-b">
                                    <tr>
                                        <th
                                            scope="col"
                                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                        >
                                            Event Name
                                        </th>
                                        <th
                                            scope="col"
                                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                        >
                                            Starting Date
                                        </th>
                                        <th
                                            scope="col"
                                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                        >
                                            End Date
                                        </th>
                                        <th
                                            scope="col"
                                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                        >
                                            Status
                                        </th>
                                        <th
                                            scope="col"
                                            class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="border-b bg-white"
                                        v-for="(student, index) in props.sheets
                                            .data"
                                        :key="index"
                                    >
                                        <td
                                            class="py-3 px-6 text-sm font-medium text-gray-500 whitespace-nowrap"
                                        >
                                            {{ student.name }}
                                        </td>
                                        <td
                                            class="py-3 px-6 text-sm font-medium text-gray-500 whitespace-pre-wrapp"
                                        >
                                            <!-- <div class="flex justify-center"> -->
                                            <ul
                                                class="bg-white rounded-lg text-gray-900"
                                            >
                                                <li
                                                    v-for="(
                                                        st, st_index
                                                    ) in student.start_time"
                                                    :key="st_index"
                                                >
                                                    {{
                                                        `${
                                                            st_index + 1
                                                        }.)  ${moment(
                                                            new Date(st)
                                                        ).format("LLL")}`
                                                    }}
                                                </li>
                                            </ul>
                                            <!-- </div> -->
                                        </td>
                                        <td
                                            class="py-3 px-6 text-sm font-medium text-gray-500 whitespace-pre-wrap"
                                        >
                                            <div class="flex justify-center">
                                                <ul
                                                    class="bg-white rounded-lg text-gray-900"
                                                >
                                                    <li
                                                        v-for="(
                                                            st, st_index
                                                        ) in student.end_time"
                                                        :key="st_index"
                                                    >
                                                        {{
                                                            `${
                                                                st_index + 1
                                                            }.)  ${moment(
                                                                new Date(st)
                                                            ).format("LLL")}`
                                                        }}
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td
                                            class="py-3 px-6 text-sm font-medium text-gray-500 whitespace-nowrap"
                                        >
                                            {{
                                                student.status == null
                                                    ? "Open"
                                                    : "Close"
                                            }}
                                        </td>
                                        <td
                                            class="py-3 px-6 text-sm font-medium text-gray-500 whitespace-nowrap"
                                        >
                                            <button
                                                @click="
                                                    showEditStudent(student)
                                                "
                                                title="Edit"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-6 h-6"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                                                    />
                                                </svg>
                                            </button>
                                            <button
                                                @click="
                                                    showConfirmModal(student)
                                                "
                                                title="Close"
                                            >
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke-width="1.5"
                                                    stroke="currentColor"
                                                    class="w-6 h-6"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"
                                                    />
                                                </svg>
                                            </button>
                                            <button>
                                                <a
                                                    title="Reports"
                                                    :href="
                                                        route(
                                                            'report.index',
                                                            student.id
                                                        )
                                                    "
                                                >
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke-width="1.5"
                                                        stroke="currentColor"
                                                        class="w-6 h-6"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z"
                                                        />
                                                    </svg>
                                                </a>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <JetModal
        :show="show_add_dialog"
        @close="show_add_dialog = false"
        maxWidth="2xl"
    >
        <template #title>Add Event</template>
        <template #content>
            <div class="shadow sm:rounded-md sm:rounded-md">
                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                            >Event Name</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            name="name"
                            id="name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <JetInputError
                            :message="form.errors.addStudents?.name"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <label
                            for="start"
                            class="block text-sm font-medium text-gray-700"
                            >Time in</label
                        >
                        <input
                            type="datetime-local"
                            name="start"
                            id="start"
                            class="mb-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            v-model="form.start_date"
                        />
                        <button
                            @click="form.start_time.push(form.start_date)"
                            class="mr-1 inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                        >
                            Add
                        </button>
                        <button
                            @click="removeTime('start_time')"
                            class="inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                        >
                            Delete
                        </button>
                        <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                            <div>
                                <h3 class="text-base font-medium text-gray-500">
                                    Time in
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div
                                        v-for="(
                                            ti, ti_index
                                        ) in form.start_time"
                                        :key="ti_index"
                                        class="flex items-start"
                                    >
                                        <div class="flex h-5 items-center">
                                            <input
                                                v-model="
                                                    delete_entries.in[ti_index]
                                                "
                                                id="start_date"
                                                name="start_date"
                                                type="checkbox"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            />
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label
                                                for="comments"
                                                class="font-medium text-gray-700"
                                            >
                                                {{
                                                    moment(new Date(ti)).format(
                                                        "LLLL"
                                                    )
                                                }}</label
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <JetInputError
                            :message="form.errors.addStudents?.start_time"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="end"
                            class="block text-sm font-medium text-gray-700"
                            >Time out</label
                        >
                        <input
                            type="datetime-local"
                            name="end"
                            id="end"
                            class="mb-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            v-model="form.end_date"
                        />
                        <button
                            @click="form.end_time.push(form.end_date)"
                            class="mr-1 text-sm font-small inline-block px-6 py-2.5 bg-blue-600 text-white text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                        >
                            Add
                        </button>
                        <button
                            @click="removeTime('end_time')"
                            class="text-sm font-small inline-block px-6 py-2.5 bg-blue-600 text-white text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                        >
                            Delete
                        </button>
                        <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                            <div>
                                <h3 class="text-base font-medium text-gray-500">
                                    Time out
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div
                                        v-for="(ti, ti_index) in form.end_time"
                                        :key="ti_index"
                                        class="flex items-start"
                                    >
                                        <div class="flex h-5 items-center">
                                            <input
                                                v-model="
                                                    delete_entries.out[ti_index]
                                                "
                                                id="end_time"
                                                name="end_time"
                                                type="checkbox"
                                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            />
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label
                                                for="comments"
                                                class="font-medium text-gray-700"
                                            >
                                                {{
                                                    moment(new Date(ti)).format(
                                                        "LLLL"
                                                    )
                                                }}</label
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <JetInputError
                            :message="form.errors.addStudents?.end_time"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="course"
                            class="block text-sm font-medium text-gray-700"
                            >Course</label
                        >
                        <multiselect
                            mode="tags"
                            v-model="form.course"
                            :options="
                                course.map((o) => {
                                    return { label: o.name, value: o.id };
                                })
                            "
                        ></multiselect>

                        <!-- <select
                            v-model="form.year_levels"
                            name="year_levels"
                            id="year_levels"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option selected value="0">All</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select> -->
                        <JetInputError
                            :message="form.errors.addStudents?.year_levels"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="year_levels"
                            class="block text-sm font-medium text-gray-700"
                            >Year Level</label
                        >
                        <multiselect
                            mode="tags"
                            :close-on-select="false"
                            v-model="form.year_level"
                            :options="year_levels"
                        ></multiselect>

                        <!-- <select
                            v-model="form.year_levels"
                            name="year_levels"
                            id="year_levels"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option selected value="0">All</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select> -->
                        <JetInputError
                            :message="form.errors.addStudents?.year_levels"
                            class="mt-2"
                        />
                    </div>
                </div></div
        ></template>
        <template #footer>
            <JetSecondaryButton @click="show_add_dialog = false" class="mr-2">
                Cancel
            </JetSecondaryButton>
            <JetPrimaryButton
                class="text-sm font-small inline-block px-6 py-2.5 bg-blue-600 text-white font-medium text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                :class="{ 'opacity-25': form?.processing }"
                :disabled="form?.processing"
                @click="addStudent"
                >{{ edit_student ? "Update" : "Add" }}
            </JetPrimaryButton></template
        >
    </JetModal>
    <JetConfirmModal :show="confirm_delete" @close="showConfirmModal = false">
        <template #title>Confirm Close</template>
        <template #content>Are you sure you want to close this event?</template>
        <template #footer>
            <JetSecondaryButton @click="confirm_delete = false"
                >Cancel</JetSecondaryButton
            >
            <JetPrimaryButton @click="confirmDelete">Confirm</JetPrimaryButton>
        </template>
    </JetConfirmModal>
</template>
<style src="@vueform/multiselect/themes/default.css"></style>
