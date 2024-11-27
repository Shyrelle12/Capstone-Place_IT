<title>Feedback</title>
<style>
    /* Styles remain the same */
    .rate-emoji {
        transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s ease-in-out;
        padding: 10px;
        border-radius: 50%; /* Fully rounded appearance */
    }

    .rate-emoji:hover {
        transform: scale(1.2); /* Slight zoom effect on hover */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
    }

    .rate-emoji.selected {
        background-color: #ff7f3f;
        color: white;
    }

    .feedback-container {
        border-radius: 1.5rem;
        overflow: hidden;
        background: linear-gradient(135deg, #ffb88e, #ea5753);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .submit-btn {
        border-radius: 1.5rem;
        transition: background-color 0.3s, transform 0.2s;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .submit-btn:hover {
        background-color: #e86d3a;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }

    .submit-btn:focus {
        outline: none;
        ring: 4px rgba(255, 163, 87, 0.6);
    }
</style>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Feedback') }}
        </h2>
    </x-slot>

    <div class="w-full py-6 flex justify-center px-4 sm:px-0">
        <div class="max-w-2xl w-full">
            <div class="bg-white p-6 sm:p-12 shadow-xl rounded-2xl feedback-container">
                
                <!-- Feedback Title -->
                <div class="text-center mb-8">
                    <h3 class="text-xl sm:text-2xl font-semibold text-gray-800">We Value Your Feedback!</h3>
                    <p class="text-sm sm:text-base text-gray-600 mt-2">Tell us how you feel about using our platform.</p>
                    <p class="text-sm sm:text-base text-gray-600">Your feedback is valuable to us and helps improve our system, PlaceIt.</p>
                    <p class="text-sm sm:text-base text-gray-600">Please share your experience to help us grow and serve you better!</p>
                </div>

                <!-- Feedback Experience Section -->
                <form action="{{ route('space.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Rating -->
                    <div class="text-center">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-700">How was your experience?</h4>
                        <p class="text-sm text-gray-500 mb-4">Click an emoji to rate us!</p>
                        <div class="flex flex-wrap justify-center gap-4">
                            @for ($i = 1; $i <= 5; $i++)
                                <label class="flex flex-col items-center cursor-pointer">
                                    <input type="radio" name="rate" value="{{ $i }}" class="hidden rate-input" required>
                                    <span class="text-3xl sm:text-4xl rate-emoji" data-value="{{ $i }}">
                                        @php
                                            echo ['😡', '😞', '😐', '😀', '😊'][$i - 1];
                                        @endphp
                                    </span>
                                    <span class="text-yellow-500 text-xs sm:text-sm mt-1">{{ str_repeat('⭐', $i) }}</span>
                                </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Feedback Section -->
                    <div class="text-center mt-6">
                        <h4 class="text-base sm:text-lg font-semibold text-gray-700">Leave your feedback</h4>
                        <p class="text-sm text-gray-500">We'd love to hear your thoughts!</p>
                    </div>

                    <!-- Hidden Renter ID -->
                    <input type="hidden" name="renterID" value="{{ auth()->id() }}">

                    <!-- Feedback Textarea -->
                    <div class="mb-6">
                        <textarea name="feedback_content" rows="4" class="w-full p-4 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-400" placeholder="Leave your feedback here..." required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="bg-orange-500 text-white py-3 px-6 rounded-2xl submit-btn hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400">
                            Submit Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.rate-emoji').forEach(emoji => {
            emoji.addEventListener('click', function() {
                document.querySelectorAll('.rate-emoji').forEach(e => e.classList.remove('selected'));
                this.classList.add('selected');
                const value = this.getAttribute('data-value');
                const radioButton = document.querySelector(`input[name="rate"][value="${value}"]`);
                if (radioButton) {
                    radioButton.checked = true;
                }
            });
        });
    </script>
</x-app-layout>
