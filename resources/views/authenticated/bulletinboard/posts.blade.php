<x-sidebar>
  <div class="board_area w-100 m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto">投稿一覧</p>
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>

        {{-- 投稿に紐づくサブカテゴリーを表示 --}}
        @foreach($post->subCategories as $sub)
        <button type="submit" name="category_word" value="{{ $sub->id }}" form="postSearchRequest" class="category_btn">
          {{ $sub->sub_category }}
        </button>
        @endforeach

        <div class="post_bottom_area d-flex">
          <div class="d-flex post_status">

            <!-- コメント表示 -->
            <div class="mr-5">
              <i class="fa fa-comment"></i>
              <span>{{ $post->postComments->count() }}</span>
            </div>
            <div>


              <!-- いいね表示 -->
              @if(Auth::user()->is_Like($post->id))
              <p class="m-0">
                <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
                <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
              </p>
              @else
              <p class="m-0">
                <i class="far fa-heart like_btn" post_id="{{ $post->id }}"></i>
                <span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span>
              </p>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    <div class="other_area w-25">
      <div class=" m-4">
        <div class="post_btn"><a href="{{ route('post.input') }}">投稿</a></div>

        <div class="keyword_search_box mb-3">
          <input type="text" name="keyword" placeholder="キーワードを検索" form="postSearchRequest" class="keyword_input">
          <input type="submit" value="検索" form="postSearchRequest" class="keyword_btn">
        </div>

        <div class="d-flex justify-content-between mb-3">
          <input type="submit" name="like_posts" class="search_filter_btn likepost_btn" value="いいねした投稿" form="postSearchRequest">
          <input type="submit" name="my_posts" class="search_filter_btn mypost_btn" value="自分の投稿" form="postSearchRequest">
        </div>

        <p>カテゴリー検索</p>
        <div class="accordion_area">
          @foreach($main_categories as $main)
          <div class="accordion_item">
            <div class="accordion_header">
              {{ $main->main_category }}
              <span class="accordion_icon"></span>
            </div>
            <ul class="accordion_content">
              @foreach($main->subCategories as $sub)
              <li>
                <button type="submit" name="category_word" value="{{ $sub->id }}" form="postSearchRequest" class="accordion_subcategory">
                  {{ $sub->sub_category }}
                </button>
              </li>
              @endforeach
            </ul>
          </div>
          @endforeach
        </div>

      </div>
    </div>
    <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const headers = document.querySelectorAll('.accordion_header');

      headers.forEach(header => {
        header.addEventListener('click', () => {
          const content = header.nextElementSibling;
          const icon = header.querySelector('.accordion_icon');

          const isOpen = content.style.display === 'block';

          // 閉じる
          if (isOpen) {
            content.style.display = 'none';
            icon.classList.remove('open');
          }
          // 開く
          else {
            content.style.display = 'block';
            icon.classList.add('open');
          }
        });
      });
    });
  </script>

</x-sidebar>
