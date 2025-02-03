<title>Edit Post</title>

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight p-2">
            {{ __('Edit Post') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-2">
        <form action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">
            </div>

            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Content</label>
                <textarea name="content" id="content" rows="5" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600">{{ old('content', $post->content) }}</textarea>
            </div>

            <!-- Display Existing Images (if any) -->
            @if($post->photos_blob)
    @php
        $photos = json_decode($post->photos_blob, true);
    @endphp
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Existing Images</label>
        <div class="flex space-x-4 mt-2">
            @foreach($photos as $photo)
                <img src="data:image/jpeg;base64,{{ $photo }}" alt="Post Photo" class="w-32 h-32 object-cover rounded-lg shadow-md">
            @endforeach
        </div>
    </div>
@endif


            <!-- Image Upload -->
            <div class="mb-4">
            <div class="mb-4">
    <label for="photos" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Replace Images</label>
    <input type="file" name="photos[]" id="photos" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg dark:bg-gray-800 dark:text-gray-100 dark:border-gray-600" multiple>
</div>
            </div>

            <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-700 text-white rounded-lg">Update Post</button>
        </form>
    </div>
</x-app-layout>
