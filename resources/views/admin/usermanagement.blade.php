<title>Admin User Management</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if ($currentRole === 'space_owner') 
                {{ __('Space Owner Users') }}
            @elseif ($currentRole === 'business_owner') 
                {{ __('Business Owner Users') }}
            @elseif ($currentRole === 'admin')
                {{ __('Admin Users') }}
            @else 
                {{ __('User Management') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($currentRole === 'admin')
                        <div class="mb-4">
                            <button id="open-modal" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                                Add New Admin
                            </button>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Total Users: {{ $userCount }}</h3>
                    
                    @if($users->isEmpty())
                        <p class="text-gray-500">No users found.</p>
                    @else
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b text-left">ID</th>
                                    <th class="py-2 px-4 border-b text-left">First Name</th>
                                    <th class="py-2 px-4 border-b text-left">Last Name</th>
                                    <th class="py-2 px-4 border-b text-left">Email</th>
                                    <th class="py-2 px-4 border-b text-left">Verified</th>
                                    <th class="py-2 px-4 border-b text-left">Mobile Number</th>
                                    <th class="py-2 px-4 border-b text-left">Created At</th>
                                    <th class="py-2 px-4 border-b text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    <tr>
                                        <td class="py-2 px-4 border-b">{{ $user->userID }}</td>
                                        <td class="py-2 px-4 border-b">{{ ucwords($user->firstName) }}</td>
                                        <td class="py-2 px-4 border-b">{{ ucwords($user->lastName) }}</td>
                                        <td class="py-2 px-4 border-b">{{ $user->email }}</td>
                                        <td class="py-2 px-4 border-b">{{ $user->isVerified ? 'Yes' : 'No' }}</td>
                                        <td class="py-2 px-4 border-b">{{ $user->mobileNumber}}</td>
                                        <td class="py-2 px-4 border-b">{{ $user->created_at->format('Y-m-d') }}</td>
                                        <td class="py-2 px-4 border-b">
                                        @if($currentRole !== 'admin')
                                            @if($user->isActive)
                                                <x-primary-button onclick="showDeactivationModal('{{ $user->userID }}')">
                                                    Deactivate
                                                </x-primary-button>
                                            @else
                                                <button onclick="showActivationModal('{{ $user->userID }}')" class="font-semibold border-2 bg-green-500 border-transparent ease-in-out duration-150 rounded-lg px-4 py-2 uppercase text-white text-xs hover:bg-green-700">
                                                    Activate
                                                </button>

                                                <!-- Activation Confirmation Modal -->
                                                <div id="activate-modal-{{ $user->userID }}" class="fixed z-50 inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden">
                                                    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                                                        <h3 class="text-lg font-semibold mb-4">Are you sure you want to activate this user?</h3>
                                                        <div class="flex justify-end">
                                                            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mr-2" onclick="activateUser('{{ $user->userID }}')">Yes</button>
                                                            <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="closeActivationModal('{{ $user->userID }}')">No</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Deactivation Confirmation Modal -->
                                            <div id="deactivate-modal-{{ $user->userID }}" class="fixed z-50 inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden">
                                                <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
                                                    <h3 class="text-lg font-semibold mb-4">Are you sure you want to deactivate this user?</h3>
                                                    <div class="flex justify-end">
                                                        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded mr-2" onclick="deactivateUser('{{ $user->userID }}')">Yes</button>
                                                        <button class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded" onclick="closeDeactivationModal('{{ $user->userID }}')">No</button>
                                                    </div>
                                                </div>
                                            </div>

                                        @else
                                            <span class="text-gray-500">---------</span> 
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div> 
            </div>
        </div>
    </div> 

    <!-- Modal Background -->
    <div id="add-modal" class="fixed z-50 inset-0 flex items-center justify-center bg-gray-500 bg-opacity-75 hidden">
        <div class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all w-full max-w-md">
            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Create New Admin</h3>
                <form id="add-admin-form" action="{{ route('admin.store') }}" method="POST">
                    @csrf
                    <div class="mt-2">
                        <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                        <input id="first_name" name="first_name" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mt-4">
                        <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                        <input id="last_name" name="last_name" type="text" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mt-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mt-4">
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                        <input id="mobile_number" name="mobile_number" type="tel" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mt-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input id="password" name="password" type="password" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                    <div class="mt-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 mt-1 block w-full sm:text-sm border border-gray-300 rounded-md" required>
                    </div>
                    <input type="hidden" name="role" value="admin">
                </form>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button id="submit-add-admin" type="submit" form="add-admin-form" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Add Admin
                </button>
                <button id="close-add-modal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        document.getElementById('open-modal').onclick = function() {
            document.getElementById('add-modal').classList.remove('hidden');
        };

        document.getElementById('close-add-modal').onclick = function() {
            document.getElementById('add-modal').classList.add('hidden');
        };

        document.getElementById('submit-add-admin').onclick = function() {
            document.getElementById('add-admin-form').submit();
        };

        function showActivationModal(userID) {
            document.getElementById('activate-modal-' + userID).classList.remove('hidden');
        }

        function closeActivationModal(userID) {
            document.getElementById('activate-modal-' + userID).classList.add('hidden');
        }

        function showDeactivationModal(userID) {
        document.getElementById('deactivate-modal-' + userID).classList.remove('hidden');
        }

        function closeDeactivationModal(userID) {
            document.getElementById('deactivate-modal-' + userID).classList.add('hidden');
        }

        function activateUser(userID) {
        let form = document.createElement('form');
        form.action = '/admin/usermanagement/activate/' + userID; // Update the action URL as needed
        form.method = 'POST';
        form.innerHTML = '@csrf @method("POST")'; // Include CSRF token and method
        document.body.appendChild(form);
        form.submit();
    }

        function deactivateUser(userID) {
            let form = document.createElement('form');
            form.action = '/admin/usermanagement/deactivate/' + userID;
            form.method = 'POST';
            form.innerHTML = '@csrf @method("POST")';
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</x-app-layout>
