<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Components/PrimaryButton.vue";
import JetModal from "@/Components/DialogModal.vue";
import JetPrimaryButton from "@/Components/PrimaryButton.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetInputError from "@/Components/InputError.vue";
import JetConfirmModal from "@/Components/ConfirmationModal.vue";
import { ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

const props = defineProps(["students"]);
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
});

const addStudent = () => {
    let str_route = edit_student.value ? "students.update" : "students.store";
    form.post(route(str_route), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            alert(
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
            alert(`Student successfully removed`);
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
        <div class="py-12">
            <button
                class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                @click="show_add_dialog = true"
            >
                Add Students
            </button>
            <div class="mt-1 overflow-hidden bg-white mb-2">
                <div class="c overflow-auto">
                    <table
                        class="min-w-full divide-y divide-gray-200 table-fixed"
                    >
                        <thead
                            class="text-xs text-gray-700 uppercase bg-gray-50"
                        >
                            <tr>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Name
                                </th>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Email
                                </th>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Contact Number
                                </th>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Address
                                </th>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="(student, index) in props.students.data"
                                :key="index"
                                class="hover:bg-gray-100"
                            >
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ student.name }}
                                </td>
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ student.email }}
                                </td>
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ student.phone_number }}
                                </td>
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ student.address }}
                                </td>
                                <td>
                                    <button @click="showEditStudent(student)">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-5"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            data-v-0e807ea5=""
                                        >
                                            <path
                                                d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"
                                                data-v-0e807ea5=""
                                            ></path>
                                            <path
                                                fill-rule="evenodd"
                                                d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"
                                                clip-rule="evenodd"
                                                data-v-0e807ea5=""
                                            ></path>
                                        </svg>
                                    </button>
                                    <button @click="showConfirmModal(student)">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-4 w-5"
                                            viewBox="0 0 20 20"
                                            fill="currentColor"
                                            data-v-0e807ea5=""
                                        >
                                            <path
                                                fill-rule="evenodd"
                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                clip-rule="evenodd"
                                                data-v-0e807ea5=""
                                            ></path>
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
