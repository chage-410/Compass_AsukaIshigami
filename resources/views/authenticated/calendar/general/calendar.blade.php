<x-sidebar>

  <div class="vh-100 pt-5" style="background:#ECF1F6;">
    <div class="border w-75 m-auto pt-5" style="border-radius:5px; background:#FFF;box-shadow: 5px 5px 5px #dee3e6;">
      <div class="m-auto" style="width:800px;">


        <p class="text-center">{{ $calendar->getTitle() }}</p>
        <div class="">
          {!! $calendar->render() !!}
        </div>
      </div>
      <div class="text-right w-75 m-auto">
        <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts" style="margin-bottom:15px">
      </div>
    </div>
  </div>

  <!-- 予約キャンセル確認モーダル -->
  <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <form method="POST" action="{{ route('deleteParts') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-body">
            <p><strong>予約日：</strong><span id="modal-date"></span></p>
            <p><strong>時間：</strong><span id="modal-part"></span></p>
            <p>上記の予約をキャンセルしてもよろしいですか？</p>
            <input type="hidden" name="delete_date" id="hidden-delete-date">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">閉じる</button>
            <button type="submit" class="btn btn-danger">キャンセルする</button>
          </div>
        </div>
      </form>
    </div>
  </div>


  <!-- jQuery & Bootstrap JS（CDN） - integrity属性削除 -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(function() {
      $(document).on('click', '[data-toggle="modal"][data-target="#cancelModal"]', function() {
        const date = $(this).data('date');
        const part = $(this).data('part');

        let partLabel = '';
        switch (part) {
          case 1:
          case '1':
            partLabel = 'リモ1部';
            break;
          case 2:
          case '2':
            partLabel = 'リモ2部';
            break;
          case 3:
          case '3':
            partLabel = 'リモ3部';
            break;
        }

        const modal = $('#cancelModal');
        modal.find('#modal-date').text(date);
        modal.find('#modal-part').text(partLabel);
        modal.find('#hidden-delete-date').val(date);
      });
    });
  </script>

</x-sidebar>
