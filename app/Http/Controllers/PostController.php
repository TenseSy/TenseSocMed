<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function create()
    {
        return view('create-post');
    }

    public function store(Request $request)
    {
        // Validate input
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
    
        // Check if there's an existing post to duplicate
        if ($request->has('duplicate_post_id')) {
            // Your existing duplication logic
        } else {
            // Create a new post if not duplicating
            $postData = [
                'title' => $request->title,
                'content' => $request->content,
                'user_id' => auth()->id(),
            ];
    
            // Handle photo upload if present
            if ($request->hasFile('photo')) {
                $photo = file_get_contents($request->file('photo')->getRealPath());
                $postData['photo_blob'] = $photo;
            }
    
            $post = Post::create($postData);
    
            return redirect()->route('posts.index')->with('success', 'Post created successfully!');
        }
    }
    

    public function index()
    {
        // Fetch all posts
        $posts = Post::with('user')->get();
        // $posts = Post::all(); 

        // Return the view with posts
        return view('dashboard', compact('posts'));
    }

    public function myPosts()
{
    // Fetch posts created by the authenticated user
    $posts = Post::where('user_id', auth()->id())->get();

    // Return the view and pass the posts variable
    return view('my-posts', compact('posts'));
}

public function edit(Post $post)
{
    // Ensure the authenticated user is the owner of the post
    if ($post->user_id !== auth()->id()) {
        return redirect()->route('my-posts')->with('error', 'Unauthorized access!');
    }

    // Return the correct view
    return view('edit-post', compact('post'));  // Update to reference the 'edit-post' view
}
public function update(Request $request, Post $post)
{
    // Ensure the authenticated user is the owner of the post
    if ($post->user_id !== auth()->id()) {
        return redirect()->route('my-posts')->with('error', 'Unauthorized access!');
    }

    // Validate the request
    $request->validate([
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'photos' => 'nullable|array',
        'photos.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048', // Validate images
    ]);

    // Prepare update data
    $updateData = [
        'title' => $request->title,
        'content' => $request->content,
    ];

    // Check if new images are uploaded
    if ($request->hasFile('photos')) {
        $images = [];
        foreach ($request->file('photos') as $photo) {
            $images[] = base64_encode(file_get_contents($photo->getRealPath()));
        }

        // Store the images in JSON format
        $updateData['photos_blob'] = json_encode($images);
    } else {
        // Keep the existing photos if no new ones are uploaded
        $updateData['photos_blob'] = $post->photos_blob;
    }

    // Update the post
    $post->update($updateData);

    return redirect()->route('my-posts')->with('success', 'Post updated successfully!');
}



public function destroy(Post $post)
{
    // Ensure the authenticated user is the owner of the post
    if ($post->user_id !== auth()->id()) {
        return redirect()->route('my-posts')->with('error', 'Unauthorized access!');
    }

    // Delete the post
    $post->delete();

    // Redirect with success message
    return redirect()->route('my-posts')->with('success', 'Post deleted successfully!');
}

public function dashboard()
{
    // You could fetch posts or other dashboard-related data
    $posts = Post::with('user')->get();
    
    // Return a specific view for the dashboard
    return view('dashboard', compact('posts'));
}

}

