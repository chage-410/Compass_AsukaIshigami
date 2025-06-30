<x-sidebar>
  <div class="vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class="w-75 m-auto h-75">
      <p><strong>{{ \Carbon\Carbon::parse($date)->format('Y年m月d日') }} / {{ $part }}部</strong></p>
      <div class="h-80 border" style="border-radius:10px; background:#FFF;box-shadow: 5px 5px 5px #dee3e6; width:850px">
        <table class=" detail-table table-bordered text-center" style="margin:10px;width:830px">
          <thead>
            <tr>
              <th class="w-25">ID</th>
              <th class="w-50">名前</th>
              <th class="w-25">場所</th>
            </tr>
          </thead>
          <tbody>
            @forelse($reservePersons as $reserve)
            @foreach($reserve->users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->over_name }} {{ $user->under_name }}</td>
              <td>リモート</td>
            </tr>
            @endforeach
            @empty
            <tr>
              <td colspan="3">予約者はいません。</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-sidebar>
