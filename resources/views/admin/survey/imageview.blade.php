<div id="All" class="tabcontent active">
    <div class="card example-1 square scrollbar-dusty-grass square thin">
        <div id="images-wrapper">
            @foreach ($photo as $key => $value)
                <img src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="p-1 select_image"  draggable="true" ondragstart="imgDrag(event)">
            @endforeach
        </div>
    </div>
</div>
<div id="Unsplash" class="tabcontent">
    <div class="card example-1 square scrollbar-dusty-grass square thin">
        <div id="images-wrapper">
            @foreach ($photo as $key => $value)
                <img src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="p-1 select_image"  draggable="true" ondragstart="imgDrag(event)">
            @endforeach
        </div>
    </div> 
</div>
<div id="Pixabay" class="tabcontent">
    <div class="card example-1 square scrollbar-dusty-grass square thin">
        <div id="images-wrapper">
            <!-- @foreach ($photo as $key => $value)
                <img src="{{$value->urls->small}}" alt="{{$value->alt_description}}" class="p-1 select_image"  draggable="true" ondragstart="imgDrag(event)">
            @endforeach -->
        </div>
    </div>
</div>