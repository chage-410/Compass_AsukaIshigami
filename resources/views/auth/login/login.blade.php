<x-guest-layout>
  <form action="{{ route('loginPost') }}" method="POST">
    <div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center; background: #ecf1f6;display: flex;
  flex-direction: column;">
      <div class="service-logo" style="text-align : center;  ">
        <img src="{{ asset('image/atlas-black.png') }}" alt="Atlas" class="logo-image" style="display:block; width: auto;height: 80px;margin: 0 auto; margin-bottom:30px;">
      </div>
      <div class="border vh-50 w-25" style="background: #fff;  border-radius: 15px; box-shadow: 5px 5px 5px #dee3e6;">

        <div class="w-75 m-auto pt-5">
          @error('mail_address')
          <div class="text-danger">{{ $message }}</div>
          @enderror
          <label class="d-block m-0" style="font-size:13px;">メールアドレス</label>
          <div class="border-bottom border-primary w-100">
            <input type="text" class="w-100 border-0" name="mail_address">
          </div>
        </div>

        <div class="w-75 m-auto pt-5">
          @error('password')
          <div class="text-danger">{{ $message }}</div>
          @enderror
          <label class="d-block m-0" style="font-size:13px;">パスワード</label>
          <div class="border-bottom border-primary w-100">
            <input type="password" class="w-100 border-0" name="password">
          </div>
        </div>
        <div class="text-right m-3">
          <input type="submit" class="btn btn-primary" value="ログイン">
        </div>
        <div class="text-center" style="margin-bottom: 18px;">
          <a href="{{ route('registerView') }}">新規登録はこちら</a>
        </div>
      </div>
      {{ csrf_field() }}
    </div>
  </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
</x-guest-layout>
