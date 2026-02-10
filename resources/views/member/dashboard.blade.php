@extends('member.includes.app')
@section('content')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<div class="container mx-auto px-4 py-6">
    <!-- Existing Welcome Section -->
    <div class="mb-8 bg-gradient-to-r from-blue-600 to-purple-700 p-6 rounded-xl shadow-lg">
        <h2 class="text-3xl font-extrabold text-white tracking-wide animate-fade-in">
            Welcome back, {{ Auth::user()->name }}!
        </h2>
        <div class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-4 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-white bg-blue-500 p-3 rounded-full">
                    <i class="fas fa-id-card text-lg"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-200">Membership</p>
                    <p class="text-sm font-semibold text-white">{{ $membership['name'] }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-4 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-white bg-green-500 p-3 rounded-full">
                    <i class="fas fa-calendar-alt text-lg"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-200">Start Date</p>
                    <p class="text-sm font-semibold text-white">{{ $membership['start_date'] }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm p-4 rounded-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="text-white bg-red-500 p-3 rounded-full">
                    <i class="fas fa-calendar-times text-lg"></i>
                </div>
                <div>
                    <p class="text-xs uppercase tracking-wider text-gray-200">End Date</p>
                    <p class="text-sm font-semibold text-white">{{ $membership['end_date'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Bagian Kartu Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        @php
            $statsCards = [
                ['icon' => 'fa-folder', 'title' => 'Total Projects', 'value' => $totalProjects, 'color' => 'bg-blue-500'],
                ['icon' => 'fa-users', 'title' => 'Total Users', 'value' => $totalUsers, 'color' => 'bg-green-500'],
                ['icon' => 'fa-tasks', 'title' => 'Total Tasks', 'value' => $totalTasks, 'color' => 'bg-indigo-500'],
                ['icon' => 'fas fa-user-cog', 'title' => 'Total Roles', 'value' => $totalRoles, 'color' => 'bg-pink-500']
            ];
        @endphp

        @foreach($statsCards as $card)
            <div class="transform transition-all duration-300 hover:scale-105 hover:shadow-xl">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 flex items-center space-x-4">
                    <div class="{{ $card['color'] }} text-white p-4 rounded-lg">
                        <i class="fas {{ $card['icon'] }} text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm uppercase tracking-wider">
                            {{ $card['title'] }}
                        </p>
                        <h3 class="text-3xl font-bold text-gray-800 dark:text-white">
                            {{ $card['value'] }}
                        </h3>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Enhanced Memberships List -->
    <div class="mb-10">
        <div class="text-center mb-8">
            <h3 class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent mb-2">
                Choose Your Membership Plan
            </h3>
            <p class="text-gray-600 dark:text-gray-400 text-lg">Unlock premium features and take your experience to the next level</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($memberships as $index => $membership)
                @php
                    $gradients = [
                        'from-blue-500 to-purple-600',
                        'from-emerald-500 to-teal-600',
                        'from-orange-500 to-red-600',
                        'from-pink-500 to-rose-600',
                        'from-indigo-500 to-blue-600',
                        'from-green-500 to-emerald-600'
                    ];
                    $currentGradient = $gradients[$index % count($gradients)];
                    $isPopular = $index === 1; // Make second item popular
                @endphp

                <div class="relative group">
                    @if($isPopular)
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 z-10">
                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 text-white px-4 py-1 rounded-full text-sm font-bold shadow-lg">
                                <i class="fas fa-star mr-1"></i>Most Popular
                            </div>
                        </div>
                    @endif

                    <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 transform transition-all duration-500 hover:scale-105 hover:shadow-2xl border {{ $isPopular ? 'border-yellow-400 border-2' : 'border-gray-200 dark:border-gray-700' }} overflow-hidden">
                        <!-- Background Pattern -->
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br {{ $currentGradient }} opacity-10 rounded-full -mr-16 -mt-16"></div>

                        <!-- Header -->
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br {{ $currentGradient }} rounded-full mb-4 shadow-lg">
                                <i class="fas fa-crown text-white text-2xl"></i>
                            </div>
                            <h4 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">{{ $membership->name }}</h4>
                            <div class="flex items-center justify-center">
                                <span class="text-4xl font-bold bg-gradient-to-r {{ $currentGradient }} bg-clip-text text-transparent">
                                    @if($membership->id == 1)
                                        Free
                                    @else
                                        ${{ number_format($membership->price, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Features -->
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-600 dark:text-gray-300">Valid for {{ $membership->valid_days }} days</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-600 dark:text-gray-300">Premium Features Access</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-600 dark:text-gray-300">24/7 Priority Support</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0 w-5 h-5 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-xs"></i>
                                </div>
                                <span class="text-gray-600 dark:text-gray-300">Advanced Analytics</span>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        @if($membership->id == 1)
                            <button class="w-full bg-gradient-to-r {{ $currentGradient }} text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300"
                                    onclick="activateFreeMembership({{ $membership->id }})">
                                <i class="fas fa-check-circle mr-2"></i>
                                Activate Free Plan
                            </button>
                        @else
                            <button class="w-full bg-gradient-to-r {{ $currentGradient }} text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform transition-all duration-300 hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-300"
                                    onclick="initiatePayment({{ $membership->id }}, '{{ $membership->name }}', {{ $membership->price }})">
                                <i class="fas fa-shopping-cart mr-2"></i>
                                Get Started Now
                            </button>
                        @endif

                        <!-- Guarantee Badge -->
                        <div class="text-center mt-4">
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-shield-alt mr-1"></i>
                                30-day money back guarantee
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Add Midtrans Snap.js -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
    // Function to activate Free membership (membership_id = 1)
    function activateFreeMembership(membershipId) {
        if (confirm('Are you sure you want to activate the Free membership plan?')) {
            fetch('{{ route('member.purchase-membership') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    membership_id: membershipId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Free membership activated successfully!');
                    window.location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while activating the membership.');
            });
        }
    }

    // Function to initiate Midtrans payment for Pro and Enterprise plans
    function initiatePayment(membershipId, membershipName, price) {
        fetch('{{ route('member.generate-snap-token') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                membership_id: membershipId,
                membership_name: membershipName,
                price: price
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.snapToken) {
                // Open Midtrans Snap payment popup
                snap.pay(data.snapToken, {
                    onSuccess: function(result) {
                        // Handle successful payment
                        handlePaymentResult('success', result, membershipId);
                    },
                    onPending: function(result) {
                        // Handle pending payment
                        handlePaymentResult('pending', result, membershipId);
                    },
                    onError: function(result) {
                        // Handle payment error
                        handlePaymentResult('error', result, membershipId);
                    },
                    onClose: function() {
                        alert('You closed the payment popup.');
                    }
                });
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while initiating the payment.');
        });
    }

    // Function to handle payment result
    function handlePaymentResult(status, result, membershipId) {
        fetch('{{ route('member.handle-payment') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                status: status,
                payment_result: result,
                membership_id: membershipId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Payment ' + status + '! Transaction ID: ' + result.transaction_id);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while processing the payment result.');
        });
    }
</script>

<style>
    @keyframes fade-in {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-fade-in {
        animation: fade-in 0.6s ease-out;
    }

    .group:hover .group-hover\:scale-110 {
        transform: scale(1.1);
    }
</style>

@endsection
