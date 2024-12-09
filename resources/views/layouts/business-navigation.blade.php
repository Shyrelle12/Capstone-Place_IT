<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('business.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('business.dashboard')" :active="request()->routeIs('business.dashboard')" class="ajax-link">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('business.negotiations')" :active="request()->routeIs('business.negotiations')" class="ajax-link">
                        {{ __('Negotiations') }}
                    </x-nav-link>
                    <x-nav-link :href="route('business.reports')" :active="request()->routeIs('business.reports')" class="ajax-link">
                        {{ __('Payment Reports') }}
                    </x-nav-link>
                    <x-nav-link :href="route('business.bookinghistory')" :active="request()->routeIs('business.bookinghistory')" class="ajax-link">
                        {{ __('Booking History') }}
                    </x-nav-link>
                    <x-nav-link :href="route('business.feedback')" :active="request()->routeIs('business.feedback')" class="ajax-link">
                        {{ __('Feedback') }}
                    </x-nav-link>
                </div>
            </div>
            
            
            <!-- Notification Icon and Settings Dropdown -->
            <div class="flex items-center space-x-4">
                <!-- Notification Icon -->
                <div class="flex items-center space-x-4">
                    <!-- Notification Icon -->
                    <div class="relative">
                        <i class="fa-regular fa-bell font-semibold text-gray-500 hover:text-orange-500 cursor-pointer"></i>
                        <!-- Red dot for new notifications -->
                        <span id="notification-dot" class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-600 rounded-full hidden"></span>
                        <!-- Notification Dropdown -->
                        <div id="notification-dropdown" class="absolute right-0 mt-2 w-96 bg-white rounded-md shadow-lg py-2 z-50 hidden border border-gray-200" style="right: -50px;">
                            <h2 class="px-4 py-2 text-lg font-semibold text-gray-800 border-b">Notifications</h2>
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

            <!-- Hamburger Menu (Responsive) -->
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
            <x-responsive-nav-link :href="route('business.dashboard')" :active="request()->routeIs('business.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('business.negotiations')" :active="request()->routeIs('business.negotiations')">
                {{ __('Negotiations') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('business.reports')" :active="request()->routeIs('business.reports')">
                {{ __('Payment Reports') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('business.bookinghistory')" :active="request()->routeIs('business.bookinghistory')">
                {{ __('Booking History') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('business.feedback')" :active="request()->routeIs('business.feedback')">
                {{ __('Feedback') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
            <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center space-x-2 py-2 px-4 text-gray-700 hover:bg-orange-100">
                    <i class="fas fa-user w-5 h-5 text-gray-500"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <<form method="POST" action="{{ route('logout') }}">
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

<!-- JavaScript for Notifications -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notificationIcon = document.querySelector('.fa-bell');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const notificationDot = document.getElementById('notification-dot');
        const notificationList = document.getElementById('notification-list');
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
            if (notification.type === 'negotiation_status_update') {
                return 'Your negotiation for ' + '<strong>' + notification.data + '</strong> has been updated.';
            } else if (notification.type === 'agreement_approved') {
                return 'Your agreement was approved by ' + '<strong>' + notification.data + '</strong>.';
            } else if (notification.type === 'maintenance') {
                return notification.data;
            } else if (notification.type === 'follow-up') {
                return notification.data;
            } else if (notification.type === 'message') {
                const data = JSON.parse(notification.data); // Parse the notification data to extract senderName
                const senderName = data.senderName || 'Unknown sender'; // Default to 'Unknown sender' if no name is available
                return `You have a new message from <strong> ${senderName}. </strong>`;
            } else if (notification.type === 'negotiation') {
                return notification.data;
            } else if (notification.type === 'payment_sent') {
                return notification.data;
            } else if (notification.type === 'payment_confirmed') {
                return 'Your ' + '<strong>' + notification.data + '</strong> has been confirmed by the admin.';
            } else if (notification.type === 'feedback') {
                return notification.data;
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
            const baseNegotiationUrl = '/business/negotiations';

            // Handle the case where the notification type requires a negotiationID
            if (
                notification.type === 'message' ||
                notification.type === 'negotiation' ||
                notification.type === 'negotiation_status_update' ||
                notification.type === 'payment_confirmed' ||
                notification.type === 'agreement_approved'
            ) {
                let data;
                
                // Try to parse the notification data if it's valid JSON
                try {
                    data = JSON.parse(notification.data);
                } catch (e) {
                    console.error('Error parsing notification data:', e);
                    return baseNegotiationUrl;  // Return a default URL if parsing fails
                }

                // If the negotiationID exists, redirect to the specific negotiation
                if (data && data.negotiationID) {
                    return `${baseNegotiationUrl}/${data.negotiationID}`;
                } else {
                    return baseNegotiationUrl;  // Return the base negotiations URL if no negotiationID
                }
            } else if (notification.type === 'maintenance') {
                return '/business/dashboard';
            } else if (notification.type === 'feedback') {
                return '/business/dashboard';
            }

            return '/business/dashboard'; // Default URL if no specific match is found
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
        loadNotifications();
    });
</script>
