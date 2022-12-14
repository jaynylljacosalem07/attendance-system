<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Components/PrimaryButton.vue";
import JetModal from "@/Components/DialogModal.vue";
import JetPrimaryButton from "@/Components/PrimaryButton.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetInputError from "@/Components/InputError.vue";
import JetConfirmModal from "@/Components/ConfirmationModal.vue";
import { useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

const toast = useToast({
    position: "bottom-right",
    timeout: 1500,
});
const props = defineProps(["students", "course"]);
const show_add_dialog = ref(false);
const edit_student = ref(false);
const confirm_delete = ref(false);
const form = useForm({
    id: "",
    name: "",
    email: "",
    parent_name: "",
    birthday: "",
    address: "",
    phone_number: "",
    gender: "",
    password: "",
    password_confirmation: "",
    course: "",
    year_level: "",
});

const addStudent = () => {
    let str_route = edit_student.value ? "students.update" : "students.store";
    form.post(route(str_route), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast(
                `Student successfully ${
                    edit_student.value ? "updated" : "added"
                }`
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
    form.parent_name = student.parent_name;
    form.birthday = student.birthday;
    form.address = student.address;
    form.phone_number = student.phone_number;
    form.gender = student.gender;
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
    form.post(route("students.delete"), {
        preserveScroll: true,
        errorBag: "deleteStudent",
        onSuccess: () => {
            toast(`Student successfully removed`);
            confirm_delete.value = false;
            form.reset();
        },
        onError: (e) => {
            console.log(e);
        },
    });
};
</script>
<template>
    <AppLayout title="Students">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Students
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
                Add Students
            </button>
            <div class="mt-1 overflow-hidden bg-white">
                <div class="c overflow-auto">
                    <table class="min-w-full">
                        <thead class="bg-white border-b">
                            <tr>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                >
                                    ID Number
                                </th>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                >
                                    Name
                                </th>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                >
                                    Email
                                </th>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                >
                                    Contact Number
                                </th>
                                <th
                                    scope="col"
                                    class="text-sm font-medium text-gray-900 px-6 py-4 text-left"
                                >
                                    Address
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
                                v-for="(student, index) in props.students.data"
                                :key="index"
                                class="border-b bg-white"
                            >
                                <td
                                    class="px-2 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ student.id_number }}
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                >
                                    {{ student.name }}
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-3 py-4 whitespace-pre-wrap"
                                >
                                    {{ student.email }}
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                >
                                    {{ student.phone_number }}
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-pre-wrap"
                                >
                                    {{ student.address }}
                                </td>
                                <td
                                    class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap"
                                >
                                    <button @click="showEditStudent(student)">
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
                                    <button @click="showConfirmModal(student)">
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
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                            />
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td
                                    v-if="props.students.data.length === 0"
                                    colspan="5"
                                    class="uppercase py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap text-center"
                                >
                                    No result found
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
    <JetModal
        :show="show_add_dialog"
        @close="show_add_dialog = false"
        maxWidth="2xl"
    >
        <template #title>Add Students</template>
        <template #content>
            <div class="shadow sm:overflow-hidden sm:rounded-md">
                <div class="space-y-6 bg-white px-4 py-5 sm:p-6">
                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700"
                            >Name</label
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
                            for="gender"
                            class="block text-sm font-medium text-gray-700"
                            >Gender</label
                        >
                        <select
                            v-model="form.gender"
                            name="gender"
                            id="gender"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                        <JetInputError
                            :message="form.errors.addStudents?.gender"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="gender"
                            class="block text-sm font-medium text-gray-700"
                            >Course</label
                        >
                        <select
                            v-model="form.course"
                            name="gender"
                            id="gender"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option
                                v-for="(c, c_index) in course"
                                :key="c_index"
                                :value="c.id"
                            >
                                {{ c.name }}
                            </option>
                        </select>
                        <JetInputError
                            :message="form.errors.addStudents?.course"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="gender"
                            class="block text-sm font-medium text-gray-700"
                            >Year</label
                        >
                        <select
                            v-model="form.year_level"
                            name="gender"
                            id="gender"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        >
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                        <JetInputError
                            :message="form.errors.addStudents?.year_level"
                            class="mt-2"
                        />
                    </div>
                    <div v-if="!edit_student">
                        <label
                            for="email"
                            class="block text-sm font-medium text-gray-700"
                            >Email</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            name="email"
                            id="email"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <JetInputError
                            :message="form.errors.addStudents?.email"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="birthday"
                            class="block text-sm font-medium text-gray-700"
                            >Birthday</label
                        >
                        <input
                            v-model="form.birthday"
                            type="date"
                            name="birthday"
                            id="birthday"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <JetInputError
                            :message="form.errors.addStudents?.birthday"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="address"
                            class="block text-sm font-medium text-gray-700"
                            >Address</label
                        >
                        <textarea
                            v-model="form.address"
                            type="text"
                            name="address"
                            id="address"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        ></textarea>
                        <JetInputError
                            :message="form.errors.addStudents?.address"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <label
                            for="parent_name"
                            class="block text-sm font-medium text-gray-700"
                            >Parent's/Guardian's Name</label
                        >
                        <input
                            v-model="form.parent_name"
                            type="text"
                            name="parent_name"
                            id="parent_name"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <JetInputError
                            :message="form.errors.addStudents?.parent_name"
                            class="mt-2"
                        />
                    </div>
                    <div>
                        <label
                            for="phone_number"
                            class="block text-sm font-medium text-gray-700"
                            >Contact Number</label
                        >
                        <input
                            v-model="form.phone_number"
                            type="number"
                            name="phone_number"
                            id="phone_number"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <JetInputError
                            :message="form.errors.addStudents?.phone_number"
                            class="mt-2"
                        />
                    </div>
                    <div v-if="!edit_student">
                        <label
                            for="password"
                            class="block text-sm font-medium text-gray-700"
                            >Password</label
                        >
                        <input
                            v-model="form.password"
                            type="password"
                            name="password"
                            id="password"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <JetInputError
                            :message="form.errors.addStudents?.password"
                            class="mt-2"
                        />
                    </div>
                    <div v-if="!edit_student">
                        <label
                            for="password_confirmation"
                            class="block text-sm font-medium text-gray-700"
                            >Verify Password</label
                        >
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            name="password_confirmation"
                            id="password_confirmation"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                        />
                        <JetInputError
                            :message="
                                form.errors.addStudents?.password_confirmation
                            "
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
                class="text-sm font-small inline-block px-6 py-2.5 bg-blue-600 text-white text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                :class="{ 'opacity-25': form?.processing }"
                :disabled="form?.processing"
                @click="addStudent"
                >{{ edit_student ? "Update" : "Create" }}
            </JetPrimaryButton></template
        >
    </JetModal>
    <JetConfirmModal :show="confirm_delete" @close="showConfirmModal = false">
        <template #title>Confirm Delete</template>
        <template #content
            >Are you sure you want to delete this stutent?</template
        >
        <template #footer>
            <JetSecondaryButton @click="confirm_delete = false"
                >Cancel</JetSecondaryButton
            >
            <JetPrimaryButton @click="confirmDelete">Confirm</JetPrimaryButton>
        </template>
    </JetConfirmModal>
</template>
