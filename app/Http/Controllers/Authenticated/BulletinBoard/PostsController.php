<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $users = User::with('subjects')->get();
        $like = new Like;
        $post_comment = new Post;

        $query = Post::with('user', 'postComments', 'subCategories', 'likes');

        // ① キーワード検索（タイトル or 投稿内容のあいまい検索、サブカテゴリー名の完全一致）
        if (!empty($request->keyword)) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('post_title', 'like', '%' . $keyword . '%')
                    ->orWhere('post', 'like', '%' . $keyword . '%')
                    ->orWhereHas('subCategories', function ($subQ) use ($keyword) {
                        $subQ->where('sub_category', $keyword); // 完全一致
                    });
            });
        }

        // ② いいねした投稿のみ
        if ($request->has('like_posts')) {
            $likedPostIds = Auth::user()->likePostId()->pluck('like_post_id');
            $query->whereIn('id', $likedPostIds);
        }

        // ③ 自分の投稿のみ
        if ($request->has('my_posts')) {
            $query->where('user_id', Auth::id());
        }

        // ④ サブカテゴリーで絞る
        if ($request->has('category_word')) {
            $query->whereHas('subCategories', function ($q) use ($request) {
                $q->where('sub_categories.id', $request->category_word);
            });
        }

        $posts = $query->get();

        $main_categories = \App\Models\Categories\MainCategory::with('subCategories')->get();

        return view('authenticated.bulletinboard.posts', compact('posts', 'main_categories'));
    }

    public function postDetail($post_id)
    {
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput()
    {
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body
        ]);

        // サブカテゴリーのリレーションを保存
        if ($request->has('sub_category_id')) {
            $post->subCategories()->attach($request->sub_category_id);
        }

        return redirect()->route('post.show');
    }

    public function postEdit(Request $request)
    {
        $post = Post::findOrFail($request->post_id);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'この操作を実行する権限がありません。');
        }

        $post->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);

        return redirect()->route('post.detail', ['id' => $post->id]);
    }

    public function postDelete($id)
    {
        $post = Post::findOrFail($id);

        if ($post->user_id !== Auth::id()) {
            abort(403, 'この操作を実行する権限がありません。');
        }

        $post->delete();

        return redirect()->route('post.show');
    }
    public function mainCategoryCreate(Request $request)
    {
        $request->validate([
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
        ]);

        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // サブ追加
    public function subCategoryCreate(Request $request)
    {
        $request->validate([
            'sub_category_name' => 'required|string|max:100',
            'main_category_id' => 'required|exists:main_categories,id',
        ]);

        SubCategory::create([
            'sub_category' => $request->sub_category_name,
            'main_category_id' => $request->main_category_id
        ]);

        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request)
    {
        $request->validate([
            'comment' => 'required|string|max:250',
        ]);
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard()
    {
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard()
    {
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request)
    {
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
            ->where('like_post_id', $post_id)
            ->delete();

        return response()->json();
    }
}
