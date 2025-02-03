<x-app-layout>
    <x-slot name="header">
        <title>Dashboard</title>
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight p-2">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('create.post') }}" class="font-semibold text-xl text-white dark:text-gray-200 leading-tight p-2 bg-gray-600 rounded-md">
                {{ __('Create a Post') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12 min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Posts
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($posts as $post)
                <div class="mb-8">
                    <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md mt-2">
                        <span class="text-sm text-gray-500">
                            Posted by:
                            @if($post->user)
                                {{ $post->user->name }}
                            @else
                                Unknown User
                            @endif
                        </span>
                        <h2 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $post->title }}</h2>
                        <p class="text-gray-600 dark:text-gray-300">{{ $post->content }}</p>
                        <span class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y h:i A') }}</span>

                        <!-- Display the photo as a small clickable image -->
                        @if($post->photo_blob)
                            <div class="mt-4">
                                <img src="data:image/jpeg;base64,{{ base64_encode($post->photo_blob) }}" alt="Post Photo" 
                                     class="w-32 h-32 object-cover rounded-lg shadow-md cursor-pointer" 
                                     onclick="openModal('data:image/jpeg;base64,{{ base64_encode($post->photo_blob) }}')">
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($posts->isEmpty())
                <p class="text-gray-600 dark:text-gray-300">No posts available.</p>
            @endif
        </div>
    </div>

    <!-- Modal for Image View -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <!-- Close Button with id -->
    <span id="closeModalButton" class="absolute top-2 right-2 text-white text-3xl cursor-pointer z-60">&times;</span>
    
    <!-- Modal Image -->
    <img id="modalImage" class="max-w-[90vw] max-h-[90vh] object-contain" src="" alt="Enlarged Image">
</div>



    <!-- SweetAlert2 Script -->
    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
        <script>
            Swal.fire({
                title: 'Success!',
                text: '{{ session('success') }}',
                icon: 'success',
                confirmButtonText: 'Okay'
            }).then(() => {
                window.location.href = '{{ route('dashboard') }}';
            });
        </script>
    @endif

    <script>
    // Open Modal with Image
    function openModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modal.classList.remove('hidden');
    }

    // Close Modal when clicking the close button or clicking outside the image
    function closeModal(event) {
        const modal = document.getElementById('imageModal');
        // Check if the click target is the modal background itself (not the content or close button)
        if (event.target === modal) {
            modal.classList.add('hidden');  // Close the modal
        }
    }

    // Close Modal when clicking the close button
    document.getElementById('imageModal').addEventListener('click', function(event) {
    // If the user clicks outside the image, close the modal
    if (event.target === event.currentTarget) {
        const modal = document.getElementById('imageModal');
        modal.classList.add('hidden');
    }
});
</script>


</x-app-layout>
