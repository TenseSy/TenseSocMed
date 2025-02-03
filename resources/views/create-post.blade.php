<x-app-layout>
    <x-slot name="header">
        <title>Create Post</title>
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create a Post') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="createPostForm" action="{{ route('create.post') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" id="title" class="text-black mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                            <textarea name="content" id="content" rows="4" class="text-black mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="photo" class="block text-sm font-medium text-gray-700">Upload Photo (optional)</label>
                            <input type="file" name="photo" id="photo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <!-- Photo Preview and Progress Bar -->
                        <div id="photoContainer" class="relative mb-4">
                            <img id="photoPreview" src="" alt="Image Preview" class="hidden w-auto max-h-32 rounded-md"> <!-- Smaller preview size -->
                            <div id="progressContainer" class="absolute top-0 left-0 w-full h-full bg-gray-800 opacity-50 hidden">
                                <div id="progressBar" class="bg-blue-600 text-white text-center h-4 rounded-md" style="width: 0%;"></div>
                            </div>
                        </div>

                        <div class="flex justify-end mt-4">
                            <button id="submitBtn" type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-75" disabled>
                                Create Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('createPostForm');
            const submitBtn = document.getElementById('submitBtn');
            const progressContainer = document.getElementById('progressContainer');
            const progressBar = document.getElementById('progressBar');
            const photoInput = document.getElementById('photo');
            const photoPreview = document.getElementById('photoPreview');
            const photoContainer = document.getElementById('photoContainer');

            // Show photo preview when selected
            photoInput.addEventListener('change', function () {
                const file = photoInput.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        photoPreview.src = e.target.result;
                        photoPreview.classList.remove('hidden');
                        photoContainer.classList.add('relative');
                    };
                    reader.readAsDataURL(file);
                } else {
                    photoPreview.classList.add('hidden');
                }
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();

                // Show progress bar
                progressContainer.classList.remove('hidden');
                progressBar.style.width = '0%';

                // Disable the submit button
                submitBtn.disabled = true;

                xhr.open('POST', form.action, true);
                
                // Update the progress bar as the upload progresses
                xhr.upload.onprogress = function (event) {
                    if (event.lengthComputable) {
                        let percent = (event.loaded / event.total) * 100;
                        progressBar.style.width = percent + '%';
                    }
                };

                // When the request finishes
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        Swal.fire({
                            title: 'Success!',
                            text: 'Post created successfully!',
                            icon: 'success',
                            confirmButtonText: 'Okay'
                        }).then(function() {
                            window.location.href = '{{ route('posts.index') }}';  // Redirect to the posts page
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred. Please try again.',
                            icon: 'error',
                            confirmButtonText: 'Okay'
                        });
                    }
                    progressBar.style.width = '100%';  // Ensure the bar fills completely
                };

                // Send the form data (including the photo)
                xhr.send(formData);
            });

            // Enable the submit button only after the user fills in the form
            form.addEventListener('input', function () {
                if (form.title.value && form.content.value) {
                    submitBtn.disabled = false;
                } else {
                    submitBtn.disabled = true;
                }
            });
        });
    </script>
</x-app-layout>
