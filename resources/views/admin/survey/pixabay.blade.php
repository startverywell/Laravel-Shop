@foreach ($pixa_photo as $key => $value)
    <img loading="lazy" src="{{$value->imageURL}}" class="select_image"  draggable="true" ondragstart="imgDrag(event)">
@endforeach