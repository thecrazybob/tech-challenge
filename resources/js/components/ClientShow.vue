<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }}</h1>

        <div class="flex">
            <div class="w-1/3 mr-5">
                <div class="w-full bg-white rounded p-4">
                    <h2>Client Info</h2>
                    <table>
                        <tbody>
                            <tr>
                                <th class="text-gray-600 pr-3">Name</th>
                                <td>{{ client.name }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Email</th>
                                <td>{{ client.email }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Phone</th>
                                <td>{{ client.phone }}</td>
                            </tr>
                            <tr>
                                <th class="text-gray-600 pr-3">Address</th>
                                <td>{{ client.address }}<br/>{{ client.postcode + ' ' + client.city }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-2/3">
                <div>
                    <button class="btn" :class="{'btn-primary': currentTab == 'bookings', 'btn-default': currentTab != 'bookings'}" @click="switchTab('bookings')">Bookings</button>
                    <button class="btn" :class="{'btn-primary': currentTab == 'journals', 'btn-default': currentTab != 'journals'}" @click="switchTab('journals')">Journals</button>
                </div>

                <!-- Bookings -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'bookings'">
                    <h3 class="mb-3">List of client bookings</h3>

                    <div class="mb-4">
                        <select v-model="bookingsFilter" class="form-control">
                            <option value="all">All bookings</option>
                            <option value="future">Future bookings only</option>
                            <option value="past">Past bookings only</option>
                        </select>
                    </div>

                    <template v-if="filteredBookings.length">
                        <table>
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Notes</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="booking in filteredBookings" :key="booking.id">
                                    <td>{{ formatBookingTime(booking.start, booking.end) }}</td>
                                    <td>{{ booking.notes }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-else>
                        <p class="text-center">No bookings matching this filter.</p>
                    </template>

                </div>

                <!-- Journals -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'journals'">
                    <h3 class="mb-3">List of client journals</h3>

                    <textarea v-model="newJournalContent" placeholder="Enter new journal content here..."></textarea>
                    <button class="btn btn-primary" @click="createJournal()">Add Journal Entry</button>

                    <div v-if="client.journals && client.journals.length">
                        <tr v-for="journal in client.journals" :key="journal.id">
                            <p><strong>Date:</strong> {{ journal.entry_date }}</p>
                            <p><strong>Entry:</strong> {{ journal.content }}</p>
                            <button class="btn btn-danger" @click="deleteJournal(journal)">Delete</button>
                            <hr/>
                        </tr>
                    </div>

                    <div v-else>
                        <p>No journal entries yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientShow',

    props: ['client'],

    data() {
        return {
            currentTab: 'bookings',
            bookingsFilter: 'all',
            newJournalContent: "",
        }
    },

    computed: {
        filteredBookings() {
            const now = new Date();
            switch (this.bookingsFilter) {
                case 'future':
                    return this.client.bookings.filter(booking => new Date(booking.start) > now);
                case 'past':
                    return this.client.bookings.filter(booking => new Date(booking.start) < now);
                default:
                    return this.client.bookings;
            }
        }
    },

    methods: {
        switchTab(newTab) {
            this.currentTab = newTab;
        },

        deleteBooking(booking) {
            axios.delete(`/bookings/${booking.id}`);
        },

        formatBookingTime(start, end) {
            const startDateTime = new Date(start);
            const endDateTime = new Date(end);

            const formatDate = date => date.toLocaleDateString('en-GB', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });

            const formatTime = date => date.toLocaleTimeString('en-GB', {
                hour: '2-digit', minute: '2-digit', hour12: false
            });

            return `${formatDate(startDateTime)}, ${formatTime(startDateTime)} to ${formatTime(endDateTime)}`;
        },

        createJournal() {
            axios.post(`/clients/${this.client.id}/journals`, {
                content: this.newJournalContent
            }).then(response => {
                this.client.journals.push(response.data);
                this.newJournalContent = "";  // Reset the input
            }).catch(error => console.error('Error posting journal:', error));
        },

        deleteJournal(journal) {
            axios.delete(`/clients/${this.client.id}/journals/${journal.id}`)
                .then(() => {
                    this.client.journals = this.client.journals.filter(j => j.id !== journal.id);
                }).catch(error => console.error('Error deleting journal:', error));
        },

    }
}
</script>
