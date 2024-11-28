<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <div class="relative pt-4">
                        <button id="account-management-btn" class="flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            {{ __('Account Management') }}
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="account-management-dropdown" class="border-2 absolute left-0 z-10 hidden mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <x-dropdown-link :href="route('admin.spaceOwners')" :active="request()->routeIs('admin.spaceOwners')">
                                {{ __('Space Owner Account') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.businessOwners')" :active="request()->routeIs('admin.businessOwners')">
                                {{ __('Business Owner Account') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('admin.adminUsers')" :active="request()->routeIs('admin.adminUsers')">
                                {{ __('Admin Account') }}
                            </x-dropdown-link>
                        </div>
                    </div>
                    <x-nav-link :href="route('admin.listingmanagement')" :active="request()->routeIs('admin.listingmanagement')">
                        {{ __('Listing Management') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.payment')" :active="request()->routeIs('admin.payment')">
                        {{ __('Payment') }}
                    </x-nav-link>
                    <x-nav-link :href="route('admin.reports.dashboard')" :active="request()->routeIs('admin.reports.dashboard')">
                        {{ __('Reports') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="flex items-center space-x-4">
                <!-- Notification Icon -->
                <div class="">
                    <!-- Notification Icon -->
                    <div class="relative">
                        <i class="fa-regular fa-bell font-semibold text-gray-500 hover:text-orange-500 cursor-pointer"></i>
                        <!-- Red dot for new notifications -->
                        <span id="notification-dot" class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full hidden"></span>
                        <!-- Notification Dropdown -->
                        <div id="notification-dropdown" class="absolute right-0 mt-2 w-96 bg-white rounded-md shadow-lg py-2 z-50 hidden border border-gray-200" style="right: -50px;">
                            <div class="border-b-2 border-gray-200 px-4 py-2">
                                <button id="create-notification-btn" class="text-blue-500 hover:text-blue-700 cursor-pointer">
                                    Create Notification
                                </button>
                            </div>
                            <div id="notification-list" class="max-h-80 overflow-y-auto">
                                <p class="px-4 py-2 text-gray-800">No new notifications.</p>
                            </div>
                            <button id="see-previous-btn" class="px-4 py-2 w-full text-center text-blue-600 hover:bg-gray-100 hidden">See previous notifications</button>
                        </div>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->firstName }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="flex items-center space-x-2 py-2 px-4 text-gray-700 hover:bg-orange-100">
                                <i class="fas fa-user w-5 h-5 text-gray-500"></i>
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center space-x-2 py-2 px-4 text-gray-700 hover:bg-orange-100">
                                    <i class="fas fa-sign-out-alt w-5 h-5 text-gray-500"></i>
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.usermanagement')" :active="request()->routeIs('admin.usermanagement')">
                {{ __('User Management') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.listingmanagement')" :active="request()->routeIs('admin.listingmanagement')">
                {{ __('Listing Management') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('admin.payment')" :active="request()->routeIs('admin.payment')">
                {{ __('Payment') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->firstName }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center space-x-2 py-2 px-4 text-gray-700 hover:bg-orange-100">
                    <i class="fas fa-user w-5 h-5 text-gray-500"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt w-5 h-5 text-gray-500"></i>    
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
<!-- Modal Background -->
<div id="notification-modal" class="fixed z-50 inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden">
    <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full max-w-md sm:w-auto sm:max-w-sm">
        <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Notification</h3>
            <form id="notification-form" action="{{ route('admin.notifications.store') }}" method="POST">
                @csrf
                <div class="mt-2">
                    <label for="message" class="block text-sm font-medium text-gray-700">Notification Message</label>
                    <textarea id="message" name="message" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required></textarea>
                </div>
                <div class="mt-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Notification Type</label>
                    <select type="text" id="type" name="type" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" placeholder="e.g. General, Alert" required>
                        <option value="null">Choose..</option>
                        <option value="maintenance">Maintenance</option>
                        <option value="feedback">Feedback</option>
                        <option value="follow-up">Follow-Up</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="selectUser" class="block text-sm font-medium text-gray-700">Select User Type</label>
                    <select id="selectUser" name="selectUser" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required>
                        <option value="both" selected>All Users</option>
                        <option value="business_owner">Business Owner</option>
                        <option value="space_owner">Space Owner</option>
                    </select>
                </div>
                <div class="mt-4" id="user-list-container" class="hidden">
                    <label for="users" class="block text-sm font-medium text-gray-700">Select Specific User</label>
                    <select id="users" name="user_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md">
                        <!-- Options will be populated dynamically -->
                    </select>
                </div>
            </form>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button id="submit-notification" type="submit" form="notification-form" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                Send Notification
            </button>
            <button id="close-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
            </button>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notificationIcon = document.querySelector('.fa-bell');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const notificationDot = document.getElementById('notification-dot');
        const notificationList = document.getElementById('notification-list');
        const createNotificationBtn = document.getElementById('create-notification-btn');
        const notificationModal = document.getElementById('notification-modal');
        const closeModalBtn = document.getElementById('close-modal');
        const accountManagementBtn = document.getElementById('account-management-btn');
        const accountManagementDropdown = document.getElementById('account-management-dropdown');
        const seePreviousBtn = document.getElementById('see-previous-btn');


        let offset = 0;
        let hasPrevious = false;

        function loadNotifications() {
        fetch(`/notifications?offset=${offset}&limit=8`)
        .then(response => response.json())
        .then(data => {
            const unreadNotifications = data.filter(notification => notification.read_at === null);

            // Show or hide red dot based on unread notifications
            notificationDot.style.display = unreadNotifications.length > 0 ? 'inline-block' : 'none';

            if (data.length > 0) {
                if (offset === 0) {
                    notificationList.innerHTML = ''; // Clear notifications on first load
                }

                data.forEach(notification => {
                    // Container for each notification
                    const notificationItem = document.createElement('div');
                    notificationItem.classList.add('flex', 'items-center', 'justify-between', 'px-4', 'py-2', 'border-b');

                    // Apply background color for unread notifications
                    if (notification.read_at === null) {
                        notificationItem.style.backgroundColor = '#daccc9'; // Light gray background for unread
                    } else {
                        notificationItem.style.backgroundColor = '#fff'; // White background for read
                    }

                    // Notification link/message
                    const notificationLink = document.createElement('a');
                    notificationLink.classList.add('flex-grow', 'text-gray-800', 'hover:bg-gray-100', 'cursor-pointer', 'mr-4', 'p-2');
                    notificationLink.href = getNotificationUrl(notification);
                    notificationLink.setAttribute('data-id', notification.notificationID);

                    const notificationMessage = document.createElement('div');
                    notificationMessage.innerHTML = getNotificationMessage(notification);

                    const notificationDate = document.createElement('span');
                    notificationDate.classList.add('text-gray-400', 'text-sm', 'block');
                    notificationDate.textContent = new Date(notification.created_at).toLocaleString();

                    notificationLink.appendChild(notificationMessage);
                    notificationLink.appendChild(notificationDate);

                    // Add click event to mark as read and redirect
                    notificationLink.addEventListener('click', function (event) {
                        event.preventDefault();
                        markAsRead(notification.notificationID, notificationLink.href);
                    });

                    // Delete button
                    const deleteButton = document.createElement('button');
                    deleteButton.classList.add('text-red-600', 'hover:text-red-800');
                    deleteButton.innerHTML = '<i class="fa-solid fa-trash-alt"></i>';
                    deleteButton.addEventListener('click', () => {
                        deleteNotification(notification.notificationID, notificationItem);
                    });

                    // Append notification content and delete button
                    notificationItem.appendChild(notificationLink);
                    notificationItem.appendChild(deleteButton);

                    notificationList.appendChild(notificationItem);
                });


                // Show "See previous notifications" button if more notifications exist
                seePreviousBtn.style.display = data.length === 8 ? 'block' : 'none';
                hasPrevious = data.length === 8;
                offset += data.length;
            } else if (offset === 0) {
                notificationList.innerHTML = '<p class="px-4 py-2 text-gray-800">No new notifications.</p>';
            }
        })
        .catch(error => console.error('Error loading notifications:', error));
    } 
        function getNotificationMessage(notification) {
                if (notification.type === 'listing_approval') {
                    return 'A new listing needs approval: <strong>' + notification.data + '</strong>';
                } else if (notification.type === 'payment_submitted') {
                    return '<strong>' + notification.data + '</strong> has submitted a payment.';
                } else if (notification.type === 'payment') {
                    return 'You received a payment';
                } else if (notification.type === 'meetup_submitted') {
                    return '<strong>' + notification.data + '</strong> has submitted a proof of meetup for confirmation.';
                } else {
                    return notification.description;  // Default message
                }
                    return notification.description; // Default message
            }


            function deleteNotification(notificationID, notificationElement) {
                fetch(`/notifications/${notificationID}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => {
                    if (response.ok) {
                        // Remove the notification from the DOM
                        notificationElement.remove();
                        
                        // Show success message using SweetAlert
                        Swal.fire({
                            title: 'Deleted!',
                            text: 'The notification has been deleted successfully.',
                            icon: 'success',
                            confirmButtonText: 'OK',
                            timer: 2000, // Auto close after 2 seconds
                        });
                    } else {
                        // Show error message if the delete operation fails
                        Swal.fire({
                            title: 'Error!',
                            text: 'Failed to delete the notification. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });
                    }
                })
                .catch(error => {
                    console.error('Error deleting notification:', error);

                    // Show error alert
                    Swal.fire({
                        title: 'Error!',
                        text: 'An error occurred while deleting the notification.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                    });
                });
            }
            // Event listener for "See previous notifications" button
            seePreviousBtn.addEventListener('click', function () {
                // Enable scrolling when this button is clicked
                notificationList.classList.add('max-h-96');
                notificationList.style.overflowY = 'auto'; // Allow scrolling
                loadNotifications();
            });

            // Show/hide the dropdown on bell icon click
            notificationIcon.addEventListener('click', function () {
                notificationDropdown.classList.toggle('hidden');
                // Reset icon color if dropdown is closed
                if (notificationDropdown.classList.contains('hidden')) {
                    notificationIcon.classList.remove('text-orange-500'); // Remove highlight if dropdown is closed
                } else if (hasPrevious) {
                    // Reset the overflow property when dropdown is opened again
                    notificationList.classList.remove('max-h-96');
                    notificationList.style.overflowY = 'hidden'; // Disable scrolling until button is clicked
                }
            });

            // Hide dropdown on outside click
            document.addEventListener('click', function (event) {
                if (!notificationIcon.contains(event.target) && !notificationDropdown.contains(event.target)) {
                    notificationDropdown.classList.add('hidden');
                    notificationIcon.classList.remove('text-orange-500'); // Remove highlight if clicked outside
                }
            });

        // Function to generate the notification URL based on type
        function getNotificationUrl(notification) {
            if (notification.type === 'listing_approval') {
                return '/admin/listingmanagement';  // Adjust to the correct URL where the admin manages listings
            } else if (notification.type === 'payment_submitted') {
                return '/admin/payment';  // Adjust to the payment-related page
            } else if (notification.type === 'payment') {
                return '/admin/payment';
            } else if (notification.type === 'meetup_submitted') {
                return '/admin/payment';  // Adjust to the payment-related page
            }
            return '#';  // Default URL
        }


        // AJAX function to mark a notification as read
        function markAsRead(notificationId, redirectUrl) {
            fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ read_at: new Date() })
            })
            .then(response => response.json())
            .then(data => {
                console.log('Notification marked as read:', data);
                // Redirect to the target page after marking the notification as read
                window.location.href = redirectUrl;
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }
        createNotificationBtn.addEventListener('click', function () {
            notificationModal.classList.remove('hidden');
        });

        closeModalBtn.addEventListener('click', function () {
            notificationModal.classList.add('hidden');
        });
        notificationModal.addEventListener('click', function (event) {
            if (event.target === notificationModal) {
                notificationModal.classList.add('hidden');
            }
        });
        accountManagementBtn.addEventListener('click', function () {
        accountManagementDropdown.classList.toggle('hidden');
        });

        // Optional: Hide the dropdown if clicked outside
        document.addEventListener('click', function (event) {
        if (!accountManagementBtn.contains(event.target) && !accountManagementDropdown.contains(event.target)) {
            accountManagementDropdown.classList.add('hidden');
        }
        });
        loadNotifications();
    });

    

    document.getElementById('selectUser').addEventListener('change', function () {
    const selectedRole = this.value;
    const userListContainer = document.getElementById('user-list-container');
    const userListSelect = document.getElementById('users');

    // Clear existing options
    userListSelect.innerHTML = '<option value="">Select User</option>';

    if (selectedRole === 'both') {
        userListContainer.classList.add('hidden');
        return; // Stop further execution if 'All Users' is selected
    }

    // Show user list container if specific roles are selected
    userListContainer.classList.remove('hidden');

    // Fetch users for the selected role
    fetch(`/admin/dashboard/${selectedRole}`)
        .then(response => response.json())
        .then(data => {
            userListSelect.innerHTML = '<option value="">Select User</option>';
            data.forEach(user => {
                const option = document.createElement('option');
                option.value = user.userID; // Ensure correct `id` is set
                option.textContent = `${user.firstName} ${user.lastName}`;
                userListSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error fetching users:', error);
            userListSelect.innerHTML = '<option value="">Error loading users</option>';
        });
});

    
</script>

