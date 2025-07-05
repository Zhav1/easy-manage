<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Notifikasi - EasyManage</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/notifikasi.css') }}">
    <script>
        window.authToken = "{{ session('token') }}";
    </script>
    <script src="{{ asset('js/notifikasi.js') }}"></script>
</head>
<body class="min-h-full bg-gradient-to-br from-indigo-100 via-purple-50 to-pink-100">
    @include('components.sidebar-navbar')
    
    <div class="floating mt-16 md:mt-16">
        <div class="container mx-auto px-4 md:px-6 pt-4 md:pt-8 md:pl-60 md:pr-5">
            <div class="glass-effect rounded-2xl p-4 md:p-6 mb-6 md:mb-8 shadow-2xl">
                <div class="flex items-center justify-between mobile-stack">
                    <div class="flex items-center space-x-3 md:space-x-4 mobile-center">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 p-2 md:p-3 rounded-xl shadow-lg">
                            <i class="fas fa-bell text-white text-xl md:text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Notifikasi</h1>
                            <p class="text-gray-600 mt-1 mobile-small-text">Kelola pesan dan approval Anda</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 md:px-6 pb-6 md:pb-8 md:pl-60 md:pr-5">
        <div class="glass-effect rounded-2xl shadow-2xl overflow-hidden">
            
            <div class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                <div class="flex overflow-x-auto">
                    <button onclick="showTab('reminder')" id="tab-reminder"
                        class="tab-button flex-1 py-3 md:py-4 px-4 md:px-6 text-center font-semibold text-blue-600 bg-white border-b-4 border-blue-500 relative min-w-max">
                        <i class="fas fa-clock mr-2"></i>
                        Reminder
                        <span class="absolute top-1 md:top-2 right-1 md:right-2 bg-blue-500 text-white text-xs rounded-full px-2 py-1">0</span>
                    </button>
                    {{-- Add other tabs here if you have them, e.g., Approval, Messages --}}
                    {{-- <button onclick="showTab('approval')" id="tab-approval" class="tab-button flex-1 py-3 md:py-4 px-4 md:px-6 text-center font-semibold text-gray-600 bg-gray-50 border-b-4 border-transparent hover:border-gray-300 relative min-w-max"> --}}
                        {{-- <i class="fas fa-check-circle mr-2"></i> --}}
                        {{-- Approval --}}
                        {{-- <span class="absolute top-1 md:top-2 right-1 md:right-2 bg-green-500 text-white text-xs rounded-full px-2 py-1">0</span> --}}
                    {{-- </button> --}}
                </div>
            </div>

            <div class="p-4 md:p-8">
                
                <div id="content-reminder" class="slide-in">
                    <div class="space-y-4 md:space-y-6" id="notifications-list-container">
                        </div>
                </div>

                {{-- Add other tab content divs here, e.g., content-approval --}}
                {{-- <div id="content-approval" class="slide-in hidden"> --}}
                    {{-- <p class="text-gray-600">This is the Approval content area.</p> --}}
                {{-- </div> --}}

            </div>
        </div>
    </div>
    <div id="laporan-notification-container"></div> {{-- Added for showNotification helper --}}

    {{-- No Flowbite needed if you're managing modals/tabs manually with JS --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script> --}}
</body>
</html>