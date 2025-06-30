<x-sidebar>
  <div class="vh-100" style="background:#ECF1F6;">
    <div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
      <div class=" border w-75 m-auto pt-5 pb-5 " style="border-radius:5px; background:#FFF;box-shadow: 5px 5px 5px #dee3e6;">
        <p class="text-center">{{ $calendar->getTitle() }}</p>
        {!! $calendar->render() !!}
        <div class=" adjust-table-btn m-auto text-right">
          <input type="submit" class="btn btn-primary" style="margin-top:15px;" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
        </div>
      </div>
    </div>
</x-sidebar>
