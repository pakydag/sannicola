<footer class="bg-gray-800 text-white py-8 mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center text-sm">
            <div class="mb-4 md:mb-0">
                &copy; {{ date('Y') }} {{ config('app.name', 'CRM Laravel') }}. Tutti i diritti riservati.
            </div>
            <div class="space-x-4">
                <a href="#" class="text-gray-400 hover:text-white transition">Privacy Policy</a>
                <a href="#" class="text-gray-400 hover:text-white transition">Cookie Policy</a>
                <a href="{{ route('login') }}" class="text-gray-400 hover:text-white transition">Area Riservata</a>
            </div>
        </div>
    </div>
</footer>
