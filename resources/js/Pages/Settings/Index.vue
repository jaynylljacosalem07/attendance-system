<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetPrimaryButton from "@/Components/PrimaryButton.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetConfirmModal from "@/Components/ConfirmationModal.vue";
import { useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";
import { useForm } from "@inertiajs/inertia-vue3";
import { ref } from "@vue/reactivity";
const props = defineProps(["school", "courses"]);
const form = useForm({
    id: "",
    name: "",
    address: "",
    website: "",
    email: "",
    logo: "",
});
const toast = useToast({
    position: "bottom-right",
    timeout: 1500,
});
const form_courses = useForm({
    courses: [],
});
const course_title = ref({ id: "", name: "" });
const edit_course = ref(false);
const confirm_delete = ref(false);

const saveSettings = () => {
    for (const key in props.school) {
        if (key !== "logo") form[key] = props.school[key];
    }
    form.post(route("settings.update"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast(`Setting successfully saved`);
            form.reset();
        },
        onError: (e) => {
            console.log(e);
        },
    });
};

const saveCourses = () => {
    form_courses.courses = props.courses.data;
    form_courses.post(route("settings.updateCourses"), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast(`Course successfully saved`);
            form.reset();
        },
        onError: (e) => {
            console.log(e);
        },
    });
};

const editCourseTitle = (course) => {
    // edit_course.value = true;
    course_title.value = course;
};

const addCourseProcess = () => {
    if (edit_course.value) {
        edit_course.value = false;
        course_title.value.name = "";
    } else {
        if (course_title.value.name.trim() !== "")
            props.courses.data.push({
                name: course_title.value.name,
            });
    }
};
const showConfirmModal = (course) => {
    confirm_delete.value = true;
    form.id = course.id;
};
const confirmDelete = () => {
    form.post(route("settings.deleteCourse"), {
        preserveScroll: true,
        errorBag: "deleteCourse",
        onSuccess: () => {
            toast(`Course successfully removed`);
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
    <AppLayout title="Settings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Settings
            </h2>
        </template>
        <div class="py-12 mb-5 mx-28">
            <div>
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                School Information
                            </h3>
                            <!-- <p class="mt-1 text-sm text-gray-600">
                                This information will be displayed publicly so
                                be careful what you share.
                            </p> -->
                        </div>
                    </div>
                    <div class="mt-5 md:col-span-2 md:mt-0">
                        <form @submit.prevent="submit">
                            <div
                                class="shadow sm:overflow-hidden sm:rounded-md"
                            >
                                <div
                                    class="space-y-6 bg-white px-4 py-5 sm:p-6"
                                >
                                    <div>
                                        <label
                                            for="name"
                                            class="block text-sm font-medium text-gray-700"
                                            >Name</label
                                        >
                                        <input
                                            type="text"
                                            name="name"
                                            id="name"
                                            v-model="school.name"
                                            autocomplete="given-name"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                        />
                                    </div>
                                    <div class="grid grid-cols-3 gap-6">
                                        <div class="col-span-3 sm:col-span-2">
                                            <label
                                                for="company-website"
                                                class="block text-sm font-medium text-gray-700"
                                                >Website</label
                                            >
                                            <div
                                                class="mt-1 flex rounded-md shadow-sm"
                                            >
                                                <span
                                                    class="inline-flex items-center rounded-l-md border border-r-0 border-gray-300 bg-gray-50 px-3 text-sm text-gray-500"
                                                    >http://</span
                                                >
                                                <input
                                                    type="text"
                                                    name="company-website"
                                                    v-model="school.website"
                                                    id="company-website"
                                                    class="block w-full flex-1 rounded-none rounded-r-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                                    placeholder="www.example.com"
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label
                                            for="about"
                                            class="block text-sm font-medium text-gray-700"
                                            >Address</label
                                        >
                                        <div class="mt-1">
                                            <textarea
                                                id="about"
                                                v-model="school.address"
                                                name="about"
                                                rows="3"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            ></textarea>
                                        </div>
                                    </div>

                                    <!-- <div>
                                        <label
                                            class="block text-sm font-medium text-gray-700"
                                            >Photo</label
                                        >
                                        <div class="mt-1 flex items-center">
                                            <span
                                                class="inline-block h-12 w-12 overflow-hidden rounded-full bg-gray-100"
                                            >
                                                <svg
                                                    class="h-full w-full text-gray-300"
                                                    fill="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z"
                                                    />
                                                </svg>
                                            </span>
                                            <label class="block">
                                                <span class="sr-only"
                                                    >Choose profile photo</span
                                                >
                                                <input
                                                    type="file"
                                                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
                                                    @input="
                                                        form.logo =
                                                            $event.target.files[0]
                                                    "
                                                />
                                            </label>
                                        </div>
                                    </div> -->
                                </div>
                                <div
                                    class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                >
                                    <button
                                        @click="saveSettings"
                                        type="submit"
                                        class="text-sm font-small inline-block px-6 py-2.5 bg-blue-600 text-white text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="hidden sm:block" aria-hidden="true">
                <div class="py-5">
                    <div class="border-t border-gray-200"></div>
                </div>
            </div>

            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3
                                class="text-lg font-medium leading-6 text-gray-900"
                            >
                                Courses
                            </h3>
                            <!-- <p class="mt-1 text-sm text-gray-600">
                                Use a permanent address where you can receive
                                mail.
                            </p> -->
                        </div>
                    </div>
                    <div class="mt-5 md:col-span-2 md:mt-0">
                        <form @submit.prevent="submit">
                            <div class="overflow-hidden shadow sm:rounded-md">
                                <div class="bg-white px-4 py-5 sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6">
                                            <label
                                                for="course_title"
                                                class="block text-sm font-medium text-gray-700"
                                                >Course Title</label
                                            >
                                            <input
                                                type="text"
                                                name="course_title"
                                                id="course_title"
                                                v-model="course_title.name"
                                                autocomplete="course_title"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                            />
                                        </div>
                                        <div class="col-span-6">
                                            <button
                                                @click="addCourseProcess"
                                                class="text-sm font-small inline-block px-6 py-2.5 bg-blue-600 text-white text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                                            >
                                                {{
                                                    edit_course
                                                        ? "Update"
                                                        : "Add"
                                                }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div>
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
                                                    ID
                                                </th>
                                                <th
                                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                                >
                                                    Course Title
                                                </th>

                                                <th
                                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                                >
                                                    Edit
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-gray-200"
                                        >
                                            <tr
                                                v-for="(
                                                    course, course_key
                                                ) in courses.data"
                                                :key="course_key"
                                            >
                                                <td
                                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                                >
                                                    {{ course?.id }}
                                                </td>
                                                <td
                                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                                >
                                                    {{ course.name }}
                                                </td>
                                                <td
                                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                                >
                                                    <button
                                                        @click="
                                                            editCourseTitle(
                                                                course
                                                            )
                                                        "
                                                    >
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
                                                    <button
                                                        @click="
                                                            showConfirmModal(
                                                                course
                                                            )
                                                        "
                                                    >
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
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="bg-gray-50 px-4 py-3 text-right sm:px-6"
                                >
                                    <button
                                        @click="saveCourses"
                                        class="text-sm font-small inline-block px-6 py-2.5 bg-blue-600 text-white text-xs leading-tight uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
    <JetConfirmModal :show="confirm_delete" @close="showConfirmModal = false">
        <template #title>Confirm Delete</template>
        <template #content
            >Are you sure you want to delete this course?</template
        >
        <template #footer>
            <JetSecondaryButton @click="confirm_delete = false"
                >Cancel</JetSecondaryButton
            >
            <JetPrimaryButton @click="confirmDelete">Confirm</JetPrimaryButton>
        </template>
    </JetConfirmModal>
</template>
