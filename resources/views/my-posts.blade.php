<x-app-layout>
    <x-slot name="header">
        <title>My Posts</title>
        <div class="mb-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('My Posts') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-2">
        @foreach($posts as $post)
            <div class="mb-8">
                <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md">
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
                    <span class="text-sm text-gray-500">{{ $post->created_at->format('M d, Y') }}</span>
                    @if($post->photo_blob)
                            <div class="mt-4">
                                <img src="data:image/jpeg;base64,{{ base64_encode($post->photo_blob) }}" alt="Post Photo" 
                                     class="w-32 h-32 object-cover rounded-lg shadow-md cursor-pointer" 
                                     onclick="openModal('data:image/jpeg;base64,{{ base64_encode($post->photo_blob) }}')">
                            </div>
                            @endif
                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 mt-4">
                        <!-- Edit Button -->
                        <a href="{{ route('posts.edit-post', $post->id) }}" class="text-white hover:text-blue-700 p-2 bg-blue-600 rounded-md">
                            Edit
                        </a>
                        
                        <!-- Delete Button -->
                        <button type="button" onclick="deletePost({{ $post->id }})" class="text-white hover:text-red-700 p-2 bg-red-600 rounded-md">
                            Delete
                        </button>
                    </div>

                    <!-- Delete Form (hidden, will be triggered by SweetAlert2) -->
                    <form id="delete-form-{{ $post->id }}" action="{{ route('posts.destroy', $post->id) }}" method="POST" class="hidden">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        @endforeach

        @if($posts->isEmpty())
            <p class="text-gray-600 dark:text-gray-300">No posts available.</p>
        @endif
    </div>

    <div id="imageModal" class="fixed inset-0 z-50 hidden bg-gray-800 bg-opacity-50 flex justify-center items-center">
    <!-- Close Button with id -->
    <span id="closeModalButton" class="absolute top-2 right-2 text-white text-3xl cursor-pointer z-60">&times;</span>
    
    <!-- Modal Image -->
    <img id="modalImage" class="max-w-[90vw] max-h-[90vh] object-contain" src="" alt="Enlarged Image">
</div>

    <!-- SweetAlert2 Script with defer -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Your Custom Script (placed after SweetAlert2 script) -->
    <script>
        function deletePost(postId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Trigger the delete form submission after confirmation
                    document.getElementById('delete-form-' + postId).submit();
                }
            });
        }
    </script>


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