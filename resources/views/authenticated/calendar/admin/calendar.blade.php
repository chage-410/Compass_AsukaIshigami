<x-sidebar>
  <div class="w-100 vh-100 d-flex" style="align-items:center; justify-content:center;">
    <div class=" border w-75 m-auto pt-5 pb-5 " style="border-radius:5px; background:#FFF;box-shadow: 5px 5px 5px #dee3e6;">
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <p>{!! $calendar->render() !!}</p>
    </div>
</x-sidebar>
