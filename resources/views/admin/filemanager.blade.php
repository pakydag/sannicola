<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('File Manager (Media)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-0 text-gray-900 flex justify-center w-full" style="height: 600px;">
                    <div id="fm" style="height: 100%; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css">
        <link href="{{ asset('vendor/file-manager/css/file-manager.css') }}" rel="stylesheet">
        
        <!-- Fix Tailwind/Bootstrap conflicts for this specific page -->
        <style>
            #fm * {
                box-sizing: border-box;
            }
            #fm a { text-decoration: none; }
        </style>
        
        <script src="{{ asset('vendor/file-manager/js/file-manager.js') }}"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Set explicitly the base URL to avoid subdirectory/routing issues
                fm.$store.commit('fm/settings/manualSettings', {
                    baseUrl: '{{ url('file-manager') }}/'
                });
                
                // Manually re-trigger the axios configuration and init since the first one failed with 404
                fm.setAxiosConfig();
                fm.$store.dispatch('fm/initializeApp');
            });
        </script>
    @endpush
</x-app-layout>
