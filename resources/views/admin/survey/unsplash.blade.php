@foreach ($photo as $key => $value)
    <img loading="lazy" src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
@endforeach