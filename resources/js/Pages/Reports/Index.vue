<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import JetButton from "@/Components/PrimaryButton.vue";
import JetModal from "@/Components/DialogModal.vue";
import JetPrimaryButton from "@/Components/PrimaryButton.vue";
import JetSecondaryButton from "@/Components/SecondaryButton.vue";
import JetInputError from "@/Components/InputError.vue";
import JetConfirmModal from "@/Components/ConfirmationModal.vue";
import Multiselect from "@vueform/multiselect";
import moment from "moment";
import { reactive, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

const props = defineProps(["data", "event"]);

const year_levels = reactive([
    { label: "1st Year", value: 1 },
    { label: "2nd Year", value: 2 },
    { label: "3rd Year", value: 3 },
    { label: "4th Year", value: 4 },
]);
</script>
<template>
    <AppLayout title="Reports">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Reports
            </h2>
        </template>
        <div class="py-12">
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
                                    No.
                                </th>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Name
                                </th>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Course
                                </th>
                                <th
                                    class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                >
                                    Year Level
                                </th>
                                <template
                                    v-for="(st, st_index) in event.start_time"
                                    :key="st_index"
                                >
                                    <th
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                    >
                                        In #{{ st_index + 1 }}
                                    </th>

                                    <th
                                        class="py-3 px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase"
                                    >
                                        Out #{{ st_index + 1 }}
                                    </th>
                                </template>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="(d, index) in data"
                                :key="index"
                                class="hover:bg-gray-100"
                            >
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ d.user.name }}
                                </td>
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{ d.course.name }}
                                </td>
                                <td
                                    class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                >
                                    {{
                                        year_levels.find((o) => {
                                            return o.value == d.year_level_id;
                                        }).label
                                    }}
                                </td>
                                <template
                                    v-for="(st, st_index) in d.in"
                                    :key="st_index"
                                >
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                    >
                                        {{
                                            moment(new Date(st)).format("LLLL")
                                        }}
                                    </td>
                                    <td
                                        class="py-4 px-6 text-sm font-medium text-gray-900 whitespace-nowrap"
                                    >
                                        {{
                                            moment(
                                                new Date(d.out[st_index])
                                            ).format("LLLL")
                                        }}
                                    </td>
                                </template>
                            </tr>
                            <tr>
                                <td
                                    v-if="data.length === 0"
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
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            v-model="form.start_date"
                        />
                        <button
                            @click="form.start_time.push(form.start_date)"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-1 px-3 mt-1 mb-1 text-sm font-small text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Add
                        </button>
                        <button
                            @click="removeTime('start_time')"
                            class="ml-1 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-1 px-3 mt-1 mb-1 text-sm font-small text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            v-model="form.end_date"
                        />
                        <button
                            @click="form.end_time.push(form.end_date)"
                            class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-1 px-3 mt-1 mb-1 text-sm font-small text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Add
                        </button>
                        <button
                            @click="removeTime('end_time')"
                            class="ml-1 inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-1 px-3 mt-1 mb-1 text-sm font-small text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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
